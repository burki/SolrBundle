<?php

namespace FS\SolrBundle\Doctrine\ORM\Listener;

use DeepCopy\DeepCopy;
use DeepCopy\Filter\Doctrine\DoctrineEmptyCollectionFilter;
use DeepCopy\Matcher\PropertyTypeMatcher;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use FS\SolrBundle\Doctrine\AbstractIndexingListener;

class EntityIndexer extends AbstractIndexingListener
{
    /**
     * @var array
     */
    private $persistedEntities = [];

    /**
     * @var array
     */
    private $deletedRootEntities = [];

    /**
     * @var array
     */
    private $deletedNestedEntities = [];

    /**
     * @param PostUpdateEventArgs $args
     */
    public function postUpdate(PostUpdateEventArgs $args)
    {
        // in Doctrine ORM3, $args is a subclass of Doctrine\Persistence\Event\EventArgs
        $entity = method_exists($args, 'getEntity') ? $args->getEntity() : $args->getObject();

        if ($this->isAbleToIndex($entity) === false) {
            return;
        }

        $entityManager = method_exists($args, 'getEntityManager')
            ? $args->getEntityManager() : $args->getObjectManager();

        $doctrineChangeSet = $entityManager->getUnitOfWork()->getEntityChangeSet($entity);
        try {
            if ($this->hasChanged($doctrineChangeSet, $entity) === false) {
                return;
            }

            $this->solr->updateDocument($entity);
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }

    /**
     * @param PostPersistEventArgs $args
     */
    public function postPersist(PostPersistEventArgs $args)
    {
        // in Doctrine ORM3, $args is a subclass of Doctrine\Persistence\Event\EventArgs
        $entity = method_exists($args, 'getEntity') ? $args->getEntity() : $args->getObject();

        if ($this->isAbleToIndex($entity) === false) {
            return;
        }

        $this->persistedEntities[] = $entity;
    }

    /**
     * @param PreRemoveEventArgs $args
     */
    public function preRemove(PreRemoveEventArgs $args)
    {
        // in Doctrine ORM3, $args is a subclass of Doctrine\Persistence\Event\EventArgs
        $entity = method_exists($args, 'getEntity') ? $args->getEntity() : $args->getObject();

        if ($this->isAbleToIndex($entity) === false) {
            return;
        }

        if ($this->isNested($entity)) {
            $this->deletedNestedEntities[] = $this->emptyCollections($entity);
        } else {
            $this->deletedRootEntities[] = $this->emptyCollections($entity);
        }
    }

    /**
     * @param object $object
     *
     * @return object
     */
    private function emptyCollections($object)
    {
        $deepcopy = new DeepCopy();
        $deepcopy->addFilter(new DoctrineEmptyCollectionFilter(), new PropertyTypeMatcher('Doctrine\Common\Collections\Collection'));

        return $deepcopy->copy($object);
    }

    /**
     * @param PostFlushEventArgs $eventArgs
     */
    public function postFlush(PostFlushEventArgs $eventArgs)
    {
        foreach ($this->persistedEntities as $entity) {
            $this->solr->addDocument($entity);
        }
        $this->persistedEntities = [];

        foreach ($this->deletedRootEntities as $entity) {
            $this->solr->removeDocument($entity);
        }
        $this->deletedRootEntities = [];

        foreach ($this->deletedNestedEntities as $entity) {
            $this->solr->removeDocument($entity);
        }
        $this->deletedNestedEntities = [];
    }
}
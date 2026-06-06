<?php

namespace FS\SolrBundle\Tests\Doctrine\ORM\Listener;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\UnitOfWork;
use FS\SolrBundle\Attribute\AttributeReader;
use FS\SolrBundle\Doctrine\Mapper\MetaInformationFactory;
use FS\SolrBundle\Doctrine\ORM\Listener\EntityIndexer;
use FS\SolrBundle\SolrInterface;
use FS\SolrBundle\Tests\Fixtures\NestedEntity;
use FS\SolrBundle\Tests\Fixtures\NotIndexedEntity;
use FS\SolrBundle\Tests\Fixtures\ValidTestEntityWithCollection;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Tests\Constraints\ValidTest;

class EntityIndexerTest extends TestCase
{
    /**
     * @var EntityIndexer
     */
    private $subscriber;

    private $solr;

    private $metaInformationFactory;

    private $logger;

    public function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->solr = $this->createMock(SolrInterface::class);
        $this->metaInformationFactory = new MetaInformationFactory(new AttributeReader());

        $this->subscriber = new EntityIndexer($this->solr, $this->metaInformationFactory, $this->logger);
    }

    /**
     * @test
     */
    public function separteDeletedRootEntitiesFromNested()
    {
        $nested = new NestedEntity();
        $nested->setId(uniqid());

        $entity = new ValidTestEntityWithCollection();
        $entity->setId(uniqid());
        $entity->setCollection(new ArrayCollection([$nested]));

        $objectManager = $this->createMock(EntityManagerInterface::class);

        $this->solr->expects(self::exactly(2))
            ->method('removeDocument')
            ->willReturnOnConsecutiveCalls(
                $this->callback(function (ValidTestEntityWithCollection $entity) {
                    if (count($entity->getCollection())) {
                        return false;
                    }

                    return true;
                }),
                $this->callback(function ($entity) {
                    if (!$entity instanceof NestedEntity) {
                        return false;
                    }

                    return true;
                })
            );

        $deleteRootEntityEvent = new LifecycleEventArgs($entity, $objectManager);
        $this->subscriber->preRemove($deleteRootEntityEvent);

        $deleteNestedEntityEvent = new LifecycleEventArgs($nested, $objectManager);
        $this->subscriber->preRemove($deleteNestedEntityEvent);

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $this->subscriber->postFlush(new PostFlushEventArgs($entityManager));
    }

    /**
     * @test
     */
    public function indexOnlyModifiedEntites()
    {
        $changedEntity = new ValidTestEntityWithCollection();
        $this->solr->expects($this->once())
            ->method('updateDocument')
            ->with($changedEntity);

        $unitOfWork = $this->createMock(UnitOfWork::class);
        $unitOfWork->expects(self::exactly(2))
            ->method('getEntityChangeSet')
            ->willReturnOnConsecutiveCalls(
                ['title' => 'value'],
                []
            );

        $objectManager = $this->createMock(EntityManagerInterface::class);
        $objectManager->expects($this->any())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWork);

        $updateEntityEvent1 = new LifecycleEventArgs($changedEntity, $objectManager);

        $unmodifiedEntity = new ValidTestEntityWithCollection();
        $updateEntityEvent2 = new LifecycleEventArgs($unmodifiedEntity, $objectManager);

        $this->subscriber->postUpdate($updateEntityEvent1);
        $this->subscriber->postUpdate($updateEntityEvent2);
    }

    /**
     * @test
     */
    public function doNotFailHardIfNormalEntityIsPersisted()
    {
        $this->solr->expects($this->never())
            ->method('addDocument');

        $this->solr->expects($this->never())
            ->method('removeDocument');

        $entity = new NotIndexedEntity();

        $objectManager = $this->createMock(EntityManagerInterface::class);

        $lifecycleEventArgs = new LifecycleEventArgs($entity, $objectManager);

        $this->subscriber->postPersist($lifecycleEventArgs);
        $this->subscriber->preRemove($lifecycleEventArgs);

        $this->subscriber->postFlush(new PostFlushEventArgs($objectManager));
    }
}

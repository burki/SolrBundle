<?php

namespace FS\SolrBundle\Doctrine\ClassnameResolver;

use Doctrine\ORM\Configuration as OrmConfiguration;

/**
 * Class collects document and entity aliases from ORM configuration
 */
class KnownNamespaceAliases
{
    /**
     * @var array Namespace-Alias => Full Entity/Documentnamespace
     */
    private $knownNamespaceAlias = array();

    /**
     * @var array
     */
    private $entityClassnames = array();

    /**
     * @param OrmConfiguration $configuration
     */
    public function addEntityNamespaces(OrmConfiguration $configuration)
    {
        $this->knownNamespaceAlias = array_merge($this->knownNamespaceAlias, $configuration->getEntityNamespaces());

        if ($configuration->getMetadataDriverImpl()) {
            $this->entityClassnames = array_merge($this->entityClassnames, $configuration->getMetadataDriverImpl()->getAllClassNames());
        }
    }

    /**
     * @param string $alias
     *
     * @return bool
     */
    public function isKnownNamespaceAlias($alias)
    {
        return isset($this->knownNamespaceAlias[$alias]);
    }

    /**
     * @param string $alias
     *
     * @return string
     */
    public function getFullyQualifiedNamespace($alias)
    {
        if ($this->isKnownNamespaceAlias($alias)) {
            return $this->knownNamespaceAlias[$alias];
        }

        return '';
    }

    /**
     * @return array
     */
    public function getAllNamespaceAliases()
    {
        return $this->knownNamespaceAlias;
    }

    /**
     * @return array
     */
    public function getEntityClassnames()
    {
        return $this->entityClassnames;
    }
}
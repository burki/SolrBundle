<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @Solr\Document(repository="FS\SolrBundle\Tests\Fixtures\InvalidEntityRepository")
 */
class EntityWithInvalidRepository
{
    /**
     * @var int
     *
     * @Solr\Id
     */
    private $id;
}

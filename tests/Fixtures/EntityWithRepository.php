<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as Solr;

/**
 */
#[Solr\Document(repository: "FS\SolrBundle\Tests\Fixtures\ValidEntityRepository")]
class EntityWithRepository
{
    /**
     * @var int
     */
    #[Solr\Id]
    private $id;
}

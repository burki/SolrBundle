<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as SolrAttribute;
use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @Solr\Document(repository="FS\SolrBundle\Tests\Fixtures\ValidEntityRepository")
 */
#[SolrAttribute\Document(repository: "FS\SolrBundle\Tests\Fixtures\ValidEntityRepository")]
class EntityWithRepository
{
    /**
     * @Solr\Id()
     *
     * @var int
     */
    #[SolrAttribute\Id]
    private $id;
}

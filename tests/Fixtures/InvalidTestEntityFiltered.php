<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as Solr;

/**
 */
#[Solr\Document]
#[Solr\SynchronizationFilter(callback:"shouldBeIndex")]
class InvalidTestEntityFiltered
{
    /**
     * @var int
     */
    #[Solr\Id]
    private $id;
}

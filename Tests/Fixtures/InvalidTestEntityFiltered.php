<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as SolrAttribute;
use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @Solr\Document
 * @Solr\SynchronizationFilter(callback="shouldBeIndex")
 */
#[SolrAttribute\Document]
#[SolrAttribute\SynchronizationFilter(callback:"shouldBeIndex")]
class InvalidTestEntityFiltered
{
    /**
     * @Solr\Id
     *
     * @var int
     */
    #[SolrAttribute\Id]
    private $id;
}

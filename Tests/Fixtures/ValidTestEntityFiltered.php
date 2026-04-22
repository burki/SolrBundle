<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as SolrAttribute;
use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @Solr\Document(index="index0")
 * @Solr\SynchronizationFilter(callback="shouldBeIndex")
 */
#[SolrAttribute\Document(index:"index0")]
#[SolrAttribute\SynchronizationFilter(callback:"shouldBeIndex")]
class ValidTestEntityFiltered
{
    /**
     * @Solr\Id()
     *
     * @var int
     */
    #[SolrAttribute\Id]
    private $id;

    private $shouldBeIndexedWasCalled = false;

    public $shouldIndex = false;

    public function shouldBeIndex()
    {
        $this->shouldBeIndexedWasCalled = true;

        return $this->shouldIndex;
    }

    public function getShouldBeIndexedWasCalled()
    {
        return $this->shouldBeIndexedWasCalled;
    }
}

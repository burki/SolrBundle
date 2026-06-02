<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as Solr;

/**
 */
#[Solr\Document(index:"index0")]
#[Solr\SynchronizationFilter(callback:"shouldBeIndex")]
class ValidTestEntityFiltered
{
    /**
     * @var int
     */
    #[Solr\Id]
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

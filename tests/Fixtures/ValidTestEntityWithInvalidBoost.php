<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as Solr;

/**
 */
#[Solr\Document(boost:"aaaa")]
class ValidTestEntityWithInvalidBoost
{
    /**
     */
    private $id;
}

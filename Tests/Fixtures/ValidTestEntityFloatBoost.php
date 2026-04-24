<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as Solr;

/**
 */
#[Solr\Document(boost:"1.4")]
class ValidTestEntityFloatBoost
{
    /**
     */
    private $id;
}

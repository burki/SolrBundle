<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as SolrAttribute;
use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @Solr\Document(boost="aaaa")
 */
#[SolrAttribute\Document(boost:"aaaa")]
class ValidTestEntityWithInvalidBoost
{

    /**
     * @Solr\Id
     */
    private $id;
}

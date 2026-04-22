<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as SolrAttribute;
use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @Solr\Document(boost="1.4")
 */
#[SolrAttribute\Document(boost:"1.4")]
class ValidTestEntityFloatBoost
{
    /**
     * @Solr\Id
     */
    private $id;

}

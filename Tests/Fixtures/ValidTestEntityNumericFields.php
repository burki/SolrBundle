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
class ValidTestEntityNumericFields
{

    /**
     * @Solr\Field(type="integer")
     */
    #[SolrAttribute\Field(type:"integer")]
    private $integer;

    /**
     *
     * @Solr\Field(type="double")
     */
    #[SolrAttribute\Field(type:"double")]
    private $double;

    /**
     *
     * @Solr\Field(type="float")
     */
    #[SolrAttribute\Field(type:"float")]
    private $float;

    /**
     *
     * @Solr\Field(type="long")
     */
    #[SolrAttribute\Field(type:"long")]
    private $long;
}

<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @Solr\Document
 * @Solr\SynchronizationFilter(callback="shouldBeIndex")
 */
#[Solr\Document]
#[Solr\SynchronizationFilter(callback:"shouldBeIndex")]
class ValidTestEntityNumericFields
{

    /**
     * @Solr\Field(type="integer")
     */
    #[Solr\Field(type:"integer")]
    private $integer;

    /**
     *
     * @Solr\Field(type="double")
     */
    #[Solr\Field(type:"double")]
    private $double;

    /**
     *
     * @Solr\Field(type="float")
     */
    #[Solr\Field(type:"float")]
    private $float;

    /**
     *
     * @Solr\Field(type="long")
     */
    #[Solr\Field(type:"long")]
    private $long;
}

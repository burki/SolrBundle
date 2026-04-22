<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as SolrAttribute;
use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @Solr\Document
 */
#[SolrAttribute\Document]
class ValidTestEntityNoTypes
{

    /**
     * @Solr\Id
     */
    #[SolrAttribute\Id]
    private $id;

    /**
     *
     * @Solr\Field
     */
    #[SolrAttribute\Field]
    private $title;

}


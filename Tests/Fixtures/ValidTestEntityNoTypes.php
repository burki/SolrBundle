<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as Solr;

/**
 */
#[Solr\Document]
class ValidTestEntityNoTypes
{
    /**
     */
    #[Solr\Id]
    private $id;

    /**
     */
    #[Solr\Field]
    private $title;
}

<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @Solr\Document
 */
#[Solr\Document]
class ValidTestEntityNoTypes
{

    /**
     * @Solr\Id
     */
    #[Solr\Id]
    private $id;

    /**
     *
     * @Solr\Field
     */
    #[Solr\Field]
    private $title;

}


<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @Solr\Document(index="my_core")
 */
#[Solr\Document(index:"my_core")]
class ValidTestEntityIndexProperty
{

    /**
     * @Solr\Id
     */
    #[Solr\Id]
    private $id;

    /**
     * @Solr\Field
     */
    #[Solr\Field]
    private $title;
}

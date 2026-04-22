<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as SolrAttribute;
use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @Solr\Document(indexHandler="indexHandler")
 */
#[SolrAttribute\Document(indexHandler:"indexHandler")]
class ValidTestEntityIndexHandler
{

    /**
     * @Solr\Id
     */
    #[SolrAttribute\Id]
    private $id;

    /**
     * @Solr\Field
     */
    #[SolrAttribute\Field]
    private $title;

    public function indexHandler()
    {
        return 'my_core';
    }
}

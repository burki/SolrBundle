<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as SolrAttribute;
use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @Solr\Document()
 */
#[SolrAttribute\Document]
class EntityWithCustomId
{
    /**
     * @Solr\Id(generateId = true)
     */
    #[SolrAttribute\Id(generateId: true)]
    private $id;

    /**
     * @Solr\Field
     *
     * @var string
     */
    #[SolrAttribute\Field()]
    private $title;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}

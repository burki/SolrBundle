<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as Solr;

/**
 */
#[Solr\Document]
class EntityWithCustomId
{
    /**
     */
    #[Solr\Id(generateId: true)]
    private $id;

    /**
     * @var string
     */
    #[Solr\Field()]
    private $title;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}

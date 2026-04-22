<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as SolrAttribute;
use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @Solr\Document(index="core1")
 */
#[SolrAttribute\Document(index: "core1")]
class EntityCore1
{
    /**
     * @Solr\Id
     */
    #[SolrAttribute\Id]
    private $id;

    /**
     * @Solr\Field(type="text")
     *
     * @var string
     */
    #[SolrAttribute\Field(type:"text")]
    private $text;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }


}
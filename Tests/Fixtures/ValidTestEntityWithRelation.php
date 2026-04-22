<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as SolrAttribute;
use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @Solr\Document(boost="1")
 */
#[SolrAttribute\Document(boost: 1)]
class ValidTestEntityWithRelation
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
    #[SolrAttribute\Field(type: "text")]
    private $text;

    /**
     * @Solr\Field()
     *
     * @var string
     */
    #[SolrAttribute\Field()]
    private $title;

    /**
     * @Solr\Field(type="date")
     *
     * @var \DateTime
     */
    #[SolrAttribute\Field(type: "date")]
    private $created_at;

    /**
     * @Solr\Field(type="my_costom_fieldtype")
     *
     * @var string
     */
    #[SolrAttribute\Field(type: "my_costom_fieldtype")]
    private $costomField;

    /**
     * @var object
     *
     * @Solr\Field(type="strings", getter="getTitle")
     */
    #[SolrAttribute\Field(type: "strings", getter: "getTitle")]
    private $relation;

    /**
     * @var object
     *
     * @Solr\Field(type="strings")
     */
    #[SolrAttribute\Field(type: "strings")]
    private $posts;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $costomField
     */
    public function setCostomField($costomField)
    {
        $this->costomField = $costomField;
    }

    /**
     * @return string
     */
    public function getCostomField()
    {
        return $this->costomField;
    }

    /**
     * @return object
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * @param object $relation
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;
    }

    /**
     * @return object
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param object $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param \DateTime $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }
}

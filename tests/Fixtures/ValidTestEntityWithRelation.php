<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Attribute as Solr;

/**
 */
#[Solr\Document(boost: 1)]
class ValidTestEntityWithRelation
{
    /**
     */
    #[Solr\Id]
    private $id;

    /**
     * @var string
     */
    #[Solr\Field(type: "text")]
    private $text;

    /**
     * @var string
     */
    #[Solr\Field()]
    private $title;

    /**
     * @var \DateTime
     */
    #[Solr\Field(type: "date")]
    private $created_at;

    /**
     * @var string
     */
    #[Solr\Field(type: "my_costom_fieldtype")]
    private $costomField;

    /**
     * @var object
     */
    #[Solr\Field(type: "strings", getter: "getTitle")]
    private $relation;

    /**
     * @var object
     */
    #[Solr\Field(type: "strings")]
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

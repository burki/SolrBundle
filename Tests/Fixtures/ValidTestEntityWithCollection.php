<?php

namespace FS\SolrBundle\Tests\Fixtures;

use Doctrine\Common\Collections\ArrayCollection;
use FS\SolrBundle\Attribute as SolrAttribute;
use FS\SolrBundle\Doctrine\Annotation as Solr;
use FS\SolrBundle\Tests\Doctrine\Mapper\date;
use FS\SolrBundle\Tests\Doctrine\Mapper\text;
use FS\SolrBundle\Tests\Doctrine\Mapper\the;

/**
 * @Solr\Document(boost="1")
 */
#[SolrAttribute\Document(boost: 1)]
class ValidTestEntityWithCollection
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
     * @var ArrayCollection
     *
     * @Solr\Field(type="strings", getter="getTitle")
     */
    #[SolrAttribute\Field(type: "strings", getter: "getTitle")]
    private $collection;

    /**
     * @var ArrayCollection
     *
     * @Solr\Field(type="strings")
     */
    #[SolrAttribute\Field(type: "strings")]
    private $collectionNoGetter;

    /**
     * @Solr\Field(type="my_costom_fieldtype")
     *
     * @var string
     */
    #[SolrAttribute\Field(type: "my_costom_fieldtype")]
    private $costomField;

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
     * @return ArrayCollection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param ArrayCollection $collection
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;
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

    /**
     * @return ArrayCollection
     */
    public function getCollectionNoGetter()
    {
        return $this->collectionNoGetter;
    }

    /**
     * @param ArrayCollection $collectionNoGetter
     */
    public function setCollectionNoGetter(ArrayCollection $collectionNoGetter)
    {
        $this->collectionNoGetter = $collectionNoGetter;
    }
}

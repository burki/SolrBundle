<?php

namespace FS\SolrBundle\Tests\Doctrine\Annotation;

use FS\SolrBundle\Doctrine\Annotation as Solr;

class ChildEntity extends BaseEntity
{
    /**
     * @Solr\Field(type="integer")
     */
    protected $baseField1;

    /**
     * @Solr\Field(type="integer")
     */
    protected $childField1;
}

class ChildEntity2 extends ChildEntity
{
    /**
     * @Solr\Field(type="integer")
     */
    private $childField2;
}

class EntityWithObject
{
    /**
     * @Solr\Field(type="datetime", getter="format('d.m.Y')")
     */
    private $object;
}

/**
 * @Solr\Nested()
 */
class NestedObject {}

/** @Solr\Document() */
class EntityMissingNameProperty {

    /** @Solr\Field(type="string") */
    public function getPropertyValue2()
    {
        return 1234;
    }
}
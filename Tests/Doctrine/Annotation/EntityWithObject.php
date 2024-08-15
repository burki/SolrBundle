<?php

namespace FS\SolrBundle\Tests\Doctrine\Annotation;

use FS\SolrBundle\Doctrine\Annotation as Solr;

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
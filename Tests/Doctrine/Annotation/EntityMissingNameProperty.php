<?php

namespace FS\SolrBundle\Tests\Doctrine\Annotation;

use FS\SolrBundle\Doctrine\Annotation as Solr;

/** @Solr\Document() */
class EntityMissingNameProperty {

    /** @Solr\Field(type="string") */
    public function getPropertyValue2()
    {
        return 1234;
    }
}
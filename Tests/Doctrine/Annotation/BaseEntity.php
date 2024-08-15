<?php

namespace FS\SolrBundle\Tests\Doctrine\Annotation;

use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 *
 * @Solr\Document
 */
abstract class BaseEntity
{
    /**
     * @var mixed
     */
    protected $baseField1;

    /**
     *
     * @Solr\Field(type="integer")
     */
    protected $baseField2;
}

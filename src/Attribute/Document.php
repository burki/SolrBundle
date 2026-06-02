<?php

namespace FS\SolrBundle\Attribute;

use Attribute;

/**
 * Defines a solr-document
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Document
{
    public function __construct(
        public $repository = '',
        public $boost = 0,
        public $index = null,
        public $indexHandler = null)
    {
    }

    /**
     * @return number
     */
    public function getBoost()
    {
        return $this->boost;
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }
}

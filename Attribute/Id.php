<?php

namespace FS\SolrBundle\Attribute;

use Attribute;

/**
 * @Attribute
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Id
{
    public $value; // currently used in AttributeReader, should be reworked

    public function __construct(
        public $name = '',
        public $generateId = false)
    {
    }
}

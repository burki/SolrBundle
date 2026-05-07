<?php

namespace FS\SolrBundle\Attribute;

use Attribute;

/**
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class SynchronizationFilter
{
    public function __construct(
        public $callback = '',
    ) {
    }
}
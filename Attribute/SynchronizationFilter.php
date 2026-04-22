<?php

namespace FS\SolrBundle\Attribute;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class SynchronizationFilter extends Annotation
{
    public $callback = '';
}
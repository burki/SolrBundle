<?php

namespace FS\SolrBundle\Tests\Fixtures;

use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @Solr\Document
 */
#[Solr\Document]
class ValidTestEntityNoBoost
{
}

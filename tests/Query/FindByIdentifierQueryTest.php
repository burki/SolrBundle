<?php

namespace FS\SolrBundle\Tests\Query;

use FS\SolrBundle\Query\Exception\QueryException;
use FS\SolrBundle\Query\FindByIdentifierQuery;
use Solarium\QueryType\Update\Query\Document;

/**
 * @group query
 */
class FindByIdentifierQueryTest extends \PHPUnit\Framework\TestCase
{
    public function testGetQuery_SearchInAllFields()
    {
        $document = new Document();
        $document->setKey('id', 'validtestentity_1');

        $query = new FindByIdentifierQuery();
        $query->setDocumentKey('validtestentity_1');
        $query->setDocument($document);

        $this->assertEquals('*:*', $query->getQuery());
        $this->assertEquals('id:validtestentity_1', $query->getFilterQuery('id')->getQuery());
    }

    /**
     * @test
     */
    public function testGetQuery_IdMissing()
    {
        $this->expectException(QueryException::class);
        $this->expectExceptionMessage('id should not be null');
        $query = new FindByIdentifierQuery();
        $query->setDocument(new Document());

        $query->getQuery();
    }
}

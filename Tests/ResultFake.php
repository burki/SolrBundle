<?php

namespace FS\SolrBundle\Tests;

class ResultFake extends \Solarium\QueryType\Select\Result\Result
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->data);
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function getNumFound(): ?int
    {
        return $this->count();
    }
}
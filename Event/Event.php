<?php
namespace FS\SolrBundle\Event;

use FS\SolrBundle\Doctrine\Mapper\MetaInformationInterface;
use Solarium\Client;
use Symfony\Contracts\EventDispatcher\Event as BaseEvent;

class Event extends BaseEvent
{
    /**
     * @var Client|null
     */
    private $client = null;

    /**
     * @var MetaInformationInterface|null
     */
    private $metainformation = null;

    /**
     * something like 'update-solr-document'
     *
     * @var string
     */
    private $solrAction = '';

    /**
     * @var Event|null
     */
    private $sourceEvent;

    /**
     * @param Client|null                   $client
     * @param MetaInformationInterface|null $metainformation
     * @param string                        $solrAction
     * @param Event|null                    $sourceEvent
     */
    public function __construct(
        ?Client $client = null,
        ?MetaInformationInterface $metainformation = null,
        $solrAction = '',
        ?Event $sourceEvent = null
    )
    {
        $this->client = $client;
        $this->metainformation = $metainformation;
        $this->solrAction = $solrAction;
        $this->sourceEvent = $sourceEvent;
    }

    /**
     * @return MetaInformationInterface
     */
    public function getMetaInformation()
    {
        return $this->metainformation;
    }

    /**
     * @return string
     */
    public function getSolrAction()
    {
        return $this->solrAction;
    }

    /**
     * @return Event
     */
    public function getSourceEvent()
    {
        return $this->sourceEvent;
    }

    /**
     * @return bool
     */
    public function hasSourceEvent()
    {
        return $this->sourceEvent !== null;
    }
}

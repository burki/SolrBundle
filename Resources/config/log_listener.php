<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();
    $parameters->set('solr.log_listener.insert.class', \FS\SolrBundle\Event\Listener\InsertLogListener::class);
    $parameters->set('solr.log_listener.update.class', \FS\SolrBundle\Event\Listener\UpdateLogListener::class);
    $parameters->set('solr.log_listener.delete.class', \FS\SolrBundle\Event\Listener\DeleteLogListener::class);
    $parameters->set('solr.log_listener.error.class', \FS\SolrBundle\Event\Listener\ErrorLogListener::class);
    $parameters->set('solr.log_listener.clearindex.class', \FS\SolrBundle\Event\Listener\ClearIndexLogListener::class);

    $services->set('solr.log_listener.insert', '%solr.log_listener.insert.class%')
        ->args([service('logger')])
        ->tag('kernel.event_listener', ['event' => 'solr.pre_insert', 'method' => 'onSolrInsert'])
        ->tag('kernel.event_listener', ['event' => 'solr.post_insert', 'method' => 'onSolrInsert'])
        ->tag('monolog.logger', ['channel' => 'solr']);

    $services->set('solr.log_listener.update', '%solr.log_listener.update.class%')
        ->args([service('logger')])
        ->tag('kernel.event_listener', ['event' => 'solr.pre_update', 'method' => 'onSolrUpdate'])
        ->tag('kernel.event_listener', ['event' => 'solr.post_update', 'method' => 'onSolrUpdate'])
        ->tag('monolog.logger', ['channel' => 'solr']);

    $services->set('solr.log_listener.delete', '%solr.log_listener.delete.class%')
        ->args([service('logger')])
        ->tag('kernel.event_listener', ['event' => 'solr.pre_delete', 'method' => 'onSolrDelete'])
        ->tag('kernel.event_listener', ['event' => 'solr.post_delete', 'method' => 'onSolrDelete'])
        ->tag('monolog.logger', ['channel' => 'solr']);

    $services->set('solr.log_listener.clearindex', '%solr.log_listener.clearindex.class%')
        ->args([service('logger')])
        ->tag('kernel.event_listener', ['event' => 'solr.pre_clear_index', 'method' => 'onClearIndex'])
        ->tag('kernel.event_listener', ['event' => 'solr.post_clear_index', 'method' => 'onClearIndex'])
        ->tag('monolog.logger', ['channel' => 'solr']);

    $services->set('solr.log_listener.error', '%solr.log_listener.error.class%')
        ->args([service('logger')])
        ->tag('kernel.event_listener', ['event' => 'solr.error', 'method' => 'onSolrError'])
        ->tag('monolog.logger', ['channel' => 'solr']);
};

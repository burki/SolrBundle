<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();

    $services->set('solr.document.orm.subscriber', \FS\SolrBundle\Doctrine\ORM\Listener\EntityIndexerSubscriber::class)
        ->private()
        ->args([
            service('solr.client'),
            service('solr.meta.information.factory'),
            service('logger'),
        ])
        ->tag('monolog.logger', ['channel' => 'solr']);

    $services->set('solr.document.odm.subscriber', \FS\SolrBundle\Doctrine\ODM\Listener\DocumentIndexerSubscriber::class)
        ->private()
        ->args([
            service('solr.client'),
            service('solr.meta.information.factory'),
            service('logger'),
        ])
        ->tag('monolog.logger', ['channel' => 'solr']);
};

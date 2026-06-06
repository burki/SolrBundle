<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();

    $services->set('solr.document.orm.listener', \FS\SolrBundle\Doctrine\ORM\Listener\EntityIndexer::class)
        ->private()
        ->args([
            service('solr.client'),
            service('solr.meta.information.factory'),
            service('logger'),
        ])
        ->tag('monolog.logger', ['channel' => 'solr']);
};

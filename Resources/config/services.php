<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();
    $parameters->set('solr.class', \FS\SolrBundle\Solr::class);

    $services->set('solr.client', '%solr.class%')
        ->public()
        ->args([
            service('solr.client.adapter'),
            service('event_dispatcher'),
            service('solr.meta.information.factory'),
            service('solr.doctrine.entity_mapper'),
        ]);

    $services->set('solr.client.adapter', \Solarium\Client::class)
        ->factory([service('solr.client.adapter.builder'), 'build']);

    $services->set('solr.client.adapter.builder', \FS\SolrBundle\Client\Solarium\SolariumClientBuilder::class)
        ->args([
            [],
            service('event_dispatcher'),
        ]);

    $services->set('solr.client.plugin.logger', \FS\SolrBundle\Client\Solarium\Plugin\LoggerPlugin::class)
        ->args([service('solr.client.logger.debug')])
        ->tag('solarium.client.plugin', ['plugin-name' => 'SolrBundleLogger']);

    $services->set('solr.client.logger.debug', \FS\SolrBundle\Logging\DebugLogger::class);

    $services->set('solr.data_collector', \FS\SolrBundle\DataCollector\RequestCollector::class)
        ->private()
        ->args([service('solr.client.logger.debug')])
        ->tag('data_collector', ['template' => '@FSSolr/Profiler/solr.html.twig', 'id' => 'solr']);

    $services->set('solr.debug.client_debugger', \FS\SolrBundle\Client\Solarium\Plugin\RequestDebugger::class)
        ->args([service('logger')])
        ->tag('monolog.logger', ['channel' => 'solr'])
        ->tag('kernel.event_listener', ['event' => 'solarium.core.preExecuteRequest', 'method' => 'preExecuteRequest']);

    $services->set('solr.meta.information.factory', \FS\SolrBundle\Doctrine\Mapper\MetaInformationFactory::class)
        ->public()
        ->args([service('solr.doctrine.annotation.annotation_reader')]);

    $services->set('solr.doctrine.classnameresolver.known_entity_namespaces', \FS\SolrBundle\Doctrine\ClassnameResolver\KnownNamespaceAliases::class)
        ->public();

    $services->set('solr.doctrine.classnameresolver', \FS\SolrBundle\Doctrine\ClassnameResolver\ClassnameResolver::class)
        ->private()
        ->args([service('solr.doctrine.classnameresolver.known_entity_namespaces')]);

    $services->set('solr.doctrine.entity_mapper', \FS\SolrBundle\Doctrine\Mapper\EntityMapper::class)
        ->args([
            service('solr.doctrine.hydration.doctrine_hydrator'),
            service('solr.doctrine.hydration.index_hydrator'),
            service('solr.meta.information.factory'),
        ]);

    $services->set('solr.doctrine.hydration.doctrine_hydrator', \FS\SolrBundle\Doctrine\Hydration\DoctrineHydrator::class)
        ->args([service('solr.doctrine.hydration.doctrine_value_hydrator')])
        ->factory([service('fs_solr.doctrine_hydration.doctrine_hydrator_factory'), 'factory']);

    $services->set('fs_solr.doctrine_hydration.doctrine_hydrator_factory', \FS\SolrBundle\Doctrine\Hydration\DoctrineHydratorFactory::class)
        ->args([service('service_container')]);

    $services->set('solr.doctrine.hydration.value_hydrator', \FS\SolrBundle\Doctrine\Hydration\ValueHydrator::class);

    $services->set('solr.doctrine.hydration.no_database_value_hydrator', \FS\SolrBundle\Doctrine\Hydration\NoDatabaseValueHydrator::class);

    $services->set('solr.doctrine.hydration.doctrine_value_hydrator', \FS\SolrBundle\Doctrine\Hydration\DoctrineValueHydrator::class)
        ->public();

    $services->set('solr.doctrine.hydration.index_hydrator', \FS\SolrBundle\Doctrine\Hydration\IndexHydrator::class)
        ->args([service('solr.doctrine.hydration.no_database_value_hydrator')]);

    $services->set('solr.doctrine.annotation.annotation_reader', \FS\SolrBundle\Doctrine\Annotation\AnnotationReader::class)
        ->args([service('annotation_reader')]);

    $services->set('solr.command.clear_index_command', \FS\SolrBundle\Command\ClearIndexCommand::class)
        ->args([service('solr.client')])
        ->tag('console.command');

    $services->set('solr.command.show_schema_command', \FS\SolrBundle\Command\ShowSchemaCommand::class)
        ->args([
            service('solr.doctrine.classnameresolver.known_entity_namespaces'),
            service('solr.meta.information.factory'),
        ])
        ->tag('console.command');

    $services->set('solr.command.synchronize_index_command', \FS\SolrBundle\Command\SynchronizeIndexCommand::class)
        ->args([
            service('solr.client'),
            service('solr.doctrine.classnameresolver.known_entity_namespaces'),
            service('solr.meta.information.factory'),
            service('service_container'),
        ])
        ->tag('console.command');
};

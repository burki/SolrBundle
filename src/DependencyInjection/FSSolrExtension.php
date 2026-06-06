<?php

namespace FS\SolrBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class FSSolrExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');
        $loader->load('event_listener.php');
        $loader->load('log_listener.php');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->setupClients($config, $container);

        if (!$container->hasParameter('solr.auto_index')) {
            $container->setParameter('solr.auto_index', $config['auto_index']);
        }

        $this->setupDoctrineListener($config, $container);
        $this->setupDoctrineConfiguration($config, $container);

    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function setupClients(array $config, ContainerBuilder $container)
    {
        $endpoints = $config['endpoints'];

        $builderDefinition = $container->getDefinition('solr.client.adapter.builder');
        $builderDefinition->replaceArgument(0, $endpoints);
        $builderDefinition->addMethodCall('addPlugin', array('request_debugger', new Reference('solr.debug.client_debugger')));
    }

    /**
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function setupDoctrineConfiguration(array $config, ContainerBuilder $container)
    {
        if ($this->isOrmConfigured($container)) {
            $entityManagers = $container->getParameter('doctrine.entity_managers');

            $entityManagersNames = array_keys($entityManagers);
            foreach ($entityManagersNames as $entityManager) {
                $container->getDefinition('solr.doctrine.classnameresolver.known_entity_namespaces')->addMethodCall(
                    'addEntityNamespaces',
                    array(new Reference(sprintf('doctrine.orm.%s_configuration', $entityManager)))
                );
            }
        }

        $container->getDefinition('solr.meta.information.factory')->addMethodCall(
            'setClassnameResolver',
            array(new Reference('solr.doctrine.classnameresolver'))
        );
    }

    /**
     * listener-methods expecting different types of events
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function setupDoctrineListener(array $config, ContainerBuilder $container)
    {
        $autoIndexing = $container->getParameter('solr.auto_index');

        if ($autoIndexing == false) {
            return;
        }

        if ($this->isOrmConfigured($container)) {
            $container->getDefinition('solr.document.orm.listener')
                ->addTag('doctrine.event_listener', ['event' => 'postUpdate'])
                ->addTag('doctrine.event_listener', ['event' => 'postPersist'])
                ->addTag('doctrine.event_listener', ['event' => 'preRemove'])
                ->addTag('doctrine.event_listener', ['event' => 'postFlush'])
                ;
        }
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return boolean
     */
    private function isOrmConfigured(ContainerBuilder $container)
    {
        return $container->hasParameter('doctrine.entity_managers');
    }
}

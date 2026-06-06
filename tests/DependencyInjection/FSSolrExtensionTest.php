<?php

namespace FS\SolrBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use FS\SolrBundle\DependencyInjection\FSSolrExtension;
use Symfony\Component\DependencyInjection\Reference;

/**
 *
 * @group extension
 */
class FSSolrExtensionTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var ContainerBuilder
     */
    private $container = null;

    public function setUp(): void
    {
        $this->container = new ContainerBuilder();
    }

    private function enableOrmConfig()
    {
        $this->container->setParameter('doctrine.entity_managers', array('default' => 'orm.default.mananger'));
    }

    private function commonConfig()
    {
        return array(array(

            'endpoints' => array(
                'default' => array(
                    'host' => '192.168.178.24',
                    'port' => 8983,
                    'path' => '/solr/',
                )
            )
        ));
    }

    public function testDoctrineORMSetup()
    {
        $this->enableOrmConfig();
        $config = $this->commonConfig();

        $extension = new FSSolrExtension();
        $extension->load($config, $this->container);

        $this->assertTrue($this->container->has('solr.document.orm.listener'), 'orm listener');

        $this->assertDefinitionHasTag('solr.document.orm.listener', 'doctrine.event_listener');

        $this->assertClassnameResolverHasOrmDefaultConfiguration();
    }

    /**
     * @test
     */
    public function solrListensToOrmEvents()
    {
        $config = $this->commonConfig();
        $this->enableOrmConfig();

        $extension = new FSSolrExtension();
        $extension->load($config, $this->container);

        $this->assertTrue($this->container->has('solr.document.orm.listener'), 'orm listener');
        $this->assertDefinitionHasTag('solr.document.orm.listener', 'doctrine.event_listener');
    }

    private function assertClassnameResolverHasOrmDefaultConfiguration()
    {
        $doctrineConfiguration = $this->getReferenzIdOfCalledMethod();

        $this->assertEquals('doctrine.orm.default_configuration', $doctrineConfiguration);
    }

    private function assertClassnameResolverHasOdmDefaultConfiguration()
    {
        $doctrineConfiguration = $this->getReferenzIdOfCalledMethod();

        $this->assertEquals('doctrine_mongodb.odm.default_configuration', $doctrineConfiguration);
    }

    /**
     * @return Reference
     */
    private function getReferenzIdOfCalledMethod()
    {
        $methodCalls = $this->container->getDefinition('solr.doctrine.classnameresolver.known_entity_namespaces')->getMethodCalls();

        $firstMethodCall = $methodCalls[0];
        $references = $firstMethodCall[1];
        $reference = $references[0];

        return $reference;
    }

    private function assertDefinitionHasTag($definition, $tag)
    {
        $tags = $this->container->getDefinition($definition)->getTags();

        $this->assertTrue(
            $this->container->getDefinition($definition)->hasTag($tag),
            sprintf('%s with %s tag, has %s', $definition, $tag, print_r($tags, true))
        );
    }
}

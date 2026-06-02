<?php

namespace FS\SolrBundle\Tests\Doctrine\ClassnameResolver;

use Doctrine\ORM\Configuration as OrmConfiguration;
use FS\SolrBundle\Doctrine\ClassnameResolver\KnownNamespaceAliases;
use PHPUnit\Framework\TestCase;

class KnownNamespaceAliasesTest extends TestCase
{
    /**
     * @test
     */
    public function addAliasFromMultipleOrmConfigurations()
    {
        $config1 = $this->createMock(OrmConfiguration::class);
        $config1->expects($this->once())
            ->method('getEntityNamespaces')
            ->willReturn(array('AcmeDemoBundle'));

        $config2 = $this->createMock(OrmConfiguration::class);
        $config2->expects($this->once())
            ->method('getEntityNamespaces')
            ->willReturn(array('AcmeBlogBundle'));

        $knownAliases = new KnownNamespaceAliases();
        $knownAliases->addEntityNamespaces($config1);
        $knownAliases->addEntityNamespaces($config2);

        $namespaceAliases = $knownAliases->getAllNamespaceAliases();
        $this->assertTrue(in_array('AcmeDemoBundle', $namespaceAliases, true));
        $this->assertTrue(in_array('AcmeBlogBundle', $namespaceAliases, true));
    }

    /**
     * @test
     */
    public function knowAliasHasAValidNamespace()
    {
        $config1 = $this->createMock(OrmConfiguration::class);
        $config1->expects($this->once())
            ->method('getEntityNamespaces')
            ->will($this->returnValue(array('AcmeDemoBundle' => 'Acme\DemoBundle\Document')));

        $config2 = $this->createMock(OrmConfiguration::class);
        $config2->expects($this->once())
            ->method('getEntityNamespaces')
            ->will($this->returnValue(array('AcmeBlogBundle' => 'Acme\BlogBundle\Document')));

        $knownAliases = new KnownNamespaceAliases();
        $knownAliases->addEntityNamespaces($config1);
        $knownAliases->addEntityNamespaces($config2);

        $this->assertEquals('Acme\DemoBundle\Document', $knownAliases->getFullyQualifiedNamespace('AcmeDemoBundle'));
    }
}

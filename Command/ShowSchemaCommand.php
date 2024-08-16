<?php

namespace FS\SolrBundle\Command;

use FS\SolrBundle\Doctrine\Mapper\MetaInformationInterface;
use FS\SolrBundle\Doctrine\Mapper\SolrMappingException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowSchemaCommand extends Command
{
    protected $namespaces;
    protected $metaInformationFactory;

    public function __construct(\FS\SolrBundle\Doctrine\ClassnameResolver\KnownNamespaceAliases $namespaces,
                                \FS\SolrBundle\Doctrine\Mapper\MetaInformationFactory $metaInformationFactory)
    {
        $this->namespaces = $namespaces;
        $this->metaInformationFactory = $metaInformationFactory;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setName('solr:schema:show')
            ->setDescription('Show configured entities and their fields');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->namespaces->getEntityClassnames() as $classname) {
            try {
                $metaInformation = $this->metaInformationFactory->loadInformation($classname);

                if ($metaInformation->isNested()) {
                    continue;
                }
            } catch (SolrMappingException $e) {
                continue;
            }

            $nested = '';
            if ($metaInformation->isNested()) {
                $nested = '(nested)';
            }
            $output->writeln(sprintf('<comment>%s</comment> %s', $classname, $nested));
            $output->writeln(sprintf('Documentname: %s', $metaInformation->getDocumentName()));
            $output->writeln(sprintf('Document Boost: %s', $metaInformation->getBoost()?$metaInformation->getBoost(): '-'));

            $simpleFields = $this->getSimpleFields($metaInformation);

            $rows = [];
            foreach ($simpleFields as $documentField => $property) {
                if ($field = $metaInformation->getField($documentField)) {
                    $rows[] = [$property, $documentField, $field->boost];
                }
            }
            $this->renderTable($output, $rows);

            $nestedFields = $this->getNestedFields($metaInformation);
            if (count($nestedFields) == 0) {
                continue;
            }

            $output->writeln(sprintf('Fields <comment>(%s)</comment> with nested documents', count($nestedFields)));

            foreach ($nestedFields as $idField) {
                $propertyName = substr($idField, 0, strpos($idField, '.'));

                if ($nestedField = $metaInformation->getField($propertyName)) {
                    $output->writeln(sprintf('Field <comment>%s</comment> contains nested class <comment>%s</comment>', $propertyName, $nestedField->nestedClass));

                    $nestedDocument = $metaInformationFactory->loadInformation($nestedField->nestedClass);
                    $rows = [];
                    foreach ($nestedDocument->getFieldMapping() as $documentField => $property) {
                        $field = $nestedDocument->getField($documentField);

                        if ($field === null) {
                            continue;
                        }

                        $rows[] = [$property, $documentField, $field->boost];
                    }

                    $this->renderTable($output, $rows);
                }
            }

        }

        return 0;
    }

    /**
     * @param OutputInterface $output
     * @param array $rows
     */
    private function renderTable(OutputInterface $output, array $rows)
    {
        $table = new Table($output);
        $table->setHeaders(array('Property', 'Document Fieldname', 'Boost'));
        $table->setRows($rows);

        $table->render();
    }

    /**
     * @param MetaInformationInterface $metaInformation
     *
     * @return array
     */
    private function getSimpleFields(MetaInformationInterface $metaInformation)
    {
        $simpleFields = array_filter($metaInformation->getFieldMapping(), function ($field) {
            if (strpos($field, '.') === false) {
                return true;
            }

            return false;
        });

        return $simpleFields;
    }

    /**
     * @param MetaInformationInterface $metaInformation
     *
     * @return array
     */
    protected function getNestedFields(MetaInformationInterface $metaInformation)
    {
        $complexFields = array_filter($metaInformation->getFieldMapping(), function ($field) {
            if (strpos($field, '.id') !== false) {
                return true;
            }

            return false;
        });

        return $complexFields;
    }
}
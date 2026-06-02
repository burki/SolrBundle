<?php

namespace FS\SolrBundle\Command;

use FS\SolrBundle\SolrException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command clears the whole index
 */
class ClearIndexCommand extends Command
{
    protected $solr;

    public function __construct(\FS\SolrBundle\SolrInterface $solr)
    {
        $this->solr = $solr;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('solr:index:clear')
            ->setDescription('Clear the whole index');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->solr->clearIndex();
        } catch (SolrException $e) {
            $output->writeln(sprintf('A error occurs: %s', $e->getMessage()));

            return 1;
        }

        $output->writeln('<info>Index successful cleared.</info>');

        return 0;
    }
}

<?php

namespace Jwpage\Composerdoc;

use Jwpage\Composerdoc\DumpCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

/**
 */
class CheckCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('check')
            ->setDescription('Check a markdown file to see if it has the latest composerdoc.')
            ->addOption(
                'path',
                null,
                InputOption::VALUE_REQUIRED,
                'Path to README.md files - expects composer.* files to be in the same directory.',
                getcwd()
            )
            ->addOption(
                'dev',
                null,
                InputOption::VALUE_NONE,
                'Dump dev packages as well'
            )
            ->addOption(
                'sub',
                null,
                InputOption::VALUE_NONE,
                'Show subpackages'
            )
            ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arrayInput = array(
            '--path'  => dirname($input->getOption('path')),
            '--dev'   => $input->getOption('dev'),
            '--sub'   => $input->getOption('sub'),
        );
        $tester = new CommandTester(new DumpCommand());
        $tester->execute($arrayInput);
        $new = trim($tester->getDisplay());

        $contents = file_get_contents($input->getOption('path'));
        preg_match("#<!--- composerdoc --->.*?<!--- /composerdoc --->#s", $contents, $matches);

        
        if (isset($matches[0]) && $matches[0] == $new) {
            $output->writeln('<info>Your composerdoc is up to date.</info>');
            return 0;
        } else {
            $output->writeln('<error>Your composerdoc is NOT up to date.</error>');
            return 1;
        }
    }
}
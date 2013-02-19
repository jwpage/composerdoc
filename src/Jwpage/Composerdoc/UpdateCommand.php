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
class UpdateCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('update')
            ->setDescription('Update a README file containing composerdoc with the latest composerdoc')
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
        $inputArray = array(
            '--sub'  => $input->getOption('sub'),
            '--dev'  => $input->getOption('dev'),
            '--path' => dirname($input->getOption('path')),
        );
        $tester = new CommandTester(new DumpCommand());
        $tester->execute($inputArray);
        $new = $tester->getDisplay();

        $contents = file_get_contents($input->getOption('path'));
        $regex = "#<!--- composerdoc --->.*?<!--- /composerdoc --->#s";
        $newContents = preg_replace($regex, $new, $contents);
        if ($contents === $newContents) {
            $output->writeln("<error>Could not find composerdoc section. First insert it manually via the `dump` command.</error>");
            return 1;
        } else {
            file_put_contents($input->getOption('path'), $newContents);
            $output->writeln("<info>Composerdoc section updated successfully.</info>");
            return 0;
        }
    }
}
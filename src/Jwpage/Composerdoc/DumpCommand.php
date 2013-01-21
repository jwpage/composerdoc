<?php

namespace Jwpage\Composerdoc;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DumpCommand extends Command
{
    protected $lockArray;
    protected $primaryPackages;

    protected function configure()
    {
        $this
            ->setName('dump')
            ->setDescription('Dump the homepages of composer packages')
            ->addOption(
                'path',
                null,
                InputOption::VALUE_REQUIRED,
                'Path to composer.json/lock files',
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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $path = realpath($input->getOption('path'));
        $dev  = $input->getOption('dev');
        $sub  = $input->getOption('sub');

        if (!file_exists("$path/composer.json")) {
            throw new \RuntimeException("Could not find composer.json in $path");
        }
        if (!file_exists("$path/composer.lock")) {
            throw new \RuntimeException("Could not find composer.lock in $path");
        }
        $jsonArray = json_decode(file_get_contents($path.'/composer.json'), true);
        $lockArray = json_decode(file_get_contents($path.'/composer.lock'), true);

        $this->lockArray = $lockArray;

        if ($dev) {
            $output->writeln("Required Packages\n");
        }

        $this->dumpPackages($jsonArray['require'], $sub);

        if ($dev) {
            $output->writeln("\nDev Packages\n");
            $this->dumpPackages($jsonArray['require-dev'], $sub, true);
        }
    }

    protected function dumpPackages($packages, $showSubPackages, $isDev = false)
    {
        $packages = array_keys($packages);
        foreach ($packages as $package) {
            $package = $this->findPackage($package, $isDev);
            $this->writePackage($package);

            if ($showSubPackages) {
                $required = array_keys($package['require']);
                foreach ($required as $subPackage) {
                    $subPackage = $this->findPackage($subPackage, $isDev);
                    $this->writePackage($subPackage, 1);
                }
            }
        }
    }

    protected function writePackage($package, $indent = 0)
    {
        if (!$package) { // probably "php" requirement

            return;
        }

        $homepage = isset($package['homepage']) ? "<{$package['homepage']}>" : 'none';
        $indent = str_repeat("    ", $indent);
        $this->output->writeln("{$indent}* {$package['name']}: {$package['description']}: {$homepage}");
    }

    protected function findPackage($packageName, $isDev = false)
    {
        $flag = $isDev ? 'packages-dev' : 'packages';
        $packages = array_filter($this->lockArray[$flag], function($package) use ($packageName) {
            return $packageName == $package['name'];
        });

        return array_pop($packages);
    }
}

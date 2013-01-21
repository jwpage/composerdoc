<?php

namespace Jwpage\Test;

use Jwpage\Composerdoc\DumpCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DumpCommandTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $app = new Application();
        $app->add(new DumpCommand());

        $this->cmd = $app->find('dump');
        $this->tester = new CommandTester($this->cmd);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Could not find
     */
    public function testMissingFilesThrowsException()
    {
        $this->tester->execute(array(
            'command' => $this->cmd->getName(),
            '--path'  => __DIR__,
        ));
    }

    public function testDumpWithPath()
    {
        $this->tester->execute(array(
            'command' => $this->cmd->getName(),
            '--path' => MOCK_DIR,
        ));

        $this->assertContains('composer/composer', $this->tester->getDisplay());
        $this->assertNotContains('justinrainbow/json-schema', $this->tester->getDisplay());
        $this->assertNotContains('Dev Packages', $this->tester->getDisplay());  
    }

    public function testDumpWithDev()
    {
        $this->tester->execute(array(
            'command' => $this->cmd->getName(),
            '--path' => MOCK_DIR,
            '--dev' => true,
        ));
        $this->assertContains('Dev Packages', $this->tester->getDisplay());
    }

    public function testDumpWithSub()
    {
        $this->tester->execute(array(
            'command' => $this->cmd->getName(),
            '--path' => MOCK_DIR,
            '--sub' => true,
        ));

        $this->assertContains('justinrainbow/json-schema', $this->tester->getDisplay());
    }
}
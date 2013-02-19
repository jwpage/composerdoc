<?php

namespace Jwpage\Test;

use Jwpage\Composerdoc\CheckCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CheckCommandTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $app = new Application();
        $app->add(new CheckCommand());

        $this->cmd = $app->find('check');
        $this->tester = new CommandTester($this->cmd);
    }

    public function testCheck()
    {
        $code = $this->tester->execute(array(
            'command' => $this->cmd->getName(),
            '--path' => MOCK_DIR.'/README.md',
        ));
        $this->assertEquals(0, $code);
        $this->assertContains('Your composerdoc is up to date.', $this->tester->getDisplay());
    }
    
    public function testCheckOld()
    {
        $code = $this->tester->execute(array(
            'command' => $this->cmd->getName(),
            '--path' => MOCK_DIR.'/README-old.md',
        ));
        $this->assertEquals(1, $code);
        $this->assertContains('Your composerdoc is NOT up to date.', $this->tester->getDisplay());
    }
}

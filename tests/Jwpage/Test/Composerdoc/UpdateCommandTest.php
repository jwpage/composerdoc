<?php

namespace Jwpage\Test;

use Jwpage\Composerdoc\UpdateCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class UpdateCommandTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $app = new Application();
        $app->add(new UpdateCommand());

        $this->cmd = $app->find('update');
        $this->tester = new CommandTester($this->cmd);
    }

    public function testNoUpdate()
    {
        $code = $this->tester->execute(array(
            'command' => $this->cmd->getName(),
            '--path' => MOCK_DIR.'/composer.lock', // pointing to a non-markdown file
        ));
        $this->assertEquals(1, $code);
        $this->assertContains('Could not find composerdoc section.', $this->tester->getDisplay());
    }

    public function testUpdate()
    {
        $file = MOCK_DIR.'/README-old.md';
        $code = $this->tester->execute(array(
            'command' => $this->cmd->getName(),
            '--path' => $file,
        ));
        $this->assertEquals(0, $code);
        $this->assertContains('Composerdoc section updated successfully.', $this->tester->getDisplay());

        $contents = file_get_contents($file);
        $this->assertContains('composer/composer', $contents);

        // replace old contents
        file_put_contents($file, "<!--- composerdoc --->\n<!--- /composerdoc --->\n");
    }
}
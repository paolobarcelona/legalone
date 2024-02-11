<?php
declare(strict_types=1);

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportLogDataCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        self::bootKernel();

        $application = new Application(self::$kernel);

        $command = $application->find('log:import-data');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
        ]);

        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Saved records count:', $output);
        $this->assertStringContainsString('Errors:', $output);
    }
}
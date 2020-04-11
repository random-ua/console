<?php
namespace Console\Tests;

use PHPUnit\Framework\TestCase;
use Console\Runner;
use Console\Exception\CommandNotFoundException;

class CommandInstantiationTest extends TestCase
{

    public function testCommandInstantiation()
    {
        $this->expectOutputString("AppSimple:" . print_r([], true));

        $console = new Runner();
        // Inject arguments
        $console->setArguments([
            'test.php',
            'app:simple'
        ]);
        // Register handlers
        $console->registerAll([
            'app:simple' => new Commands\AppSimple()
        ]);
        // Run test
        $console->run();
    }

    public function testCommandInstantiationRegisterSingle()
    {
        $this->expectOutputString("AppSimple:" . print_r([], true));

        $console = new Runner();
        // Inject arguments
        $console->setArguments([
            'test.php',
            'app:simple'
        ]);
        // Register handlers
        $console->register('app:simple', new Commands\AppSimple());
        // Run test
        $console->run();
    }

    public function testCommandInstantiationWithLongArgument()
    {
        $this->expectOutputString("AppSimple:" . print_r([
            'long' => 'argument'
        ], true));

        $console = new Runner();
        // Inject arguments
        $console->setArguments([
            'test.php',
            'app:simple',
            '--long="argument"'
        ]);
        // Register handlers
        $console->registerAll([
            'app:simple' => new Commands\AppSimple()
        ]);
        // Run test
        $console->run();
    }

    public function testCommandInstantiationWithLongAndShortArgument()
    {
        $this->expectOutputString("AppSimple:" . print_r([
            'long' => 'argument',
            's' => 'arg'
        ], true));

        $console = new Runner();
        // Inject arguments
        $console->setArguments([
            'test.php',
            'app:simple',
            '--long="argument"',
            '-s',
            'arg'
        ]);
        // Register handlers
        $console->registerAll([
            'app:simple' => new Commands\AppSimple()
        ]);
        // Run test
        $console->run();
    }

    public function testCommandInstantiationWithStandaloneShortArgument()
    {
        $this->expectOutputString("AppSimple:" . print_r([
            's' => '',
            'a' => 'b'
        ], true));

        $console = new Runner();
        // Inject arguments
        $console->setArguments([
            'test.php',
            'app:simple',
            '-s',
            '-a',
            'b'
        ]);
        // Register handlers
        $console->registerAll([
            'app:simple' => new Commands\AppSimple()
        ]);
        // Run test
        $console->run();
    }

    public function testCommandInstantiationWithAliases()
    {
        // For this test, args get rewritten which impacts the order of the array
        $this->expectOutputString("AppSimple:" . print_r([
            'a' => 'b', // must be first
            'longform' => 'argument' // must be second
        ], true));

        $console = new Runner();
        // Inject arguments
        $console->setArguments([
            'test.php',
            'app:simple',
            '-l',
            'argument',
            '-a',
            'b'
        ]);
        // Register handlers
        $console->registerAll([
            'app:simple' => new Commands\AppSimple()
        ]);
        // Run test
        $console->run();
    }

    public function testCommandInstantiationWithInvalidCommand()
    {
        $this->expectException(CommandNotFoundException::class);

        $console = new Runner();
        // Inject arguments
        $console->setArguments([
            'test.php',
            'app:not-found'
        ]);
        // Register handlers
        $console->registerAll([
            'app:simple' => new Commands\AppSimple()
        ]);
        // Run test
        $console->run();
    }

    public function testCommandInstantiationWithNoCommand()
    {
        $console = new Runner();
        // Register handlers
        $console->registerAll([
            'app:simple' => new Commands\AppSimple()
        ]);
        // Inject arguments
        $console->setArguments([
            'test.php'
        ]);

        $this->expectOutputString($console->getHelp());

        // Run test
        $console->run();
    }

    public function testCommandInstantiationWithDescription()
    {
        $console = new Runner();
        // Register handlers
        $console->registerAll([
            'app:simple' => new Commands\AppSimple()
        ]);

        $this->expectOutputRegex('/app:simple - Simple command interface/');

        // Run test
        $console->run();
    }

}
<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Command\ConvertTempCommand;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ConvertTempTest extends KernelTestCase {
    public $commandTester;
    protected function setUp() : void
    { 
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:convert-temp');
        $this->commandTester = new CommandTester($command);
    }

    public function testCelsiusToFahrenheit(){
        // Zero test
        $this->commandTester->execute(array(
            'temperature' => 0,
            'input_unit' => "celsius",
            'output_unit' => "fahrenheit"
        ));

        $this->commandTester->assertCommandIsSuccessful();
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('32', $output);

        // Big positive test
        $this->commandTester->execute(array(
            'temperature' => 1000,
            'input_unit' => "celsius",
            'output_unit' => "fahrenheit"
        ));

        $this->commandTester->assertCommandIsSuccessful();
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('1832', $output);

        // Big negative test
        $this->commandTester->execute(array(
            'temperature' => -1000,
            'input_unit' => "celsius",
            'output_unit' => "fahrenheit"
        ));

        $this->commandTester->assertCommandIsSuccessful();
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('-1768', $output);
    }

    public function testFahrenheitToCelsius(){
        // Zero test
        $this->commandTester->execute(array(
            'temperature' => 0,
            'input_unit' => "fahrenheit",
            'output_unit' => "celsius"
        ));

        $this->commandTester->assertCommandIsSuccessful();
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('-17.78', $output);

        // Big positive test
        $this->commandTester->execute(array(
            'temperature' => 1000,
            'input_unit' => "fahrenheit",
            'output_unit' => "celsius"
        ));

        $this->commandTester->assertCommandIsSuccessful();
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('537.78', $output);

        // Big negative test
        $this->commandTester->execute(array(
            'temperature' => -1000,
            'input_unit' => "fahrenheit",
            'output_unit' => "celsius"
        ));

        $this->commandTester->assertCommandIsSuccessful();
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('-573.33', $output);
    }

    public function testInvalid(){
        // Not a number
        $this->commandTester->execute(array(
            'temperature' => 'abc',
            'input_unit' => "fahrenheit",
            'output_unit' => "celsius"
        ));

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('not a number', $output);

        // Invalid units
        $this->commandTester->execute(array(
            'temperature' => 10,
            'input_unit' => "meters",
            'output_unit' => "feet"
        ));

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('unit is not valid', $output);

        // Same units
        $this->commandTester->execute(array(
            'temperature' => 10,
            'input_unit' => "celsius",
            'output_unit' => "celsius"
        ));

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('unit is not valid', $output);
    }
}
<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertTempCommand extends Command
{
    protected static $defaultName = "app:convert-temp";

    protected function configure()
    {
        $this->setDescription("Command to convert temperatures between Celcius and Fahrenheit")
            ->addArgument("temperature", InputArgument::REQUIRED, "The input temperature to convert")
            ->addArgument("input_unit", InputArgument::REQUIRED, "The unit of the input temperature")
            ->addArgument("output_unit", InputArgument::REQUIRED, "The unit of the output temperature");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Get input arguments
        $temperature = $input->getArgument("temperature");
        $inputUnit = $input->getArgument("input_unit");
        $outputUnit = $input->getArgument("output_unit");

        // Check temperature input is a number
        if (!is_numeric($temperature)) {
            $output->writeLn("Temperature inputed is not a number");
            return Command::FAILURE;
        }

        // Convert unit to lower to account for capitalisations
        $inputUnit = strtolower($inputUnit);
        $outputUnit = strtolower($outputUnit);

        // $conversions[$inputUnit][$outputUnit]
        $conversions = [
            "celsius" => [
                "fahrenheit" => ($temperature * 9 / 5) + 32
            ],
            "fahrenheit" => [
                "celsius" => ($temperature - 32) * 5 / 9
            ]
        ];

        // Validate the units are valid
        if (!array_key_exists($inputUnit, $conversions) 
            or !array_key_exists($outputUnit, $conversions[$inputUnit])) {
            $output->writeLn("Temperature unit is not valid. (Try celsius or fahrenheit)");
            return Command::FAILURE;
        }

        // Use the conversion array to get the corrisponding formula
        $temperatureOutput = $conversions[$inputUnit][$outputUnit];

        // Round temperature to 2 decimal places
        $temperatureOutput = round($temperatureOutput, 2);

        $output->writeLn("{$temperature} {$inputUnit} is {$temperatureOutput} {$outputUnit}");
        return Command::SUCCESS;
    }
}

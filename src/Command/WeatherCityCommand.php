<?php

namespace App\Command;

use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:city',
    description: 'Displays measurements for city in country',
)]
class WeatherCityCommand extends Command
{
    public function __construct(
        private readonly WeatherUtil $weatherUtil,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('country', InputArgument::REQUIRED, 'Country code [eg. PL]')
            ->addArgument('city', InputArgument::REQUIRED, 'City name [eg. Szczecin]')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $countryCode = $input->getArgument('country');
        $cityName = $input->getArgument('city');

        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($countryCode, $cityName);
        $io->writeln(sprintf('Location: %s', $cityName));
        $io->table(['Date', 'Temperature'], array_map(fn($m) => [
            $m->getDate()->format('Y-m-d'),
            $m->getTemperature(),
        ], $measurements));

        return Command::SUCCESS;
    }
}

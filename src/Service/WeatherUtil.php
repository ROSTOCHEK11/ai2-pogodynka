<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\WeatherData;
use App\Repository\LocationRepository;
use App\Repository\WeatherDataRepository;

class WeatherUtil
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly WeatherDataRepository $weatherDataRepository,
    )
    {
    }

    /**
     * @return WeatherData[]
     */
    public function getWeatherForLocation(Location $location): array
    {
        $measurements = $this->weatherDataRepository->findByLocation($location);
        return $measurements;
    }

    /**
     * @return WeatherData[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->locationRepository->findOneBy([
            'country' => $countryCode,
            'city' => $city,
        ]);

        $measurements = $this->getWeatherForLocation($location);

        return $measurements;
    }
}

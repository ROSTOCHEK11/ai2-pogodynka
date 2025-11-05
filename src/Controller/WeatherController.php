<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Repository\WeatherDataRepository;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{country}/{city}', name: 'app_weather', requirements: ['country' => '^[a-zA-Z]{2}$'])]
    public function showWeather( string $city, ?string $country, LocationRepository $locationRepository, WeatherUtil $util): Response
    {
        $location = $locationRepository->findOneByCountryAndCity($country, $city);


        $weather_data = $util->getWeatherForLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'weather_data' => $weather_data,
        ]);
    }

}

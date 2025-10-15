<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Repository\WeatherDataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{id}', name: 'app_weather')]
    public function city(): Response
    {
        return $this->render('city.html.twig', [
            'controller_name' => 'WeatherController',
        ]);
    }

    #[Route('/weather/{country}/{city}', name: 'app_weather', requirements: ['country' => '^[a-zA-Z]{2}$'])]
    public function showWeather( string $city, ?string $country, LocationRepository $locationRepository, WeatherDataRepository $weatherRepository): Response
    {
        $location = $locationRepository->findOneByCountryAndCity($country, $city);


        $weather_data = $weatherRepository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'weather_data' => $weather_data,
        ]);
    }

}

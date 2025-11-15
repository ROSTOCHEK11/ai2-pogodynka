<?php

namespace App\Controller;

use App\Entity\WeatherData;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        WeatherUtil $util,
        #[MapQueryParameter('country')] string $country,
        #[MapQueryParameter('city')] string $city,
        #[MapQueryParameter('format')] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response
    {
        $measurements = $util->getWeatherForCountryAndCity($country, $city);

        if ($format === 'csv') {
            if ($twig) {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]);
            } else {
                $csv = "city,country,date,celsius,fahrenheit\n";
                $csv .= implode(
                    "\n",
                    array_map(fn(WeatherData $m) => sprintf(
                        '%s,%s,%s,%s,%s',
                        $city,
                        $country,
                        $m->getDate()->format('Y-m-d'),
                        $m->getTemperature(),
                        $m->getFahrenheit(),
                    ), $measurements)
                );

                return new Response($csv, 200, [
//                'Content-Type' => 'text/csv',
                ]);
            }
        }

        if ($twig) {
            return $this->render('weather_api/index.json.twig', [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ]);
        } else {
            return $this->json([
                'city' => $city,
                'country' => $country,
                'measurements' => array_map(fn(WeatherData $m) => [
                    'date' => $m->getDate()->format('Y-m-d'),
                    'celsius' => $m->getTemperature(),
                    'fahrenheit' => $m->getFahrenheit(),
                ], $measurements),
            ]);
        }


    }
}

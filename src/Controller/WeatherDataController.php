<?php

namespace App\Controller;

use App\Entity\WeatherData;
use App\Form\WeatherDataType;
use App\Repository\WeatherDataRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/weather/data')]
final class WeatherDataController extends AbstractController
{
    #[Route(name: 'app_weather_data_index', methods: ['GET'])]
    #[IsGranted('ROLE_WEATHER_INDEX')]
    public function index(WeatherDataRepository $weatherDataRepository): Response
    {
        return $this->render('weather_data/index.html.twig', [
            'weather_datas' => $weatherDataRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_weather_data_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_WEATHER_NEW')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $weatherDatum = new WeatherData();
        $form = $this->createForm(WeatherDataType::class, $weatherDatum, [
            'validation_groups' => 'create',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($weatherDatum);
            $entityManager->flush();

            return $this->redirectToRoute('app_weather_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weather_data/new.html.twig', [
            'weather_datum' => $weatherDatum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weather_data_show', methods: ['GET'])]
    #[IsGranted('ROLE_WEATHER_SHOW')]
    public function show(WeatherData $weatherDatum): Response
    {
        return $this->render('weather_data/show.html.twig', [
            'weather_datum' => $weatherDatum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_weather_data_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_WEATHER_EDIT')]
    public function edit(Request $request, WeatherData $weatherDatum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WeatherDataType::class, $weatherDatum, [
            'validation_groups' => 'edit',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_weather_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weather_data/edit.html.twig', [
            'weather_datum' => $weatherDatum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weather_data_delete', methods: ['POST'])]
    #[IsGranted('ROLE_WEATHER_DELETE')]
    public function delete(Request $request, WeatherData $weatherDatum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$weatherDatum->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($weatherDatum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_weather_data_index', [], Response::HTTP_SEE_OTHER);
    }
}

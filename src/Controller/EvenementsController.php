<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class EvenementsController extends AbstractController
{
    #[Route('/events', name: 'app_events')]
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findBy(['canceledDate' => null]);

        return $this->render('evenements/index.html.twig', [
            'events' => $events
        ]);

    }

    #[Route('/events/{id}', name: 'app_event_show')]
    public function show($id, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            return $this->redirectToRoute('app_events');
        }

        return $this->render('evenements/show.html.twig', [
            'event' => $event
        ]);
    }
}

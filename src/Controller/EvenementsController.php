<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class EvenementsController extends AbstractController
{
    // Route for displaying the list of events
    #[Route('/evenements', name: 'app_evenements')]
    public function index(): Response
    {
        // Define a list of events
        $events = [
            [
                'id' => 1,
                'title' => 'Fantastic workshops - online',
                'date' => new \DateTime('2025-03-29 17:00:00'),
                'duration' => '2',
                'description' => 'Participez à l\'atelier en deux parties en ligne ! Inscrivez-vous avant le 5 mars pour y participer !',
                'image' => '/images/event1.png',
                'link' => '#'
            ],
            [
                'id' => 2,
                'title' => 'Conférence sur l\'IA',
                'date' => new \DateTime('2025-04-10 14:00:00'),
                'duration' => '1',
                'description' => 'Découvrez les dernières avancées en intelligence artificielle.',
                'image' => '/images/event2.png',
                'link' => '#'
            ],
            [
                'id' => 3,
                'title' => 'Rencontre avec les développeurs de Symfony',
                'date' => new \DateTime('2025-04-15 18:00:00'),
                'duration' => '2',
                'description' => 'Rencontrez les développeurs de Symfony et découvrez les dernières nouveautés du framework.',
                'image' => '/images/event3.png',
                'link' => '#'
            ],
            [
                'id' => 4,
                'title' => 'Conférence sur le design web',
                'date' => new \DateTime('2025-04-22 14:00:00'),
                'duration' => '1',
                'description' => 'Découvrez les dernières tendances en matière de design web.',
                'image' => '/images/event4.png',
                'link' => '#'
            ]
        ];

        // Render the index page with the list of events
        return $this->render('evenements/index.html.twig', [
            'events' => $events
        ]);
    }

    // Route for displaying a single event
    #[Route('/evenements/{id}', name: 'app_evenement_show')]
    public function show($id): Response
    {
        // Define the events array again
        $events = [
            [
                'id' => 1,
                'title' => 'Fantastic workshops - online',
                'date' => new \DateTime('2025-03-29 17:00:00'),
                'duration' => '2',
                'description' => 'Participez à l\'atelier en deux parties en ligne ! Inscrivez-vous avant le 5 mars pour y participer !',
                'image' => '/images/event1.png',
                'link' => '#'
            ],
            [
                'id' => 2,
                'title' => 'Conférence sur l\'IA',
                'date' => new \DateTime('2025-04-10 14:00:00'),
                'duration' => '1',
                'description' => 'Découvrez les dernières avancées en intelligence artificielle.',
                'image' => '/images/event2.png',
                'link' => '#'
            ],
            [
                'id' => 3,
                'title' => 'Rencontre avec les développeurs de Symfony',
                'date' => new \DateTime('2025-04-15 18:00:00'),
                'duration' => '2',
                'description' => 'Rencontrez les développeurs de Symfony et découvrez les dernières nouveautés du framework.',
                'image' => '/images/event3.png',
                'link' => '#'
            ],
            [
                'id' => 4,
                'title' => 'Conférence sur le design web',
                'date' => new \DateTime('2025-04-22 14:00:00'),
                'duration' => '1',
                'description' => 'Découvrez les dernières tendances en matière de design web.',
                'image' => '/images/event4.png',
                'link' => '#'
            ]
        ];

        // Search for the event by ID
        $event = null;
        foreach ($events as $e) {
            if ($e['id'] == $id) {
                $event = $e;
                break;
            }
        }

        // If the event is not found, throw a 404 error
        if (!$event) {
            throw $this->createNotFoundException('Événement introuvable !');
        }

        // Render the single event page
        return $this->render('evenements/show.html.twig', [
            'event' => $event
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EvenementsController extends AbstractController
{
    #[Route('/evenements', name: 'app_evenements')]
    public function index(): Response
    {
        $events = [
            [
                'title' => 'Fantastic workshops - online',
                'date' => new \DateTime('2025-03-29 17:00:00'),
                'duration' => '2',
                'description' => 'Participez à l\'atelier en deux parties en ligne ! Inscrivez-vous avant le 5 mars pour y participer !',
                'image' => '/images/event1.png',
                'link' => '#'
            ],
            [
                'title' => 'Conférence sur l\'IA',
                'date' => new \DateTime('2025-04-10 14:00:00'),
                'duration' => '1',
                'description' => 'Découvrez les dernières avancées en intelligence artificielle.',
                'image' => '/images/event2.png',
                'link' => '#'
            ],
            [
                'title' => 'Rencontre avec les développeurs de Symfony',
                'date' => new \DateTime('2025-04-15 18:00:00'),
                'duration' => '2',
                'description' => 'Rencontrez les développeurs de Symfony et découvrez les dernières nouveautés du framework.',
                'image' => '/images/event3.png',
                'link' => '#'
            ],
            [
                'title' => 'Conférence sur le design web',
                'date' => new \DateTime('2025-04-22 14:00:00'),
                'duration' => '1',
                'description' => 'Découvrez les dernières tendances en matière de design web.',
                'image' => '/images/event4.png',
                'link' => '#'
            ]
        ];

        return $this->render('evenements/index.html.twig', [
            'events' => $events
        ]);
    }
}

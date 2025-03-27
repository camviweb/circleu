<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\RegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(PostRepository $postRepository, RegistrationRepository $registrationRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer uniquement les posts non supprimés
        $posts = $postRepository->findBy(['user' => $user, 'deletedDate' => NULL]);

        // Récupérer les événements où l'utilisateur est inscrit
        $registrations = $registrationRepository->findBy(['email' => $user->getEmail()]);
        $events = array_map(fn($registration) => $registration->getEvent(), $registrations);

        return $this->render('compte/compte.html.twig', [
            'user' => $user,
            'events' => $events,
            'posts' => $posts, // Passe les posts non supprimés
        ]);
    }
}


?>
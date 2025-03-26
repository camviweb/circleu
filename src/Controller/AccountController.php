<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'compte')]
    public function index(): Response
    {
        $user = $this->getUser(); // ici ca recupere l'user connecté 

        if (!$user) {
            return $this->redirectToRoute('app_login'); // ici redirection si jamais user n'est ps connecté
        }

        return $this->render('compte/compte.html.twig', [
            'user' => $user,
        ]);
    }
}


?>

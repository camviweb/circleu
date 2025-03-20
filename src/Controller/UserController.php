<?php
// src/Controller/UserController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function create(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vous pouvez enregistrer l'entité en base de données ici
            // Par exemple :
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($user);
            // $entityManager->flush();

            // Message flash pour informer que l'utilisateur a bien été créé
            $this->addFlash('success', 'L\'utilisateur a été créé avec succès !');

            // Rediriger vers une autre page, ou afficher un message de succès
            return $this->redirectToRoute('user_create');
        }

        return $this->render('user/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
?>
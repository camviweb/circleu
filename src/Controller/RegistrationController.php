<?php

namespace App\Controller;

use App\Entity\Registration;
use App\Form\RegistrationType;
use App\Repository\EventRepository;
use App\Repository\RegistrationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RegistrationController extends AbstractController
{
    #[Route('/events/{id}/register', name: 'app_event_register', requirements: ['id' => '\d+'])]
    #[isGranted('ROLE_USER')]
    public function register(int $id, Request $request, EntityManagerInterface $entityManager, EventRepository $eventRepository, RegistrationRepository $registrationRepository): Response
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            return $this->redirectToRoute('app_events');
        }

        // Vérifier si l'utilisateur est déjà inscrit à cet événement
        $user = $this->getUser();
        $existingRegistration = $registrationRepository->findOneBy([
            'event' => $event, // Recherche par l'événement
            'email' => $user->getEmail() // Recherche par l'email de l'utilisateur
        ]);

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour vous inscrire à un événement.');
            return $this->redirectToRoute('app_login');
        }

        if ($existingRegistration) {
            $this->addFlash('danger', 'Vous êtes déjà inscrit à cet événement.');
            return $this->redirectToRoute('app_events');
        }

        // Créer une nouvelle inscription
        $registration = new Registration();
        $registration->setEvent($event);

        // Remplir les informations de l'utilisateur si connecté
        if ($user) {
            $registration->setNom($user->getLastName());
            $registration->setPrenom($user->getFirstName());
            $registration->setEmail($user->getEmail());
            $registration->setFormation($user->getFormation());
            $registration->setNiveau($user->getEducationLevel());
        }

        $form = $this->createForm(RegistrationType::class, $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Réinitialiser les champs afin qu'ils restent les mêmes en cas de modification côté client dans le code source
            $registration->setNom($user->getLastName());
            $registration->setPrenom($user->getFirstName());
            $registration->setEmail($user->getEmail());
            $registration->setFormation($user->getFormation());
            $registration->setNiveau($user->getEducationLevel());

            $entityManager->persist($registration);
            $entityManager->flush();

            $this->addFlash('success', 'Inscription réussie à l\'événement "' . $event->getTitle() . '".');
            return $this->redirectToRoute('app_events');
        }

        return $this->render('registration/form.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

    #[Route('/events/{id}/cancel', name: 'app_event_cancel', requirements: ['id' => '\d+'])]
    #[isGranted('ROLE_USER')]
    public function cancel(int $id, EntityManagerInterface $entityManager, EventRepository $eventRepository, RegistrationRepository $registrationRepository): Response
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            return $this->redirectToRoute('app_events');
        }

        $user = $this->getUser();
        $registration = $registrationRepository->findOneBy([
            'event' => $event,
            'email' => $user->getEmail()
        ]);

        if (!$registration) {
            $this->addFlash('danger', 'Vous n\'êtes pas inscrit à cet événement.');
            return $this->redirectToRoute('app_account');
        }

        $entityManager->remove($registration);
        $entityManager->flush();

        $this->addFlash('success', 'Inscription annulée à l\'événement "' . $event->getTitle() . '".');
        return $this->redirectToRoute('app_account');
    }

}

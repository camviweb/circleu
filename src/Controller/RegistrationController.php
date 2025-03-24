<?php

namespace App\Controller;

use App\Entity\Registration;
use App\Form\RegistrationType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/event/{id}/register', name: 'event_register', requirements: ['id' => '\d+'])]
    public function register(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        EventRepository $eventRepository
    ): Response {
        // Debugging: Check if the ID is passed correctly
        dump('Received Event ID:', $id);
        dump('Request Attributes:', $request->attributes->all());

        // Find the event
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException("Événement introuvable !");
        }

        // Create new registration and set the event
        $registration = new Registration();
        $registration->setEvent($event);

        // Create and handle form submission
        $form = $this->createForm(RegistrationType::class, $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($registration);
            $entityManager->flush();

            // Flash message for success
            $this->addFlash('success', 'Inscription réussie !');

            // Redirect to event list or event details page
            return $this->redirectToRoute('event_list');
        }

        return $this->render('registration/form.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }
}

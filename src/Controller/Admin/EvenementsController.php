<?php

namespace App\Controller\Admin;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Event;
use App\Form\EventType;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class EvenementsController extends AbstractController
{
    #[Route('/events/{id}/edit', name: 'app_admin_event_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, Event $event): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $oldPicture = $event->getPicture(); // Sauvegarde l'ancienne image

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                $newFilename = uniqid() . '.' . $pictureFile->guessExtension();

                // Déplace le fichier
                $pictureFile->move(
                    $this->getParameter('event_images_directory'),
                    $newFilename
                );

                // Supprime l'ancienne image si une nouvelle est uploadée
                if ($oldPicture && file_exists($this->getParameter('event_images_directory') . '/' . basename($oldPicture))) {
                    unlink($this->getParameter('event_images_directory') . '/' . basename($oldPicture));
                }

                // Met à jour l'image
                $event->setPicture('/uploads/events/' . $newFilename);
            } else {
                // Garde l'ancienne image si aucune nouvelle n'est fournie
                $event->setPicture($oldPicture);
            }

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', 'Événement modifié avec succès.');
            return $this->redirectToRoute('app_events');
        }

        return $this->render('evenements/admin/edit.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }


    #[Route('/events/new', name: 'app_admin_event_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $event = new Event();
        $event->setCreatedDate(new \DateTime()); // Date de création automatique

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload de l'image
            $pictureFile = $form->get('picture')->getData();
            if ($pictureFile) {
                $newFilename = uniqid().'.'.$pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('event_images_directory'), // Défini dans services.yaml
                    $newFilename
                );
                $event->setPicture('/uploads/events/' . $newFilename);
            }

            // Ajouter les participants sélectionnés
            $participants = $form->get('participants')->getData();
            if ($participants) {
                foreach ($participants as $participant) {
                    $event->addParticipant($participant);
                }
            }

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', 'Événement créé avec succès.');

            return $this->redirectToRoute('app_events');
        }

        return $this->render('evenements/admin/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/events/{id}/delete', name: 'app_admin_event_delete')]
    public function delete(EntityManagerInterface $entityManager, Event $event): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if (!$event) {
            $this->addFlash('danger', 'Événement introuvable.');
        }

        $event->setCanceledDate(new \DateTime());

        $entityManager->persist($event);
        $entityManager->flush();

        $this->addFlash('success', 'Événement supprimé avec succès.');

        return $this->redirectToRoute('app_account');
    }

    #[Route('/events/{id}/participants', name: 'app_admin_event_participants')]
    #[IsGranted('ROLE_ADMIN')]
    public function participants(Event $event): Response
    {
        $participants = $event->getParticipants();

        return $this->render('evenements/admin/participants.html.twig', [
            'event' => $event,
            'participants' => $participants
        ]);
    }


    #[Route('/events/{id}/participants/excel', name: 'app_admin_event_participants_excel')]
    #[IsGranted('ROLE_ADMIN')]
    public function exportParticipantsToExcel(Event $event): Response
    {
        $participants = $event->getParticipants();

        // Créer un nouvel objet Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Ajouter les en-têtes de colonnes
        $sheet->setCellValue('A1', 'Nom')
            ->setCellValue('B1', 'Prénom')
            ->setCellValue('C1', 'Email');

        // Remplir les données des participants
        $row = 2;
        foreach ($participants as $participant) {
            $sheet->setCellValue('A' . $row, $participant->getLastName())
                ->setCellValue('B' . $row, $participant->getFirstName())
                ->setCellValue('C' . $row, $participant->getEmail());
            $row++;
        }

        // Créer une réponse en stream pour télécharger le fichier
        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        // Définir les en-têtes HTTP pour le téléchargement du fichier
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="participants_' . $event->getId() . '_' . $event->getTitle() . '.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

}

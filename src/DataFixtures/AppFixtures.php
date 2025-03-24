<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer les utilisateurs
        $firstNames = ['Ewa', 'Jonathan', 'Xavier', 'Cam-Vi', 'Éléni', 'Manal'];
        $lastNames = ['Lobodzinska', 'Levy', 'Alibert', 'Nguyen', 'Xynou', 'Oumlil'];
        $formations = ['Informatique', 'Mathématiques', 'Physique', 'Chimie', 'Biologie', 'Géologie'];
        $educationLevels = ['Bac +1', 'Bac +2', 'Bac +3', 'Bac +4', 'Bac +5'];

        $users = [];

        for ($i = 0; $i < 6; $i++) {
            $user = new User();
            $user->setFirstName($firstNames[$i]);
            $user->setLastName($lastNames[$i]);
            $user->setEmail(strtolower($firstNames[$i] . '.' . $lastNames[$i] . '@etu.u-paris.fr'));
            $user->setRole('ROLE_USER');
            $user->setProfilePic('/images/user_icon.png');
            $user->setFormation($formations[array_rand($formations)]);
            $user->setEducationLevel($educationLevels[array_rand($educationLevels)]);
            $user->setCreatedDate(new \DateTime());

            $password = $this->passwordHasher->hashPassword($user, 'mdp');
            $user->setPassword($password);

            $manager->persist($user);
            $users[] = $user;
        }

        // Créer l'utilisateur admin
        $admin = new User();
        $admin->setFirstName('Admin');
        $admin->setLastName('Admin');
        $admin->setEmail('admin@circleu.fr');
        $admin->setRole('ROLE_ADMIN');
        $admin->setProfilePic('/images/user_icon.png');
        $admin->setFormation('Informatique');
        $admin->setEducationLevel('Bac +5');
        $admin->setCreatedDate(new \DateTime());

        $password = $this->passwordHasher->hashPassword($admin, 'mdp');
        $admin->setPassword($password);

        $manager->persist($admin);
        $manager->flush();

        // Créer les événements
        $user = $admin; // Utiliser l'admin comme organisateur

        $eventsData = [
            [
                'title' => 'Fantastic workshops - online',
                'date' => new \DateTime('2025-03-29 17:00:00'),
                'duration' => 2,
                'description' => 'Participez à l\'atelier en deux parties en ligne ! Inscrivez-vous avant le 5 mars pour y participer !',
                'picture' => '/images/event1.png',
                'dateLimite' => new \DateTime('2025-03-05'),
            ],
            [
                'title' => 'Conférence sur l\'IA',
                'date' => new \DateTime('2025-04-10 14:00:00'),
                'duration' => 1,
                'description' => 'Découvrez les dernières avancées en intelligence artificielle.',
                'picture' => '/images/event2.png',
                'dateLimite' => new \DateTime('2025-04-05'),
            ],
            [
                'title' => 'Rencontre avec les développeurs de Symfony',
                'date' => new \DateTime('2025-04-15 18:00:00'),
                'duration' => 2,
                'description' => 'Rencontrez les développeurs de Symfony et découvrez les dernières nouveautés du framework.',
                'picture' => '/images/event3.png',
                'dateLimite' => new \DateTime('2025-04-10'),
            ],
            [
                'title' => 'Conférence sur le design web',
                'date' => new \DateTime('2025-04-22 14:00:00'),
                'duration' => 1,
                'description' => 'Découvrez les dernières tendances en matière de design web.',
                'picture' => '/images/event4.png',
                'dateLimite' => new \DateTime('2025-04-17'),
            ],
        ];

        foreach ($eventsData as $eventData) {
            $event = new Event();
            $event->setTitle($eventData['title'])
                ->setEventDate($eventData['date'])
                ->setDuration($eventData['duration'])
                ->setDescription($eventData['description'])
                ->setPicture($eventData['picture'])
                ->setDateLimite($eventData['dateLimite'])
                ->setCreatedDate(new \DateTime())
                ->setOrganizer($user); // Utiliser l'utilisateur admin comme organisateur

            // Ajouter des participants (par exemple, les 3 premiers utilisateurs)
            for ($i = 0; $i < 3; $i++) {
                $event->addParticipant($users[$i]);
            }

            $manager->persist($event);
        }

        $manager->flush();
    }
}
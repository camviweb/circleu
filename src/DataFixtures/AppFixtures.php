<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
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
        $firstNames = ['Ewa', 'Jonathan', 'Xavier', 'Cam-Vi', 'Éléni', 'Manal', 'Luc', 'Sophie', 'Hugo', 'Alice', 'Paul', 'Emma', 'Antoine', 'Sarah', 'Thomas', 'Julie', 'David', 'Clara', 'Mathieu', 'Laura'];
        $lastNames = ['Lobodzinska', 'Levy', 'Alibert', 'Nguyen', 'Xynou', 'Oumlil', 'Martin', 'Dupont', 'Durand', 'Morel', 'Lemoine', 'Rousseau', 'Garnier', 'Faure', 'Blanc', 'Henry', 'Lefevre', 'Bertrand', 'Perrot', 'Renard'];
        $formations = ['Informatique', 'Mathématiques', 'Physique', 'Chimie', 'Biologie', 'Géologie'];
        $educationLevels = ['Bac +1', 'Bac +2', 'Bac +3', 'Bac +4', 'Bac +5'];

        $users = [];

        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setFirstName($firstNames[$i])
                ->setLastName($lastNames[$i])
                ->setEmail(strtolower($firstNames[$i] . '.' . $lastNames[$i] . '@etu.u-paris.fr'))
                ->setRole('ROLE_USER')
                ->setProfilePic('/images/user_icon.png')
                ->setFormation($formations[array_rand($formations)])
                ->setEducationLevel($educationLevels[array_rand($educationLevels)])
                ->setCreatedDate(new \DateTime());

            $password = $this->passwordHasher->hashPassword($user, 'mdp');
            $user->setPassword($password);

            $manager->persist($user);
            $users[] = $user;
        }

        // Créer l'utilisateur admin
        $admin = new User();
        $admin->setFirstName('Nicolas')
            ->setLastName('Boss')
            ->setEmail('admin@etu.u-paris.fr')
            ->setRole('ROLE_ADMIN')
            ->setProfilePic('/images/user_icon.png')
            ->setFormation('Informatique')
            ->setEducationLevel('Bac +5')
            ->setCreatedDate(new \DateTime());

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

            // Ajouter une 15aine de participants à chaque événement
            $participants = $users;
            shuffle($participants);
            $nbParticipants = rand(10, 15);
            for ($i = 0; $i < $nbParticipants; $i++) {
                $event->addParticipant($participants[$i]);
            }

            $manager->persist($event);


            // Créer des posts et des commentaires pour chaque événement

            // Définir les catégories et buts spécifiques
            $categories = [
                'Mobilités' => 'Mobilités',
                'Séjours Court' => 'Séjours Court',
                'Cours et Conférences en ligne' => 'Cours Conférences',
                'Evénements' => 'Evénements',
            ];

            $purposes = [
                'Alumni' => 'Alumni',
                'Question' => 'Question',
            ];

            // Définir des descriptions pour chaque catégorie et but
            $descriptions = [
                'Mobilités' => [
                    'Alumni' => 'Les alumni partagent leurs expériences sur les opportunités de mobilité à l\'étranger.',
                    'Question' => 'Quelles sont les meilleures options pour un séjour d\'études à l\'international ?',
                ],
                'Séjours Court' => [
                    'Alumni' => 'Les anciens étudiants parlent de leur expérience lors des séjours courts organisés par l\'université.',
                    'Question' => 'Quels sont les avantages de participer à un séjour court dans une autre ville ?',
                ],
                'Cours et Conférences en ligne' => [
                    'Alumni' => 'Découvrez comment les alumni ont enrichi leur parcours grâce aux cours et conférences en ligne.',
                    'Question' => 'Quelles sont les meilleures conférences en ligne sur les nouvelles technologies ?',
                ],
                'Evénements' => [
                    'Alumni' => 'Retrouvez les témoignages des alumni sur les événements marquants qu\'ils ont suivis pendant leurs études.',
                    'Question' => 'Quels événements à ne pas manquer dans le domaine de l\'informatique cette année ?',
                ],
            ];

            // Créer des posts
            $posts = [];

            foreach ($users as $user) {
                // Créer plusieurs posts pour chaque utilisateur
                for ($i = 0; $i < 2; $i++) {
                    // Choisir aléatoirement une catégorie et un but
                    $category = array_rand($categories);
                    $purpose = array_rand($purposes);

                    // Créer un post avec la description appropriée en fonction de la catégorie et du but
                    $post = new Post();
                    $post->setCategory($category)
                        ->setPurpose($purpose)
                        ->setDescription($descriptions[$category][$purpose])
                        ->setCreatedDate(new \DateTime())
                        ->setUser($user);

                    // Pas d'image obligatoire
                    if (rand(0, 1)) {
                        $post->setPicture('post_image' . rand(1, 3) . '.png');
                    }

                    $manager->persist($post);
                    $posts[] = $post;
                }
            }

            // Créer des commentaires pour les posts
            $commentContents = [
                'Ce post est très utile, merci pour ces informations !',
                'Je suis tout à fait d\'accord avec ce que tu dis, c\'est un sujet passionnant.',
                'Une excellente ressource ! Je vais la partager avec mes collègues.',
                'Intéressant, mais j\'aimerais en savoir plus sur ce point particulier.',
                'C\'est exactement ce que je cherchais, merci beaucoup !',
                'Super sujet !',
                'Très clair et bien expliqué, merci !',
                'J\'apprécie le partage d\'expérience.',
                'Un bon point de départ pour ceux qui veulent en savoir plus.',
                'Merci pour ces précieuses infos !',
                'Je vais appliquer ces conseils !',
                'Quelqu\'un a testé ?',
                'Je recommande aussi cette approche.',
                'On en parle beaucoup en ce moment, merci pour ce post !',
                'Est-ce que cela fonctionne aussi dans d\'autres contextes ?',
            ];

            foreach ($posts as $post) {
                $nbComments = rand(5, 15);
                for ($i = 0; $i < $nbComments; $i++) {
                    $comment = new Comment();
                    $comment->setContent($commentContents[array_rand($commentContents)])
                        ->setDate(new \DateTime())
                        ->setCreatedDate(new \DateTime())
                        ->setUser($users[array_rand($users)]) // Utilisateur aléatoire
                        ->setPost($post);

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $firstNames = ['Ewa', 'Jonathan', 'Xavier', 'Cam-Vi', 'Éléni', 'Manal'];
        $lastNames = ['Lobodzinska', 'Levy', 'Alibert', 'Nguyen', 'Xynou', 'Oumlil'];
        $formations = ['Informatique', 'Mathématiques', 'Physique', 'Chimie', 'Biologie', 'Géologie'];
        $educationLevels = ['Bac +1', 'Bac +2', 'Bac +3', 'Bac +4', 'Bac +5'];

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
        }

        $manager->flush();
    }
}
<?php
// src/Form/UserType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nom ne peut pas être vide']),
                    new Length(['max' => 255]),
                ],
            ])
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom ne peut pas être vide']),
                    new Length(['max' => 255]),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'L\'email ne peut pas être vide']),
                    new Length(['max' => 255]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le mot de passe ne peut pas être vide']),
                    new Length(['min' => 6, 'max' => 255]),
                ],
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('profilePic', FileType::class, [
                'required' => false,
                'mapped' => false, // puisque c'est un champ de fichier, pas directement lié à l'entité
            ])
            ->add('formation', TextType::class, [
                'required' => false,
            ])
            ->add('educationLevel', TextType::class, [
                'required' => false,
            ])
            ->add('createdDate', DateTimeType::class, [
                'data' => new \DateTime(), // valeur par défaut
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer un utilisateur'
            ]);
    }
}
?>
<?php

namespace App\Form;

use App\Entity\Registration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom *',
                'attr' => ['placeholder' => 'Entrez votre nom'],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom *',
                'attr' => ['placeholder' => 'Entrez votre prénom'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail *',
                'attr' => ['placeholder' => 'Entrez votre e-mail'],
            ])
            ->add('formation', TextType::class, [
                'label' => 'Formation *',
                'attr' => ['placeholder' => 'Entrez votre formation'],
            ])
            ->add('niveau', TextType::class, [
                'label' => "Niveau d'études *",
                'attr' => ['placeholder' => "Entrez votre niveau d'études"],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Registration::class,
        ]);
    }
}

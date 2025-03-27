<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EventType extends AbstractType
{
    private $security;

    // On injecte le service Security pour accéder à l'utilisateur connecté
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre'
            ])
            ->add('eventDate', null, [
                'widget' => 'single_text',
                'label' => 'Date de l\'événement'
            ])
            ->add('description', null, [
                'label' => 'Description'
            ])
            /*
             ->add('duration', TimeType::class, [
                'label' => 'Durée (heures et minutes)',
                'input' => 'string',
                'widget' => 'single_text',
                'attr' => ['placeholder' => 'hh:mm (ex: 02:30)']
            ])*/
            ->add('duration', null, [
                'label' => 'Durée (heures)'
            ])
            ->add('picture', FileType::class, [
                'label' => 'Image (JPEG, PNG, max 8Mo)',
                'mapped' => false, // Ne lie pas directement ce champ à l'entité Event
                'required' => false, // Facultatif, l'utilisateur peut ne pas ajouter d'image
                'constraints' => [
                    new File([
                        'maxSize' => '8M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG ou PNG)',
                    ])
                ],
                'attr' => [
                    'accept' => 'image/jpeg, image/png', // Limite l'acceptation aux fichiers JPEG et PNG
                ]
            ])
            ->add('dateLimite', null, [
                'widget' => 'single_text',
                'label' => 'Date limite d\'inscription'
            ])
            ->add('organizer', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getFirstName() . ' ' . $user->getLastName(); // Affiche le nom complet
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.role = :role')  // Filtre sur le rôle exact
                        ->setParameter('role', 'ROLE_ADMIN') // Recherche des utilisateurs avec ROLE_ADMIN
                        ->orderBy('u.firstName', 'ASC'); // Optionnel: trier par prénom
                },
                'data' => $this->security->getUser(), // Prémplissage par défaut avec l'utilisateur connecté
                'label' => 'Organisateur'
            ])
            ->add('participants', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'fullname',
                'multiple' => true,
                'expanded' => false, // Important si tu veux que ce soit un select et non des checkboxes
                'label' => 'Participants',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.role = :role')  // Filtre sur le rôle exact
                        ->setParameter('role', 'ROLE_USER') // Recherche des utilisateurs avec ROLE_USER
                        ->orderBy('u.firstName', 'ASC'); // Optionnel: trier par prénom
                },
                'required' => false, // Rend le champ facultatif
                'attr' => ['class' => 'js-select2'] // Ajoute une classe pour activer select2
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}

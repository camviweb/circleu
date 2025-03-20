<?php
// src/Form/PostType.php
namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', TextType::class, [
                'label' => 'Catégorie',
            ])
            ->add('purpose', TextType::class, [
                'label' => 'But',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('picture', FileType::class, [
                'label' => 'Image (optionnelle)',
                'required' => false,
                'mapped' => false, // L'image n'est pas directement mappée à une propriété
            ])
            ->add('createdDate', DateTimeType::class, [
                'label' => 'Date de création',
                'data' => new \DateTime(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
?>
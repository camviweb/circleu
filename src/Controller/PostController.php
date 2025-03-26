<?php
// src/Controller/PostController.php
namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
    
            return $this->redirectToRoute('app_login');
        }
        // Créer une nouvelle instance de Post
        $post = new Post();
        $post->setCreatedDate(new \DateTime());

        // Créer le formulaire
        $form = $this->createForm(PostType::class, $post);

        // Traiter le formulaire lorsque celui-ci est soumis
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le fichier de l'image si elle existe
            $pictureFile = $form->get('picture')->getData();
            if ($pictureFile) {
                $newFilename = uniqid().'.'.$pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('post_images_directory'), // Définir ce paramètre dans votre config services.yaml
                    $newFilename
                );
                $post->setPicture($newFilename);
            }

            // Sauvegarder le post en base de données
            $entityManager->persist($post);
            $entityManager->flush();

            // Rediriger vers une page de confirmation ou vers la liste des posts
            return $this->redirectToRoute('app_blog');
        }

        return $this->render('post/post.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
?>
<?php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;

final class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        $forms = [];

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

        // Générer un formulaire pour chaque post
        foreach ($posts as $post) {
            $comment = new Comment();
            $form = $this->createForm(CommentType::class, $comment);
            $forms[$post->getId()] = $form->createView();
        }

        return $this->render('blog/blog.html.twig', [
            'posts' => $posts,
            'forms' => $forms,
            'categories' => $categories,
            'purposes' => $purposes,
        ]);
    }

    #[Route('/post/{id}/comment', name: 'app_comment_add', methods: ['POST'])]
    public function addComment(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $comment->setPost($post);
        $comment->setUser($this->getUser());
        $comment->setDate(new \DateTime());
        $comment->setCreatedDate(new \DateTime());

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté.');
        }

        return $this->redirectToRoute('app_blog');
    }
}



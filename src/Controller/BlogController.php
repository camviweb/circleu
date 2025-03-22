<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(PostRepository $postRepository): Response
    {
        // Récupère tous les posts depuis la base de données
        $posts = $postRepository->findAll();

        // Passe les posts à la vue
        return $this->render('blog/blog.html.twig', [
            'posts' => $posts,
        ]);
    }
}
?>

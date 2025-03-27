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

class CommentController extends AbstractController
{
    #[Route('/post/{id}', name: 'app_post_show')]
    public function show(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('blog/blog.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
}

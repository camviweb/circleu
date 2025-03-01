<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(): Response
    {
        $posts = [
            [
                'user' => [
                    'name' => 'Emily',
                ],
                'date' => new \DateTime('2025-02-20 15:30'),
                'type' => 'alumni',
                'content' => "J'ai participé au CU. MUN !",
                'image' => 'images/post1.png',
                'comments' => [
                    ['user' => ['name' => 'Bob'], 'content' => "Wow! Comment s'inscrire ?"],
                    ['user' => ['name' => 'Alice'], 'content' => 'Félicitations !']
                ]
            ],
            [
                'user' => [
                    'name' => 'Damian',
                ],
                'date' => new \DateTime('2025-02-18 13:37'),
                'type' => 'question',
                'content' => 'Comment faire ... ?',
                'image' => null,
                'comments' => []
            ]
        ];
        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}

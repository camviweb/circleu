<?php
namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    // Récupérer tous les posts, triés par date (du plus récent au plus ancien)
    public function findAllPosts(): array
    {
        return $this->findBy([], ['createdAt' => 'DESC']);
    }
}
?>
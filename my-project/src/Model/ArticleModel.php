<?php
namespace App\Model;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;


class ArticleModel
{
    public $em;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->em = $entityManager;
    }
    
    public function showArticleByCategory($categoryId) {
        $articles = $this->em
        ->getRepository(Article::class)
        ->findBy(
            array('category' =>  $categoryId)
        );
             
        if (!$articles) {
            //toto v modelu nefunguje
            throw $this->createNotFoundException(
                'Brekeke.'
                );
        }
        
       
        return $articles;
    }
}
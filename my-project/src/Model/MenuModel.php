<?php
namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
use App\Entity\Article;
use Symfony\Component\Validator\Constraints\Collection;


class MenuModel
{
    public $em;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->em = $entityManager;
    }

    public function countArticlesInCategory(int $categoryId) {
        $articles = $this->em->getRepository(Article::class)
        ->findBy(
            array(
                'category' => $categoryId
            )
        );  
        
        return count($articles);
    }
}

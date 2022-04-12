<?php
namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Space;


class SpaceModel
{
    public $em;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->em = $entityManager;
    }
    
    public function showAllSpaces() {
        $spaces = $this->em
        ->getRepository(Space::class)
        ->findAll();
                        
        return $spaces;
    }
    
    public function getSpaceById(int $spaceId) {
        $space = $this->em
        ->getReference(Space::class, $spaceId);
        return $space;
    }
    
    public function getArticleInSpace(string $textId) {
        $articleInSpace = $this->em
        ->getRepository(Space::class)
        ->findOneby(array(
            'textId' => $textId,
        ))
        ->getArticle();
        
        return $articleInSpace;
    }
}

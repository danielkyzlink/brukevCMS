<?php
namespace App\Model;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

class SeoModel
{
    public $em;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->em = $entityManager;
    }
    
    public function createSeoTitle(String $nameOfArticle, int $iterace = 0) {
        if ($iterace == 0){
            $append = "";
        }else{
            $append = " " . $iterace;
        }
        
        $seoTitle = $nameOfArticle . $append;
        $seoTitle = iconv("UTF-8", "ASCII//TRANSLIT", $seoTitle);
        $seoTitle = str_replace(" ", "-", $seoTitle);
        $seoTitle = preg_replace('~[^-a-z0-9_]+~', '', $seoTitle); //vyhodi binec po iconvu
        
        $article = $this->em
        ->getRepository(Article::class)
        ->findOneBy(array('seoTitle' => $seoTitle));
        
        if (!$article) {
            return $seoTitle;
        }else{
            $iterace += 1;
            return $this->createSeoTitle($nameOfArticle, $iterace);
        }
    }
}
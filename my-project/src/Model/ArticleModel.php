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
            array('category' =>  $categoryId),
            array('dateOfCreated' => 'DESC')
        );
        
        return $articles;
    }
    
    public function saveArticle(object $article, object $picture, FileUploadModel $fileUpload) {        
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $this->em->persist($article);
        
        //uložení obrázku        
        if ($picture){
            $pictureName = $fileUpload->saveImage($picture, "MASTER");            
            //nastaveni jmena obrazku
            $article->setPicture($pictureName);
        }
        
        //set date
        $article->setDateOfCreated(new \DateTime());
        
        //set seoTitle
        $article->setSeoTitle($this->createSeoTitle($article->getName()));
        
        // actually executes the queries (i.e. the INSERT query)
        $this->em->flush();
        
        
    }
    
    public function createSeoTitle(String $nameOfArticle, int $iterace = 0) {
        echo($iterace);
        if ($iterace == 0){
            $append = "";
        }else{
            $append = " " . $iterace;
        }
        
        $seoTitle = $nameOfArticle . $append;
        setlocale(LC_CTYPE, 'cs_CZ'); //nastaveni iconv
        $seoTitle = iconv("UTF-8", "ASCII//TRANSLIT", $seoTitle);
        $seoTitle = str_replace(" ", "-", $seoTitle);
        
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
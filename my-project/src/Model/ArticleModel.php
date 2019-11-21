<?php
namespace App\Model;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;


class ArticleModel
{
    public $em;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->em = $entityManager;
    }
    
    public function showArticleByCategory($seoTitle) {
        $categoryId = $this->em
        ->getRepository(Category::class)
        ->findOneBy(array(
                'seoTitle' => $seoTitle,
            )
        );
        
        $articles = $this->em
        ->getRepository(Article::class)
        ->findBy(
            array(
                'category' => $categoryId,
                'state' => 1,                
            ),
            array('dateOfCreated' => 'DESC')
        );
        
        return $articles;
    }
    
    public function showArticleById($articleId) {
        /*$article = $this->em
        ->getRepository(Article::class)
        ->find($articleId);*/
        
        /*TODO zjistit rozdil mezi timto zapisem a vyse zakomentovanym*/
        $article = $this->em->getReference(Article::class, $articleId);
        
        return $article;
    }
    
    public function articleByIdToTrash($articleId) {
        /** @var $article Article */
        $article = $this->em->getRepository(Article::class)
        ->find($articleId);
        
        $this->em->persist($article);
        $article->setState(0);
        
        $this->em->flush();
    }
    
    public function articleByIdToKoncept($articleId) {
        /** @var $article Article */
        $article = $this->em->getRepository(Article::class)
        ->find($articleId);
        
        $this->em->persist($article);
        $article->setState(2);
        
        $this->em->flush();
    }

    public function saveArticle(Article $article, object $picture = null, FileUploadModel $fileUpload) {
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
        if(!$article->getSeoTitle()){
            $article->setSeoTitle($this->createSeoTitle($article->getName()));
        }
                
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
        /*setlocale(LC_CTYPE, 'cs_CZ'); //nastaveni iconv - nejak to zlobilo tak oddelavam*/
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
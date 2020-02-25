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
                'state' => Article::STATE_PUBLIKOVANO,
            ),
            array('dateOfCreated' => 'DESC')
        );
        
        return $articles;
    }
    
    public function showArticleById(int $articleId) {
        /*$article = $this->em
        ->getRepository(Article::class)
        ->find($articleId);*/
        
        /*TODO zjistit rozdil mezi timto zapisem a vyse zakomentovanym*/
        $article = $this->em->getReference(Article::class, $articleId);
        
        return $article;
    }

    public function articleToTrash(Article $article) {        
        $this->em->persist($article);
        $article->setState(Article::STATE_SMAZANO);
        
        $this->em->flush();
    }
    
    public function articleToKoncept(Article $article) {
        $this->em->persist($article);
        $article->setState(Article::STATE_REVIZE);
        $this->em->flush();
    }

    public function saveArticle(Article $article, object $picture = null, FileUploadModel $fileUpload, SeoModel $seoModel) {
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
            $article->setSeoTitle($seoModel->createSeoTitle($article->getName()));
        }
                
        $this->em->flush();
        
    }

    public function showLatestArticles($quantity) {
        $articles = $this->em->getRepository(Article::class)
        ->findBy(
            array(
                'state' => Article::STATE_PUBLIKOVANO,
            ),
            array('dateOfCreated' => 'DESC'),
            $quantity
        );
        return $articles;
    }
}
<?php
// src/Controller/Article.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

class ArticleUse extends AbstractController
{
    public function showArticle($id = 7)
    {
        // vykresleni sablony
        return $this->render('article/showArticle.html.twig', [
            'id' => $id,
        ]);
    }
    
    /**
     * @Route("/article/saveArticle", name="product")
     */
    public function saveArticle()
    {
        //prace s doctrinou
        $entityManager = $this->getDoctrine()->getManager();
        
        $article = new Article();
        $article->setName('Test name');
        $article->setPerex('perex');
        $article->setText('Lipsum!');
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($article);
        
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        
        // vykresleni sablony
        return $this->render('article/saveArticle.html.twig', [
            'id' => $article->getId(),
        ]);
    }
    
}
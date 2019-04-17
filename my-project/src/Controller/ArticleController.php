<?php
// src/Controller/Article.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/showArticle/{id}", name="showArticle")
     */
    public function showArticle($id)
    {
        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);
        
        if (!$article) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
                );
        }
            
        // vykresleni sablony s clankem dle ID
        return $this->render('admin/article/showArticle.html.twig', [
            'article' => $article,
        ]);
    }
    
    /**
     * @Route("/admin/article/listArticle", name="listArticle")
     */
    public function listArticles()
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();
        
        if (!$articles) {
            throw $this->createNotFoundException(
                'No product found.');
        }
            
        // vykresleni sablony
        return $this->render('admin/article/listArticles.html.twig', [
           'articles' => $articles, 
        ]);
    }
    
    /**
     * @Route("/admin/article/saveArticle", name="saveArticle")
     * 
     * Require ROLE_USER for only this controller method.
     *
     * @IsGranted("ROLE_USER")
     */
    public function saveArticle(Request $request)
    {
        // vytvarim novy Article
        $article = new Article();
        
        //vytvoreni formulare
        $form = $this->createFormBuilder($article)
            ->add('name', TextType::class)
            ->add('perex', TextType::class)
            ->add('text', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();
        
        //asi odchyd POSTu
        $form->handleRequest($request);
        
        //formular odeslan
        if ($form->isSubmitted() && $form->isValid()) {            
            
            $article = $form->getData();
    
            /*
            $article->setName('Test name');
            $article->setPerex('perex');
            $article->setText('Lipsum!');
            */
            
            //prace s doctrinou
            $entityManager = $this->getDoctrine()->getManager();
            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($article);
            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();
        }
            
        // vykresleni sablony ms formularem
        return $this->render('admin/article/saveArticle.html.twig', [
            /*'id' => $article->getId(),*/
            'form' => $form->createView(),
        ]);
    }
    
}
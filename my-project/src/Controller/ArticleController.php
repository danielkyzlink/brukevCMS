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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Model\FileUploadModel;

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
    public function saveArticle(Request $request, FileUploadModel $fileUpload)
    {
        
        // nacteni kategorii
        $categories = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findAll();
        
        if (!$categories) {
            throw $this->createNotFoundException(
                'No categories found.');
        }
        
        // vytvarim novy Article
        $article = new Article();
        
        //vytvoreni formulare
        $form = $this->createFormBuilder($article)
            ->add('active', CheckboxType::class)
            ->add('name', TextType::class)
            ->add('perex', TextType::class)
            ->add('picture', FileType::class)
            ->add('text', TextareaType::class, [
                'attr' => ['class' => 'tinymce'],                
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();
        
        //asi odchyd POSTu
        $form->handleRequest($request);
        
        //formular odeslan
        if ($form->isSubmitted() && $form->isValid()) {
            
            $article = $form->getData();
            
            //prace s doctrinou
            $entityManager = $this->getDoctrine()->getManager();
            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($article);

            
            //uložení obrázku
            $picture = $form['picture']->getData();
            if ($picture){
                $pictureName = $fileUpload->saveImage($picture, "MASTER");
                
                //nastaveni jmena obrazku
                $article->setPicture($pictureName);
            }
            
            //set date
            $article->setDateOfCreated(new \DateTime());
                        
            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();
        }
            
        // vykresleni sablony ms formularem
        return $this->render('admin/article/saveArticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/admin/article/fileUpload", name="fileUpload", methods={"POST"})
     *
     * Require ROLE_USER for only this controller method.
     *
     * @IsGranted("ROLE_USER")
     */
    public function fileUpload(Request $request, FileUploadModel $fileUpload)
    {
        $images = $request->files->all();
        $imageName = $fileUpload->saveImage($images);
        
        $publicPath = '/uploads/img/';
        $finalImgPath = $publicPath . $imageName;

        $response = new Response(json_encode(array('location' => $finalImgPath)));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
    
}
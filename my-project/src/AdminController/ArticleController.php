<?php
// src/AdminController/Article.php
namespace App\AdminController;

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
use App\Model\ArticleModel;

class ArticleController extends AbstractController
{
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
    public function saveArticle(Request $request, ArticleModel $articleModel, FileUploadModel $fileUpload)
    {        
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
            $picture = $form['picture']->getData();
            
            $articleModel->saveArticle($article, $picture, $fileUpload);

        }
            
        // vykresleni sablony s formularem
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
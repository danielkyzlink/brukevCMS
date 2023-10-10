<?php
// src/AdminController/Article.php
namespace App\AdminController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Category;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Model\FileUploadModel;
use App\Model\ArticleModel;
use App\Model\SeoModel;
use Doctrine\Persistence\ManagerRegistry;

class ArticleController extends AbstractController
{
    #[Route("/admin/article/listArticle", name: "listArticle")]
    public function listArticles(ManagerRegistry $doctrine)
    {
        $articles = $doctrine->getRepository(Article::class)
            ->findBy(array(
                'state' => array(1,2),
            ));
        
        if (!$articles) {
            throw $this->createNotFoundException(
                'No product found.');
        }
            
        // vykresleni sablony
        return $this->render('admin/article/listArticles.html.twig', [
           'articles' => $articles, 
        ]);
    }

    #[Route("/admin/article/listTrash", name: "listTrash")]
    public function listTrash(ManagerRegistry $doctrine)
    {
        $articles = $doctrine->getRepository(Article::class)
        ->findBy(array(
            'state' => 0,
        ));
        
        // vykresleni sablony
        return $this->render('admin/article/listTrash.html.twig', [
            'articles' => $articles,
        ]);
    }
    
    #[Route("/admin/article/articleToTrash/{article}", name: "articleToTrash")]
    public function articleToTrash(Article $article, ArticleModel $articleModel, Request $request)
    {
        $articleModel->articleToTrash($article);
        return $this->redirectToRoute('listArticle');
    }
    
    #[Route("/admin/article/articleSetKoncept/{article}", name: "articleSetKoncept")]
    public function articleToKoncept(Article $article, ArticleModel $articleModel, Request $request)
    {
        $articleModel->articleToKoncept($article);
        return $this->redirectToRoute('listTrash');
    }
    
    #[Route("/admin/article/saveArticle/{articleId}", requirements: ["articleId"=>"\d+"], defaults: ["articleId"=>null], name: "saveArticle")]
    public function saveArticle(int $articleId = null, Request $request, ArticleModel $articleModel, FileUploadModel $fileUpload, SeoModel $seoModel)
    {   
        // TODO mela by se vyresit kontrola existence articlu s danym ID
        
        // vytvarim novy Article nebo nacitam stavajici podle $articleId z URL
        if($articleId){
            $article = $articleModel->showArticleById($articleId);
            if ($article->getState() == 0){
                throw $this->createNotFoundException('Produkt je v koši.');
            }
        }else{
            $article = new Article();
        };
        
        //vytvoreni formulare
        $form = $this->createFormBuilder($article)
            ->add('state', ChoiceType::class, [
                'choices' => [
                    'Publikovat' => 1,
                    'K revizi' => 2,
                ],
            ])
            ->add('name', TextType::class)
            ->add('perex', TextType::class, array('required' => false));
        
            if($articleId){
                $form->add('picture', FileType::class, array('mapped' => false, 'required' => false, 'help' => $article->getPicture()));
                if($article->getPicture() != "") {
                    $form->add('deletePicture', CheckboxType::class, [
                        'label' => 'Smazat obrázek',
                        'required' => false,
                        'mapped' => false,
                    ]);
                }
            }else{
                $form->add('picture', FileType::class, array('required' => false));
            };
            
            
            $form->add('text', TextareaType::class, [
                'attr' => ['class' => 'tinymce'],                
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => '-- žádná --',
            ])
            ->add('Author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'userInfo',
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Pouze pro přihlášené návštěvníky' => 'WEB_USER',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Create Task']);
            $form = $form->getForm($form);
        
        //asi odchyd POSTu
        $form->handleRequest($request);
        
        //formular odeslan
        if ($form->isSubmitted() && $form->isValid()) {
            
            $article = $form->getData();

            $deletePicture = false;
            if (isset($form['deletePicture'])) {
                if ($form['deletePicture']->getData()) {
                    $deletePicture = true;
                }
            }

            $picture = $form['picture']->getData();

            $articleModel->saveArticle($article, $picture, $fileUpload, $seoModel, $deletePicture);
            $this->addFlash('success', 'Článek byl úspěšně uložen.');

            // Přesměrování na stejnou stránku
            return $this->redirectToRoute('saveArticle', ['articleId' => $articleId]);
        }
        // vykresleni sablony s formularem
        return $this->render('admin/article/saveArticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route("/admin/article/fileUpload", name: "fileUpload", methods: ["POST"])]
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
<?php
// src/Controller/Article.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Model\FileUploadModel;
use App\Model\CommentModel;
use App\Model\ConfigModel;
use App\Entity\Comment;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Regex;
use Doctrine\Persistence\ManagerRegistry;
use App\Model\SpaceModel;

class ArticleController extends AbstractController
{
    #[Route("/{seoTitle}", name: "showArticleDetail")]
    public function showArticle($seoTitle, Request $request, CommentModel $commentModel, ManagerRegistry $doctrine, SpaceModel $spaceModel, ConfigModel $configModel)
    {
        
        /* zobraz koncept jen prihlasenym */
        if($this->isGranted('ROLE_USER')){
            $statesArray = array(1, 2);
        }else{
            $statesArray  = array(1);
        }

        $article = $doctrine->getRepository(Article::class)
            ->findOneBy(array(
                'seoTitle' => $seoTitle,
                'state' => $statesArray,
            ));

        if (!$article) {
            throw $this->createNotFoundException(
                'No product found for id '.$seoTitle
            );
        }

        if (($article->getRoles() != null || $article->getRoles() != [])){
            if($this->getUser()){
                $roleUser = $this->getUser()->getRoles();
                $roleArticle = $article->getRoles();
                if (!count(array_intersect($roleArticle, $roleUser)) > 0) {
                    return $this->render("frontend/article/noAccess.html.twig");
                }
            }else{
                return $this->render("frontend/article/noAccess.html.twig");
            }
        }

        if ($configModel->getConfigParameter("komentare")) {
            /* comment form */
            $comment = new Comment();
            $form = $this->createFormBuilder($comment)
                ->add('name', TextType::class, [
                    'label' => 'Jméno',
                ])
                ->add('email', EmailType::class, [
                    'required' => false,
                    'help' => 'email se nebude zobrazovat',
                    'label' => 'E-mail',
                ])
                ->add('fuj', TextType::class, [
                    'help' => 'Kontrola člověčenství',
                    'label' => 'Napište který den předchází neděli',
                    'mapped' => false,
                    'constraints' => [
                        new Regex([
                            'pattern' => '/sobota/',
                            'message' => 'spammmm',
                        ])
                    ],
                ])
                ->add('text', TextareaType::class, [
                    'label' => 'Komentář',
                ])
                /* přidat antispam */
                ->add('save', SubmitType::class, [
                    'label' => 'Zveřejnit komentář',
                ]);
            $form = $form->getForm($form);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $comment = $form->getData();
                $commentModel->addComment($comment, $article);

                $this->addFlash('success', 'Komentář úspěšně vložen.');
                return $this->redirect($request->getUri());
            } else if ($form->isSubmitted() && !$form->isValid()) {
                $this->addFlash('warning', 'Formulář obsahuje chybu. <a href="#comments">opravit</a>');
            }

            $comments = $commentModel->showComments($article);
        }

        $space = $spaceModel;
        // vykresleni sablony s clankem dle ID
        return $this->render("frontend/article/showArticle.html.twig", [
            'article' => $article,
            'form' => isset($form) ? $form->createView() : NULL,
            'comments' => isset($comments) ? $comments: NULL,
            'space' => $space,
            'zobrazDatum' => $configModel->getConfigParameter("showArticleDate"),
        ]);
    }    
}
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
     * @Route("/showArticle/{id}", name="showArticleDetail")
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
        return $this->render('frontend/article/showArticle.html.twig', [
            'article' => $article,
        ]);
    }
}
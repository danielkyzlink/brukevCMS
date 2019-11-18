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
     * @Route("/clanek/{seoTitle}", name="showArticleDetail")
     */
    public function showArticle($seoTitle)
    {
        
        /* zobraz koncept jen prihlasenym */
        if($this->isGranted('ROLE_USER')){
            $statesArray = array(1, 2);
        }else{
            $statesArray  = array(1);
        }
        
        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(array(
                'seoTitle' => $seoTitle,
                'state' => $statesArray,
            ));
        
        if (!$article) {
            throw $this->createNotFoundException(
                'No product found for id '.$seoTitle
                );
        }
            
        // vykresleni sablony s clankem dle ID
        return $this->render('frontend/article/showArticle.html.twig', [
            'article' => $article,
        ]);
    }
}
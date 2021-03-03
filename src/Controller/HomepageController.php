<?php
// src/Controller/Article.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Model\ArticleModel;
use App\Entity\Category;
use App\Repository\CategoryRepository;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="FEhome")
     */
    public function showFrontendHP(ArticleModel $am)
    {   
        $homepageArticles = $am->showArticleByCategory('Homepage');
        $latestArticles = $this->showLatestArticles($am, 7);
        return $this->render('frontend/homepage/showFE.html.twig', [
            'categoryList' => $homepageArticles,
            'latestArticles' => $latestArticles,
        ]);
    }
    
    public function showLatestArticles(ArticleModel $articleModel, $quantity)
    {
        return $articleModel->showLatestArticles($quantity);
    }
}
<?php
// src/Controller/Article.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Model\ArticleModel;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Model\SpaceModel;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="FEhome")
     */
    public function showFrontendHP(ArticleModel $am, SpaceModel $spaceModel)
    {   
        $space = $spaceModel;
        $latestArticles = $this->showLatestArticles($am, 7);
        return $this->render('frontend/homepage/showFE.html.twig', [
            'latestArticles' => $latestArticles,
            'space' => $space,
        ]);
    }
    
    public function showLatestArticles(ArticleModel $articleModel, $quantity)
    {
        return $articleModel->showLatestArticles($quantity);
    }
}
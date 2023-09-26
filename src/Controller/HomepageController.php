<?php
// src/Controller/Article.php
namespace App\Controller;

use App\Model\ContentPieceModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Model\ArticleModel;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Model\SpaceModel;

class HomepageController extends AbstractController
{
    #[Route("/", name: "FEhome")]
    public function showFrontendHP(ArticleModel $am, SpaceModel $spaceModel, ContentPieceModel $contentPieceModel)
    {   
        $space = $spaceModel;
        $latestArticles = $this->showLatestArticles($am, 7);
        $klubContents = $contentPieceModel->showContentsBySection('klub');

        //nastaveni klicu dle id contentu TODO:nabizi se prodat textId
        $klubContentsNiceKeys = [];
        foreach ($klubContents as $klic => $hodnota) {
            $klubContentsNiceKeys[$hodnota->getId()] = $hodnota;
        }

        return $this->render("frontend/homepage/showFE.html.twig", [
            'latestArticles' => $latestArticles,
            'space' => $space,
            'klubContents' => $klubContentsNiceKeys,
        ]);
    }
    
    public function showLatestArticles(ArticleModel $articleModel, $quantity)
    {
        return $articleModel->showLatestArticles($quantity);
    }
}
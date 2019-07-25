<?php
// src/Controller/Article.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Model\ArticleModel;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="FEhome")
     */
    public function showFrontendHP(ArticleModel $am)
    {
        $data = $am->showArticleByCategory(1);
        return $this->render('frontend/homepage/showFE.html.twig', [
            'categoryList' => $data
        ]);
    }
}
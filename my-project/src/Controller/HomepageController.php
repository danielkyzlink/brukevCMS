<?php
// src/Controller/Article.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomepageController extends AbstractController
{
    /**
     * @Route("/admin", name="home")
     */
    public function showHP()
    {
        
        return $this->render('admin/homepage/showHP.html.twig');
    }

    /**
     * @Route("/", name="FEhome")
     */
    public function showFrontendHP()
    {
        
        return $this->render('frontend/homepage/showFE.html.twig');
    }
}
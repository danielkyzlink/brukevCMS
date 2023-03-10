<?php
// src/AdminController/HomepageController.php
namespace App\AdminController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Model\ArticleModel;

class HomepageController extends AbstractController
{
    #[Route("/admin/home", name: "home")]
    public function showHP()
    {
        
        return $this->render('admin/homepage/showHP.html.twig');
    }

}
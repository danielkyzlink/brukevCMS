<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    public function showMenu()
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        
            if (!$category) {
            throw $this->createNotFoundException(
                'No category found.'
                );
        }
            
        // vykresleni menu categorii
        return $this->render('frontend/category/category.html.twig', [
            'category' => $category,
        ]);
    }
    
   
}
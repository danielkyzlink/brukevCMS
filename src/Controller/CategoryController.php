<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use App\Model\ArticleModel;
use App\Repository\CategoryRepository;

class CategoryController extends AbstractController
{
    private $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository){
        $this->categoryRepository = $categoryRepository;
    }
    
    public function showMenu()
    {   
        $categories = $this->categoryRepository->findByParent(null);
        
        if (!$categories) {
            throw $this->createNotFoundException(
                'No category found.'
                );
        }
        
        // vykresleni menu categorii
        return $this->render('frontend/category/category.html.twig', [
            'category' => $categories,
        ]);
    }
    
    /**
     * @Route("/kategorie/{seoTitle}", name="category")
     */
    public function showCategory(ArticleModel $am, $seoTitle)
    {
        $data = $am->showArticleByCategory($seoTitle);
        return $this->render('frontend/category/categoryList.html.twig', [
            'categoryList' => $data
        ]);
    }    
   
}
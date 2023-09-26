<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use App\Model\ArticleModel;
use App\Repository\CategoryRepository;
use App\Model\CategoryModel;

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
        return $this->render("frontend/category/category.html.twig", [
            'category' => $categories,
        ]);
    }
    
    #[Route("/kategorie/{seoTitle}", name: "category")]
    public function showCategory(ArticleModel $am, $seoTitle, CategoryModel $cm)
    {
        $categoryData = $cm->showCategoryBySeoTitle($seoTitle);
        $data = $am->showArticleByCategory($seoTitle);
        if(count($data) == 1){
            $articleSeoTitle = $data[0]->getSeoTitle();
            return $this->redirectToRoute('showArticleDetail', ['seoTitle' => $articleSeoTitle]);
        }
        return $this->render("frontend/category/categoryList.html.twig", [
            'categoryList' => $data,
            'categoryData' => $categoryData
        ]);
    }    
   
}
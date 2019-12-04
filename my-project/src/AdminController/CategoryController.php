<?php
// src/AdminController/CategoryController.php
namespace App\AdminController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use App\Model\ArticleModel;
use App\Model\CategoryModel;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/category/listCategories", name="listCategories")
     */
    public function listCategories(CategoryModel $categoryModel)
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        
        if (!$categories) {
            throw $this->createNotFoundException(
                'No categories found.');
        }
        
        foreach ($categories as $category){
            $categoriesCount[$category->getId()] = $categoryModel->countArticlesInCategory($category->getId());
        }
        
        // vykresleni sablony
        return $this->render('admin/category/listCategories.html.twig', [
            'categories' => $categories,
            'categoriesCount' => $categoriesCount
        ]);
    }
    
    
    /**
     * @Route("/admin/category/saveCategory/{categoryId}", requirements={"categoryId"="\d+"}, name="saveCategory")
     */
    public function saveCategory(int $categoryId = null, Request $request, CategoryModel $categoryModel)
    {
        // vytvarim novou Category nebo nacitam stavajici podle $categoryId z URL
        if($categoryId){
            $category = $categoryModel->showCategoryById($categoryId);
        }else{
            $category = new Category();
        };
        
        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class)
            ->add('rank', IntegerType::class)
            ->add('save', SubmitType::class, ['label' => 'Ulož kategorii']);
        
        $form = $form->getForm($form);
        
        $form->handleRequest($request);
        


        //formular odeslan
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            
            $categoryModel->saveCategory($category);
            
        }
        
        // vykresleni sablony s formularem
        return $this->render('admin/category/saveCategory.html.twig', [
            'form' => $form->createView(),
        ]);

    }
    
    /**
     * @Route("/admin/category/deleteCategory/{categoryId}", name="deleteCategory")
     */
    public function deleteCategory(int $categoryId, CategoryModel $categoryModel)
    {
        $categoryModel->deleteCategory($categoryId);
        return $this->redirectToRoute('listCategories');
    }
}

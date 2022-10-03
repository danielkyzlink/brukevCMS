<?php
// src/AdminController/CategoryController.php
namespace App\AdminController;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use App\Model\ArticleModel;
use App\Model\CategoryModel;
use App\Model\SeoModel;
use Doctrine\Persistence\ManagerRegistry;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/category/listCategories", name="listCategories")
     */
    public function listCategories(CategoryModel $categoryModel, ManagerRegistry $doctrine)
    {
        $categories = $doctrine->getRepository(Category::class)
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
     * @Route("/admin/category/saveCategory/{categoryId}", requirements={"categoryId"="\d+"}, defaults={"categoryId"=null}, name="saveCategory")
     */
    public function saveCategory(int $categoryId = null, Request $request, CategoryModel $categoryModel, SeoModel $seoModel)
    {
        // vytvarim novou Category nebo nacitam stavajici podle $categoryId z URL
        if($categoryId){
            $category = $categoryModel->showCategoryById($categoryId);
        }else{
            $category = new Category();
        };
        
        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class)
            ->add('rankInMenu', IntegerType::class)
            ->add('parent', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('save', SubmitType::class, ['label' => 'Ulož kategorii']);
        
        $form = $form->getForm($form);
        
        $form->handleRequest($request);
        


        //formular odeslan
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            
            $categoryModel->saveCategory($category, $seoModel);
            
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

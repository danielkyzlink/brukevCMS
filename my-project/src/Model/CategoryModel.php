<?php
namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
use App\Entity\Article;


class CategoryModel
{
    public $em;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->em = $entityManager;
    }
    
    public function showCategoryById($categoryId) {
        $category = $this->em->getReference(Category::class, $categoryId);
        
        return $category;
    }
    
    public function saveCategory(Category $category) {
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $this->em->persist($category);
                
        //set seoTitle
        if(!$category->getSeoTitle()){
            $category->setSeoTitle($this->createSeoTitle($category->getName()));
        }
                
        $this->em->flush();
        
    }
    
    public function deleteCategory(int $categoryId) {
        if ($this->countArticlesInCategory($categoryId) == 0){
            $category = $this->em->getRepository(Category::class)
                ->find($categoryId);
            $this->em->remove($category);
            $this->em->flush();
        }
    }

    public function countArticlesInCategory(int $categoryId) {
        $articles = $this->em->getRepository(Article::class)
        ->findBy(
            array(
                'category' => $categoryId
            )
        );  
        return count($articles);
    }

    public function createSeoTitle(String $nameOfCategory, int $iterace = 0) {
        if ($iterace == 0){
            $append = "";
        }else{
            $append = " " . $iterace;
        }

        $seoTitle = $nameOfCategory . $append;
        $seoTitle = iconv("UTF-8", "ASCII//TRANSLIT", $seoTitle);
        $seoTitle = str_replace(" ", "-", $seoTitle);
        $seoTitle = preg_replace('~[^-a-z0-9_]+~', '', $seoTitle); //vyhodi binec po iconvu

        $article = $this->em
        ->getRepository(Category::class)
        ->findOneBy(array('seoTitle' => $seoTitle));

        if (!$article) {
            return $seoTitle;
        }else{
            $iterace += 1;
            return $this->createSeoTitle($nameOfCategory, $iterace);
        }
    }
}

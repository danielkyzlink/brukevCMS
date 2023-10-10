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
    
    public function showCategoryBySeoTitle($categorySeoTitle) {
        $category = $this->em->getRepository(Category::class)
            ->findOneBy(array('seoTitle' => $categorySeoTitle));
        return $category;
    }
    
    public function saveCategory(Category $category, SeoModel $seoModel) {
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $this->em->persist($category);
                
        //set seoTitle
        if(!$category->getSeoTitle()){
            $category->setSeoTitle($seoModel->createSeoTitle($category->getName(), \App\Entity\Category::class));
        }
                
        $this->em->flush();
        
    }
    
    public function deleteCategory(int $categoryId) {
        $articles = $this->em->getRepository(Article::class)
            ->findBy(
                array(
                    'category' => $categoryId,
                )
            );
        foreach ($articles as $article){
            $article->setCategory(null);
        }
        $this->em->flush();

        $category = $this->em->getRepository(Category::class)
                ->find($categoryId);
        $this->em->remove($category);
        $this->em->flush();
    }

    public function changeRanks(int $categoryId, int $categoryIdSoused) {
        $category = $this->em->getRepository(Category::class)
            ->find($categoryId);
        $categorySoused = $this->em->getRepository(Category::class)
            ->find($categoryIdSoused);
        $categoryRank = $category->getRankInMenu();
        $categoryRankSoused = $categorySoused->getRankInMenu();

        $category->setRankInMenu($categoryRankSoused);
        $categorySoused->setRankInMenu($categoryRank);

        $this->em->flush();
    }

    public function countArticlesInCategory(int $categoryId) {
        $articles = $this->em->getRepository(Article::class)
        ->findBy(
            array(
                'category' => $categoryId,
                'state' => 1
            )
        );  
        return count($articles);
    }
}

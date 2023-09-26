<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Article;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName("Lorem");
        $category->setSeoTitle("lorem");
        $category->setParent(null);
        $category->setRankInMenu(0);
        
        $manager->persist($category);
        
        $category2 = new Category();
        $category2->setName("Dolor");
        $category2->setSeoTitle("dolor");
        $category2->setParent(null);
        $category2->setRankInMenu(0);
        
        $manager->persist($category2);
        
        $article = new Article();
        $article->setName("Lorem Ipsum");
        $article->setPerex("What is Lorem Ipsum?");
        $article->setText("<b>Lorem Ipsum</b> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.");        
        $article->setSeoTitle("lorem-ipsum");
        $article->setState(1);
        $article->setDateOfCreated(new \DateTime());
        $article->setCategory($category);

        $manager->persist($article);
        
        $articleHpTopLeft = new Article();
        $articleHpTopLeft->setName("Iaculis");
        $articleHpTopLeft->setPerex("What is Lorem Ipsum?");
        $articleHpTopLeft->setText("<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ac scelerisque massa. Mauris ac bibendum diam, sed volutpat tortor. Sed rutrum at odio id mattis. Praesent euismod, elit ac tempor consequat.</p><p>Nisi sem condimentum arcu, ac volutpat lacus purus non mi. Vivamus at felis interdum, pharetra elit sit amet, feugiat neque. Morbi iaculis condimentum accumsan.</p>");
        $articleHpTopLeft->setSeoTitle("iaculis");
        $articleHpTopLeft->setState(0);
        $articleHpTopLeft->setDateOfCreated(new \DateTime());
        $articleHpTopLeft->setCategory($category);
        
        $manager->persist($articleHpTopLeft);
        
        $articleHpTopRight = new Article();
        $articleHpTopRight->setName("Curabitur");
        $articleHpTopRight->setPerex("What is Lorem Ipsum?");
        $articleHpTopRight->setText("<p>Maecenas semper nulla at iaculis vestibulum. Curabitur eu nunc tristique, commodo nisl non, tempus lacus. Nunc accumsan purus non erat rhoncus porta. Integer justo odio, vehicula et posuere non, feugiat ut dolor. Phasellus nec eros eget justo tincidunt ullamcorper.</p><p>Cras porta erat ut felis fringilla, vel ultricies felis accumsan. Maecenas odio turpis, molestie sed porta at, ultrices at nibh. Duis interdum vel massa sed faucibus.</p>");
        $articleHpTopRight->setSeoTitle("curabitur");
        $articleHpTopRight->setState(0);
        $articleHpTopRight->setDateOfCreated(new \DateTime());
        $articleHpTopRight->setCategory($category);
        
        $manager->persist($articleHpTopRight);
        
        $articleHpBottom = new Article();
        $articleHpBottom->setName("Lorem Ipsum");
        $articleHpBottom->setPerex("What is Lorem Ipsum?");
        $articleHpBottom->setText("<b>Lorem Ipsum</b> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.");
        $articleHpBottom->setSeoTitle("lorem-ipsum-hp");
        $articleHpBottom->setState(0);
        $articleHpBottom->setDateOfCreated(new \DateTime());
        $articleHpBottom->setCategory($category);
        
        $manager->persist($articleHpBottom);
        
        $manager->flush();
    }
}

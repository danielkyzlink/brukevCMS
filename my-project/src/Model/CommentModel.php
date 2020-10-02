<?php
namespace App\Model;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;



class CommentModel
{
    public $em;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->em = $entityManager;
    }
    
    public function showComments(Article $article) {
        $comments = $this->em
        ->getRepository(Comment::class)
        ->findBy(
            array(
                'article' => $article,
                'state' => Comment::STATE_PUBLIKOVANO,
            ),
            array('date_of_comment' => 'DESC')
        );
        return $comments;
    }
    
    public function showAllComments() {
        $comments = $this->em
        ->getRepository(Comment::class)
        ->findBy(
            array(),
            array('date_of_comment' => 'DESC')
        );
        return $comments;
    }
    
    public function addComment(Comment $comment, Article $article) {
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $this->em->persist($comment);

        //set date
        $comment->setDateOfComment(new \DateTime())
        ->setParent(null)
        ->setState(Comment::STATE_PUBLIKOVANO)
        ->setArticle($article);

                
        $this->em->flush();
        
    }
    
    public function commentToTrash(Comment $comment) {
        $this->em->persist($comment);
        $comment->setState(Comment::STATE_SMAZANO);
        $this->em->flush();
    }
    
    public function commentToPublic(Comment $comment) {
        $this->em->persist($comment);
        $comment->setState(Comment::STATE_PUBLIKOVANO);
        $this->em->flush();
    }
}
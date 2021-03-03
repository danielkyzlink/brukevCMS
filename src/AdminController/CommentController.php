<?php
namespace App\AdminController;

use App\Model\CommentModel;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends AbstractController
{
    /**
     * @Route("admin/comment/listComments", name="listComments")
     */
    public function listComments(CommentModel $commentModel)
    {
        $comments = $commentModel->showAllComments();
        // vykresleni sablony
        return $this->render('admin/comment/listComments.html.twig', [
            'comments' => $comments,
        ]);
    }
    
    /**
     * @Route("/admin/comment/commentToTrash/{comment}", name="commentToTrash")
     */
    public function commentToTrash(Comment $comment, CommentModel $commentModel, Request $request)
    {
        $commentModel->commentToTrash($comment);
        return $this->redirectToRoute('listComments');
    }
    
    /**
     * @Route("/admin/comment/commentToPublic/{comment}", name="commentToPublic")
     */
    public function commentToPublic(Comment $comment, CommentModel $commentModel, Request $request)
    {
        $commentModel->commentToPublic($comment);
        return $this->redirectToRoute('listComments');
    }
}

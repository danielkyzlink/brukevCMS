<?php
namespace App\AdminController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\User;
use App\Model\UserModel;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/user/listUsers", name="listUsers")
     */
    public function listUsers(UserModel $userModel){
        
        $users = $userModel->showAllUsers(); 
        
        // vykresleni sablony
        return $this->render('admin/users/listUsers.html.twig', [
            'users' => $users,
        ]);
    }
    
    /**
     * @Route("/admin/user/userToBlocked/{user}", name="userToBlocked")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Post not found")
     */
    public function userToBlocked(User $user, UserModel $userModel){
        
        $userModel->userToBlocked($user);
        
        return $this->redirectToRoute('listUsers');
    }
    
    /**
     * @Route("/admin/user/userUnblocked/{user}", name="userUnblocked")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Post not found")
     */
    public function userUnblocked(User $user, UserModel $userModel){
        
        $userModel->userUnblocked($user);
        
        return $this->redirectToRoute('listUsers');
    }
}
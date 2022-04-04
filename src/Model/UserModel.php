<?php
namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;


class UserModel
{
    public $em;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->em = $entityManager;
    }
    
    public function showAllUsers() {
        $users = $this->em
        ->getRepository(User::class)
        ->findAll();
                        
        return $users;
    }
    
    public function userToBlocked(User $user) {
        $this->em->persist($user);
        $user->setState(User::STATE_USER_BLOCKED);
        
        $this->em->flush();
    }
    
    public function userUnblocked(User $user) {
        $this->em->persist($user);
        $user->setState(User::STATE_USER_OK);
        
        $this->em->flush();
    }

    public function getUserById(int $userId) {
        $user = $this->em->getReference(User::class, $userId);
        return $user;
    }
}
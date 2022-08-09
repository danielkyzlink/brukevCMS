<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserFixures extends Fixture
{
    
    private $passwordEncoder;
    
    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $user = new User();
        // $manager->persist($product);

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'the_new_password'
         ));
        
        // $manager->flush();
    }
}

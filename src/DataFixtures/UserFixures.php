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
        
        $user->setEmail("testadmin@brukev.cz");
        $user->setName("testadmin");
        $user->setSurname("Novak");
        $user->setNickname("boss");
        $user->setState(1);
        $user->setRegistrationDate(new \DateTime());
        $user->setLastLoginDate(new \DateTime());
        $user->setPassword($this->passwordEncoder->hashPassword(
            $user,
            '123456'
         ));
        
        $manager->persist($user);
        $manager->flush();
    }
}

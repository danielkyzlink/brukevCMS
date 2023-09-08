<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\WebRegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\UserModel;
use App\Form\EditUserFormType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;

class RegistrationController extends AbstractController
{
    #[Route("/admin/register", name: "app_register")]
    public function register(Request $request, UserPasswordHasherInterface $passwordEncoder, LoginFormAuthenticator $authenticator, ManagerRegistry $doctrine): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRegistrationDate(new \DateTime());
            $user->setLastLoginDate(new \DateTime());
            $user->setState(User::STATE_USER_OK);
            
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            
            /*
            //nasledujici kod rovnou noveho uzivatele prihlasi
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
            */
        }

        return $this->render('admin/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route("/app/register", name: "web_register")]
    public function webRegister(Request $request, UserPasswordHasherInterface $passwordEncoder, LoginFormAuthenticator $authenticator, ManagerRegistry $doctrine): Response
    {
        $user = new User();
        $form = $this->createForm(WebRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRegistrationDate(new \DateTime());
            $user->setLastLoginDate(new \DateTime());
            $user->setState(User::STATE_USER_OK);

            $user->setRoles(['WEB_USER']);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            $this->addFlash('success', 'Registrace proběhla úspěšně. Můžete se přihlásit.');


        }

        return $this->render('frontend/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route("/admin/user/editUser/{userId}", name: "editUser")]
    public function editUser(int $userId = null, Request $request, UserPasswordHasherInterface $passwordEncoder, LoginFormAuthenticator $authenticator, UserModel $userModel, ManagerRegistry $doctrine): Response
    {
        $user = $userModel->getUserById($userId);
        $form = $this->createForm(EditUserFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }
        
        return $this->render('admin/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

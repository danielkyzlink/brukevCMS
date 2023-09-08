<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route("/admin/login/{webLogin}", name: "app_login", defaults: ["webLogin" => false])]
    public function login(AuthenticationUtils $authenticationUtils, $webLogin = false): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if($webLogin){
            return $this->render('admin/security/webLogin.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        }else{
            return $this->render('admin/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        }
    }

    #[Route("/admin/logout", name: "app_logout")]
    public function logout()
    {
        // controller can be blank: it will never be executed!
        //throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}

<?php
namespace App\Controller;

use App\Entity\LostPassword;
use App\Entity\User;
use App\Model\LostPasswordModel;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class LostPasswordController extends AbstractController
{
    #[Route("/app/lostPassword", name: "lostPassword")]
    public function showLostPassword(Request $request, ManagerRegistry $doctrine, LostPasswordModel $lostPasswordModel, MailerInterface $mailer)
    {
        $lostPassword = new LostPassword();
        $form = $this->createFormBuilder($lostPassword)
            ->add('email', TextType::class, [
                'label' => 'email',
            ])
            ->add('submit', SubmitType::class, [
               'label' => 'Odeslat link pro změnu hesla'
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $lostPassword = $form->getData();
            $user = $doctrine->getRepository(User::class)
                ->findOneBy(array(
                    'email' => $lostPassword->getEmail()
                ));
            if (!$user) {
                $this->addFlash("warning", "Uživatel s tímto emailem není registrovaný");
                return $this->redirectToRoute('lostPassword');
            }

            if($lostPasswordModel->countRecentRequests($user) > 5){
                $this->addFlash("warning", "Za poslední hodinu již máte 5 žádnotí o obnovu hesla.");
                return $this->redirectToRoute('lostPassword');
            }

            $lostPasswordModel->addLostPassword($lostPassword, $user, $mailer);

            $this->addFlash('success', 'Byl vám odeslán email.');
        }

        return $this->render("/frontend/lostPassword/lostPassword.html.twig", [
            'lostPasswordForm' => $form->createView(),
        ]);
    }

    #[Route("/app/renewPassword/{hash}", name: "renewPassword")]
    public function renewPassword($hash, Request $request, LostPasswordModel $lostPasswordModel, UserPasswordHasherInterface $passwordEncoder)
    {
        if(!$lostPasswordModel->isValid($hash)){
            $this->addFlash('error', 'Nevalidní hash.');
            return $this->render("/frontend/lostPassword/renewPassword.html.twig");
        }

        $form = $this->createFormBuilder()
            ->add('email')
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Změnit heslo'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $lostPasswordModel->getUserByHash($hash);
            $passwordHash = $passwordEncoder->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
            );
            $lostPasswordModel->setNewPassword($user, $passwordHash);
            $lostPasswordModel->setDateRenewed($hash);
        }

        return $this->render("/frontend/lostPassword/renewPassword.html.twig", [
            'renewPasswordForm' => $form->createView(),
        ]);
    }
}
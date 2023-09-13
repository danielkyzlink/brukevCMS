<?php
namespace App\Model;

use App\Entity\LostPassword;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Request;

class LostPasswordModel
{
    public $em;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->em = $entityManager;
    }

    public function addLostPassword(LostPassword $lostPassword, User $user, MailerInterface $mailer, Request $request) {
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $this->em->persist($lostPassword);
        $hash = bin2hex(random_bytes(32));

        if ($this->getUserByHash($hash)){
            throw new NotFoundHttpException(
                'Duplicitní HASH. Prosím vygenerujte znovu.'
            );
        }

        $lostPassword->setDateOfRequest(new \DateTime())
            ->setHash($hash)
            ->setUser($user);

        $this->em->flush();

        $email = (new Email())
            ->from('info@brukev.cz')
            ->to($lostPassword->getEmail())
            ->subject('Nastavte si nové heslo')
            ->text($request->getHost() . '/app/renewPassword/' . $hash);
        $mailer->send($email);
    }

    public function getUserByHash($hash): ?User
    {
        $lostPassword = $this->em->getRepository(LostPassword::class)
            ->findOneBy(array(
                'hash' => $hash
            ));
        if ($lostPassword) {
            return $lostPassword->getUser();
        }else{
            return null;
        }
    }

    public function setNewPassword($user, string $passwordHash):void{
        $this->em->persist($user);
        $user->setPassword($passwordHash);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function setDateRenewed($hash):void{
        $lostPassword = $this->em->getRepository(LostPassword::class)
            ->findOneBy(array(
                'hash' => $hash
            ));
        $lostPassword->setDateRenewed(new \DateTime());
        $this->em->persist($lostPassword);
        $this->em->flush();
    }

    public function isValid($hash):bool{
        $lostPassword = $this->em->getRepository(LostPassword::class)
            ->findOneBy(array(
                'hash' => $hash
            ));
        if($lostPassword->getDateRenewed()){
            return false;
        }

        $requestTime = $lostPassword->getDateOfRequest();
        $currentTime = new \DateTime();
        $interval = new \DateInterval('PT1H');
        $currentTime->sub($interval);
        if($requestTime < $currentTime){
            return false;
        }
        return true;
    }

    public function countRecentRequests($user): int{
        $currentTime = new \DateTime();
        $oneHourAgo = $currentTime->sub(new \DateInterval('PT1H'));
        $lostPassword = $this->em->getRepository(LostPassword::class)
            ->findBy(array(
                'user' => $user,
                /*'dateOfRequest' => ['>=', $oneHourAgo]*/
            ),
            array(
                'dateOfRequest' => 'DESC'
            )
            );

        $count = 0;
        foreach ($lostPassword as $item) {
            if ($item->getDateOfRequest() >= $oneHourAgo) {
                $count++;
            }
        }
        return $count;
    }
}
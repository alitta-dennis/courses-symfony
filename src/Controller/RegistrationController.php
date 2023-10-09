<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;



// /**
//  * @OA\Response(
//  *      response=200,
//  *      description="User Registered"
//  *      @Model(type=user::class)
//  * @OA\Tag(name="register")
//  * @Route("/api/register"),name="app_register",methods={"POST"})
//  */
class RegistrationController extends AbstractController{

    #[Route('/api/register',name:'app_register',methods:['POST'])]
    public function register(Request $request, EntityManagerInterface $em, MailerInterface $mailer):JsonResponse
    {   
        $data=json_decode($request->getContent(),true);
        $user=new User();
        
        $user->setEmail($data['email']);
        $hashpwd=password_hash($data['password'],PASSWORD_BCRYPT);
        $user->setPassword($hashpwd);
        //  $user->setUsername($data['email']);
        
            $em->persist($user);
            $em->flush();
            $this->sendMail($user,$mailer);
            return new JsonResponse(['message'=>'User Registered'],200);

    }

    private function sendMail(User $user, MailerInterface $mailer)
    {
        $email=(new Email())
                ->from('remotedesk@gmail.com')
                ->to($user->getEmail())
                ->subject('Welcome To Remote Desk!!')
                ->html('<h1>Welcome to Remote Desk!!</h1>
                        <h3>We hope you have pleasent learning experience with us!!</br>
                        Happy Learning!!</h3>');

        $mailer->send($email);
    }
}
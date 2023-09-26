<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
  
class RegistrationController extends AbstractController{

    #[Route('/register',name:'app_register',methods:['POST'])]
    public function register(Request $request, EntityManagerInterface $em):JsonResponse
    {   
        $data=json_decode($request->getContent(),true);
        $user=new User();
        
        $user->setEmail($data['email']);
        $hashpwd=password_hash($data['password'],PASSWORD_BCRYPT);
        $user->setPassword($hashpwd);
        //  $user->setUsername($data['email']);
        
            $em->persist($user);
            $em->flush();
            return new JsonResponse(['message'=>'User Registered'],201);

    }
}
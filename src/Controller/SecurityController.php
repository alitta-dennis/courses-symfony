<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; // Import the new interface
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class SecurityController extends AbstractController
{
    private $em;
    private $jwt;

    public function __construct(EntityManagerInterface $em, JWTTokenManagerInterface $jwt)
    {
        $this->em = $em;
        $this->jwt=$jwt;
    }

    /**
     * @Route("/api/login", name="app_login", methods={"POST"})
     */
    public function login(Request $request, UserPasswordHasherInterface $pwd, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $userRepository = $this->em->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $data['email']]);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        if (!$pwd->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['message' => 'Invalid password'], 401);
        }

        //Generate token
        $token=$this->jwt->create($user);

        //Store token in response
        $response=new JsonResponse(['message'=>'Login Successful'],200);
        $response->headers->set('Authorization', 'Bearer ' . $token);
        return $response;
        // $password = $data['password']; // Get the password from the request data
        // $roles = $user->getRoles(); // Convert the single role to an array
        // $token = new UsernamePasswordToken($user, $password, $roles);
        // $tokenStorage->setToken($token);


        

        // return new JsonResponse(['message' => 'Login successful'], 200);
    }


    /**
     * @Route("/api/logout", name="logout", methods={"POST"})
     */
    public function logout(): JsonResponse
    {
        // Symfony's security system will automatically handle logout, so you don't need to implement a logout action.
        return new JsonResponse(['message' => 'Logged out'], 200);
    }
}
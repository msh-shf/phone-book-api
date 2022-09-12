<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class AuthController extends BaseController
{
    #[Route('/register', name: 'register', methods: ["POST"])]
    public function index(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {          
        $entityManager = $doctrine->getManager();
        $postData = json_decode($request->getContent());

        $email = $postData->email;
        $plaintextPassword = $postData->password;
  
        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $user->setEmail($email);
        $user->setUsername($email);

        $entityManager->persist($user);
        $entityManager->flush();
  
        return $this->successJsonResponse($user->getId());
    }
}

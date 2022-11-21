<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    #[Route('/api/register', name: 'app_register_user')]
    public function registration(Request $request,
                                 UserPasswordHasherInterface $passwordHasher,
                                 ManagerRegistry $doctrine): JsonResponse
    {
        $user = new User();
        $userInputs = json_decode($request->getContent(),true);
        $plaintextPassword = $userInputs['password'];
        $email = $userInputs['email'];

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setEmail($email);
        $user->setPassword($hashedPassword);

        $entityManager = $doctrine->getManager();

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            "hashedPassword" => $hashedPassword
        ]);
    }
}

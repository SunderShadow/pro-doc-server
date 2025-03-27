<?php

namespace App\Controller\Auth;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class EmailVerificationController extends AbstractController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
    )
    {
    }

    #[Route('/account/register/by/email', methods: ['POST'], format: 'json')]
    public function register(Request $request)
    {
        $data = json_decode($request->getContent());
        $username = $data->username;
        $password = $data->password;

        if ($this->userRepository->findOneBy(['email' => $username])) {
            return $this->json([
                'message' => 'Email exists'
            ]);
        }

        $user = new User();
        $password = $this->passwordHasher->hashPassword($user, $password);

        $user->setPassword($password);
        $user->setEmail($username);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'success'
        ]);
    }
}
<?php

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class CheckJWTController extends AbstractController
{
    #[Route('/account/jwt/check', format: 'json', methods: ['GET'])]
    public function check()
    {
        return $this->json([
            'message' => 'JWT is valid'
        ]);
    }
}
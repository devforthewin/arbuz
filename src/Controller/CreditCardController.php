<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CreditCardController extends AbstractController
{
    #[Route('/credit-card', name: 'app_credit_card')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'hello',
            'rand' =>rand()
        ]);
    }
}

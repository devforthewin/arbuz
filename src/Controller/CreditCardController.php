<?php

namespace App\Controller;

use App\Entity\CreditCard;
use App\Repository\CreditCardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreditCardController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CreditCardRepository $creditCardRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        CreditCardRepository $creditCardRepository
    ) {
        $this->entityManager        = $entityManager;
        $this->creditCardRepository = $creditCardRepository;
    }

    #[Route('/credit-card', name: 'get_cret', methods: 'GET')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'hxxxello',
            'rand'    => rand(),
        ]);
    }

    #[Route('/credit-card', name: 'post_cc', methods: 'POST')]
    public function new(Request $request): JsonResponse
    {
        // получаем данные
        $number = $request->get('number');
        $pin    = $request->get('pin');

        // создаем карту
        $cc = new CreditCard();
        $cc->setNumber($number);
        $cc->setPin($pin);

        // сохраняем карту
        $this->entityManager->persist($cc);
        $this->entityManager->flush();

        // берем все карты
        $allCards = $this->creditCardRepository->findAll();

        // возвращаем в ответ все карты
        $dataForUser = [];
        foreach ($allCards as $card) {
            $dataForUser[] = [
                'id'     => $card->getId(),
                'pin'    => $card->getPin(),
                'number' => $card->getNumber(),
            ];
        }

        return $this->json($dataForUser);
    }
}

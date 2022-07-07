<?php

namespace App\Controller;

use App\Entity\CreditCard;
use App\Repository\CreditCardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    #[Route('/credit-card/{id}', name: 'get_one_cred', methods: 'GET')]
    public function getOne(Request $request): Response
    {
        /// securty->check($user
        $id = $request->get('id'); // 2

        $card = $this->creditCardRepository->find($id); //null

        if(!$card){
            $response = new Response();
            return $response->setStatusCode(404);
        }

        return $this->json([
            'id'     => $card->getId(), // null->getId
            'pin'    => $card->getPin(),
            'number' => $card->getNumber(),
        ]);
    }


    #[Route('/credit-card', name: 'get_cret', methods: 'GET')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'hxxxello',
            'rand'    => rand(),
        ]);
    }


    #[Route('/credit-card/{id}', name: 'delete_creditcard', methods: 'DELETE')]
    public function delete(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $creditCard = $this->creditCardRepository->find($id);

        if ($creditCard) {
            $this->entityManager->remove($creditCard);
            $this->entityManager->flush();
        }

        return $this->json([]);
    }

    #[Route('/credit-card', name: 'post_cc', methods: 'POST')]
    public function new(Request $request, ValidatorInterface $validator): Response
    {
        // получаем данные
        $number = $request->get('number');
        $pin    = $request->get('pin');

        // создаем карту
        $cc = new CreditCard();
        $cc->setNumber($number);
        $cc->setPin($pin);


        $errors = $validator->validate($cc);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            $response = new Response();
            $response->setContent($errorsString);
            return $response->setStatusCode(400);
        }

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

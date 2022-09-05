<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\CreditCard;
use App\Entity\Publisher;
use App\Repository\BookRepository;
use App\Service\KnigaService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    private BookRepository $bookRepository;
    private EntityManagerInterface $entityManager;
    private KnigaService $knigaService;

    public function __construct(
        BookRepository $bookRepository,
        EntityManagerInterface $entityManager,
        KnigaService $knigaService
    ) {
        $this->bookRepository = $bookRepository;
        $this->entityManager  = $entityManager;
        $this->knigaService   = $knigaService;
    }

    #[Route('/book/create', name: 'cr_book')]
    public function create(): JsonResponse
    {
        $this->entityManager->beginTransaction();
        try {
            $publisher = new Publisher();
            $publisher->setName('Publisher'.rand());
            $publisher->setAddress(rand());

            $author = new Author();
            $author->setName('Error');

            $book = new Book();
            $book->setName('book'.rand());
            $book->addAuthor($author);
            $book->setPublisher($publisher);

            $this->entityManager->persist($author);
            $this->entityManager->persist($book);
            $this->entityManager->persist($publisher);
            $this->entityManager->flush();
            echo "my sozdali knigi";
            throw new \Exception();

            $credcard = new CreditCard();
            $credcard->setPin(123);
            $credcard->setNumber('11111111111111111');
            $this->entityManager->persist($credcard);
            $this->entityManager->flush();
            echo "my sozdali cc";


            $this->entityManager->commit();
        } catch (\Throwable $e) {
            echo "Rollback";
            $this->entityManager->rollback();
        }

        return $this->json($this->knigaService->getBooks('Error'));
    }

    #[Route('/book/list', name: 'list_book')]
    public function list(): JsonResponse
    {
        $result = $this->knigaService->getBooks('Mihail');

        return $this->json($result);
    }

    #[Route('/new', methods: ['GET'], name: 'app_book')]
    public function index(): JsonResponse
    {
        $this->entityManager->beginTransaction();

        try {
            $publisher = new Publisher();
            $publisher->setName('Publisher'.rand());
            $publisher->setAddress(rand());

            $author = new Author();
            $author->setName('Navalny '.rand());

            $book = new Book();
            $book->setName('book'.rand());
            $book->addAuthor($author);
            $book->setPublisher($publisher);

            $this->entityManager->persist($publisher);
            $this->entityManager->persist($author);
            $this->entityManager->persist($book);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
        }


        /**
         *    Throwable
         *        Exception
         *          InvalidArgumentException
         */


        return $this->json([
            'ok',
        ]);
    }


}

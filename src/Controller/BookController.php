<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Publisher;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    private BookRepository $bookRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(BookRepository $bookRepository, EntityManagerInterface $entityManager){
        $this->bookRepository = $bookRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/book/list', name: 'list_book')]
    public function list():JsonResponse
    {
        $books = $this->bookRepository->findAllByAuthor('Mihail');

        $results = [];
        foreach ($books as $book) {
            $authors = $book->getAuthor();
            $results[] = [
                'name' => $book->getName(),
                'author'=> $authors->get(0)->getName(),
                'publisher' => $book->getPublisher()->getName()
            ];
        }

        return $this->json($results);
    }

    #[Route('/new', name: 'app_book')]
    public function index(): JsonResponse
    {
        $publisher = new Publisher();
        $publisher->setName('Publisher'. rand());
        $publisher->setAddress(rand());

        $author = new Author();
        $author->setName('Navalny '.rand());

        $book = new Book();
        $book->setName('book'. rand());
        $book->addAuthor($author);
        $book->setPublisher($publisher);

        $this->entityManager->persist($publisher);
        $this->entityManager->persist($author);
        $this->entityManager->persist($book);
        $this->entityManager->flush();


        return $this->json([
           'ok'
        ]);
    }
}

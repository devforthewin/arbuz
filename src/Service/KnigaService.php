<?php

namespace App\Service;


use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;

class KnigaService
{
    private BookRepository $bookRepository;
    private \Symfony\Contracts\Cache\CacheInterface $cache;

    public function __construct(
        BookRepository $bookRepository,
        EntityManagerInterface $entityManager,
        \Symfony\Contracts\Cache\CacheInterface $cache

    ){
        $this->bookRepository = $bookRepository;
        $this->entityManager = $entityManager;
        $this->cache = $cache;
    }

    public function getBooks(string $author)
    {
        $start = microtime(true);
        $result = $this->cache->get("list_book_$author", function (ItemInterface $item) use ($author) {
            $item->expiresAfter(60 * 60 * 24);
//            $item->tag(['books','list']);

            $books = $this->bookRepository->findAllByAuthor($author);

            $results = [];
            foreach ($books as $book) {
                $authors = $book->getAuthor();
                $results[] = [
                    'name' => $book->getName(),
                    'author'=> $authors->get(0)->getName(),
                    'publisher' => $book->getPublisher()->getName()
                ];
            }

            return $results;
        });


        return $result;
    }
}

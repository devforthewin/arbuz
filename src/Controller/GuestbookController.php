<?php

namespace App\Controller;

use App\Entity\Guestbook;
use App\Repository\GuestbookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GuestbookController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private GuestbookRepository $guestbookRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        GuestbookRepository $guestbookRepository
    ) {
        $this->entityManager = $entityManager;
        $this->guestbookRepository = $guestbookRepository;
    }

    #[Route('/guestbook', name: 'get_app_guestbook', methods: 'GET')]
    public function list(Request $request): JsonResponse
    {
        $entries = $this->guestbookRepository->findAll();

        $array = [];

        $tz = new \DateTimeZone('Europe/Moscow'); // UTC  GMT

        foreach ($entries as $entry) {
            $array[] = [
                'id' => $entry->getId(),
                'name' => $entry->getName(),
                'email' => $entry->getEmail(),
                'message' => $entry->getMessage(),
                'created_at' => $entry->getCreatedAt()->setTimezone($tz)->format('Y.m.d H:i:s'),
            ];
        }

        return $this->json($array);
    }
    #[Route('/guestbook', name: 'post_app_guestbook', methods: 'POST')]
    public function add(Request $request): Response
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $message = $request->get('message');

        $guestbook = new Guestbook();
        $guestbook->setName($name);
        $guestbook->setEmail($email);
        $guestbook->setMessage($message);
        $guestbook->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($guestbook);
        $this->entityManager->flush();

        return $this->redirect('/test.php');
    }
}

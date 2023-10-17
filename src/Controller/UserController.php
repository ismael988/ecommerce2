<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user')]
class UserController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/', name: 'api_user_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                // Ajoutez d'autres propriétés de votre entité User ici
            ];
        }

        return $this->json($data);
    }

    #[Route('/{id}', name: 'api_user_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return $this->json(['message' => 'User not found']);
        }

        $data = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            // Ajoutez d'autres propriétés de votre entité User ici
        ];

        return $this->json($data);
    }
}

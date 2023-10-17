<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Form\CartType;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/cart')]
class CartController extends AbstractController
{
    private $entityManager;
    private $cartRepository;

    public function __construct(EntityManagerInterface $entityManager, CartRepository $cartRepository)
    {
        $this->entityManager = $entityManager;
        $this->cartRepository = $cartRepository;
    }

    #[Route('/', name: 'api_cart_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $carts = $this->cartRepository->findAll();
        $data = [];

        foreach ($carts as $cart) {
            $data[] = [
                'id' => $cart->getId(),
                'name_product' => $cart->getNameProduct(),
                // Ajoutez d'autres propriétés de votre entité Cart ici
            ];
        }

        return $this->json($data);
    }

    #[Route('/{id}', name: 'api_cart_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $cart = $this->cartRepository->find($id);

        if (!$cart) {
            return $this->json(['message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $cart->getId(),
            'name_product' => $cart->getNameProduct(),
            // Ajoutez d'autres propriétés de votre entité Cart ici
        ];

        return $this->json($data);
    }

    #[Route('/{id}', name: 'api_cart_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $cart = $this->cartRepository->find($id);

        if (!$cart) {
            return $this->json(['message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($cart);
        $this->entityManager->flush();

        return $this->json(['message' => 'Cart deleted successfully']);
    }
}

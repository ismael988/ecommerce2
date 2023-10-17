<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/product')]
class ProductController extends AbstractController
{
    private $productRepository;
    private $entityManager;

    public function __construct(ProductRepository $productRepository, EntityManagerInterface $entityManager)
    {
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'api_product_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $products = $this->productRepository->findAll();
        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                // Ajoutez d'autres propriétés de votre entité Product ici
            ];
        }

        return $this->json($data);
    }

    #[Route('/{id}', name: 'api_product_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return $this->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            // Ajoutez d'autres propriétés de votre entité Product ici
        ];

        return $this->json($data);
    }

    #[Route('/{id}', name: 'api_product_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return $this->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return $this->json(['message' => 'Product deleted successfully']);
    }
}

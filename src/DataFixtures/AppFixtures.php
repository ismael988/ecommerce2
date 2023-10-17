<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\Commande;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création de 6 utilisateurs
        $noms = ['Dupont', 'Martin', 'Dubois', 'Lefevre', 'Leroy', 'Moreau'];
        $emails = ['user1@example.com', 'user2@example.com', 'user3@example.com', 'user4@example.com', 'user5@example.com', 'user6@example.com'];

        for ($i = 0; $i < 6; $i++) {
            $user = new User();
            $user->setEmail($emails[$i]);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password')); // Mot de passe par défaut : 'password'
            $user->setName($noms[$i]);
            $user->setSurname('Prénom ' . ($i + 1));
            $manager->persist($user);
        }

        // Création de 10 produits
        for ($i = 1; $i <= 10; $i++) {
            $product = new Product();
            $product->setName('Produit ' . $i);
            $product->setPrice($i * 10);// Prix aléatoire
            $product->setImage('chemin/vers/image' . $i . '.jpg');
            $product->setTupe('Type de produit ' . $i); // Définir une valeur pour 'type'
            $manager->persist($product);
        }

        // Création de 5 cartes pour chaque utilisateur
        $users = $manager->getRepository(User::class)->findAll();
        $products = $manager->getRepository(Product::class)->findAll();

        foreach ($users as $user) {
            for ($i = 1; $i <= 5; $i++) {
                $cart = new Cart();
                $cart->setUser($user);
                $cart->setProduct($products[array_rand($products)]);
              
                $cart->setType('Type de produit ' . $i); // Définir une valeur pour 'type'
                $manager->persist($cart);
            }
        }
        

        // Création de 3 commandes pour chaque utilisateur
        foreach ($users as $user) {
            for ($i = 1; $i <= 3; $i++) {
                $commande = new Commande();
                $commande->setTotale(rand(50, 500)); // Montant total aléatoire
                $commande->setDate(new DateTime());
                $manager->persist($commande);
            }
        }

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\Utilisateur;
use App\Entity\Commande;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
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
        $faker = Factory::create('fr_FR');

        // Création des catégories de chaussures
        $categories = [
            'Sneakers' => 'Les meilleures sneakers pour votre style',
            'Baskets' => 'Des baskets confortables pour tous les jours',
            'Chaussures de sport' => 'Performance et style pour vos activités sportives',
            'Chaussures de ville' => 'Élégance et confort pour vos sorties',
            'Sandales' => 'Légèreté et style pour l\'été'
        ];

        $categorieObjects = [];
        foreach ($categories as $nom => $description) {
            $categorie = new Categorie();
            $categorie->setNom($nom);
            $categorie->setDescription($description);
            $manager->persist($categorie);
            $categorieObjects[] = $categorie;
        }

        // Création des marques de chaussures
        $marques = ['Nike', 'Adidas', 'Puma', 'New Balance', 'Reebok', 'Asics', 'Converse', 'Vans'];
        
        // Images disponibles
        $images = [
            'chaussure1.jpg',
            'chaussure2.jpg',
            'chaussure3.jpg',
            'chaussure4.jpg',
            'chaussure5.jpg',
            'chaussure6.jpg',
            'chaussure7.jpg',
            'chaussure8.jpg',
            'chaussure9.jpg',
            'chaussure10.jpg'
        ];

        // Création de 25 produits
        for ($i = 0; $i < 25; $i++) {
            $produit = new Produit();
            $produit->setNom($faker->randomElement($marques) . ' ' . $faker->words(3, true));
            $produit->setDescription($faker->paragraph());
            $produit->setPrix($faker->randomFloat(2, 49.99, 199.99));
            $produit->setStock($faker->numberBetween(0, 100));
            // Utilisation cyclique des images
            $produit->setImage($images[$i % count($images)]);
            $produit->setCategorie($faker->randomElement($categorieObjects));
            
            // Ajout des détails spécifiques aux chaussures
            $details = [
                'marque' => $faker->randomElement($marques),
                'modele' => $faker->word(),
                'couleur' => $faker->colorName(),
                'matiere' => $faker->randomElement(['Cuir', 'Tissu', 'Synthétique', 'Mesh']),
                'pointures' => implode(', ', $faker->randomElements(['36', '37', '38', '39', '40', '41', '42', '43', '44', '45'], 5)),
                'type' => $faker->randomElement(['Homme', 'Femme', 'Unisexe']),
                'style' => $faker->randomElement(['Casual', 'Sport', 'Classique', 'Urbain'])
            ];
            $produit->setDetails($details);
            
            $manager->persist($produit);
        }

        // Création des utilisateurs
        for ($i = 0; $i < 10; $i++) {
            $utilisateur = new Utilisateur();
            $utilisateur->setEmail($faker->email());
            $utilisateur->setNom($faker->lastName());
            $utilisateur->setPrenom($faker->firstName());
            $utilisateur->setAdresse($faker->address());
            $utilisateur->setTelephone($faker->phoneNumber());
            
            // Définir le mot de passe
            $plaintextPassword = 'password123';
            $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, $plaintextPassword);
            $utilisateur->setPassword($hashedPassword);
            
            // Attribuer le rôle
            $utilisateur->setRoles($i === 0 ? ['ROLE_ADMIN'] : ['ROLE_USER']);
            
            $manager->persist($utilisateur);
            
            // Création des commandes pour chaque utilisateur
            for ($j = 0; $j < $faker->numberBetween(0, 3); $j++) {
                $commande = new Commande();
                $commande->setUtilisateur($utilisateur);
                $commande->setDateCommande($faker->dateTimeBetween('-6 months', 'now'));
                $commande->setStatut($faker->randomElement(['En attente', 'En cours', 'Livrée']));
                $commande->setMontantTotal($faker->randomFloat(2, 50, 500));
                
                $manager->persist($commande);
            }
        }

        $manager->flush();
    }
}

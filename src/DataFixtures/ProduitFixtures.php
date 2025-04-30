<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProduitFixtures extends Fixture implements DependentFixtureInterface
{
    private $imagesChaussures = [
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

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Récupérer toutes les catégories
        $categories = $manager->getRepository(Categorie::class)->findAll();

        // Créer 50 produits
        for ($i = 0; $i < 50; $i++) {
            $produit = new Produit();
            $produit->setNom($faker->words(3, true));
            $produit->setDescription($faker->paragraph(3));
            $produit->setPrix($faker->randomFloat(2, 20, 200));
            $produit->setStock($faker->numberBetween(0, 100));
            
            // Utiliser une image de chaussure aléatoire
            $imageIndex = $faker->numberBetween(0, count($this->imagesChaussures) - 1);
            $produit->setImage($this->imagesChaussures[$imageIndex]);
            
            // Associer une catégorie aléatoire
            $categorie = $faker->randomElement($categories);
            $produit->setCategorie($categorie);

            $manager->persist($produit);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategorieFixtures::class,
        ];
    }
} 
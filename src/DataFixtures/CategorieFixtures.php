<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    private $imagesCategories = [
        'baskets.jpg',
        'sport.jpg',
        'ville.jpg',
        'bottes.jpg',
        'sandales.jpg',
        'mocassins.jpg',
        'escarpins.jpg',
        'randonnee.jpg'
    ];

    public function load(ObjectManager $manager): void
    {
        $categories = [
            'Baskets',
            'Chaussures de sport',
            'Chaussures de ville',
            'Bottes',
            'Sandales',
            'Mocassins',
            'Escarpins',
            'Chaussures de randonnée'
        ];

        foreach ($categories as $index => $categoryName) {
            $category = new Categorie();
            $category->setNom($categoryName);
            $category->setDescription("Description pour " . $categoryName);
            $category->setImage($this->imagesCategories[$index]);
            $manager->persist($category);
        }

        $manager->flush();
    }
} 
<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $produitRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_show')]
    public function show(int $id, ProduitRepository $produitRepository): Response
    {
        $product = $produitRepository->find($id);
        
        if (!$product) {
            throw $this->createNotFoundException('Le produit demandé n\'existe pas');
        }

        // Get related products from the same category
        $relatedProducts = $produitRepository->findBy(
            ['categorie' => $product->getCategorie()],
            ['id' => 'DESC'],
            4
        );

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
} 
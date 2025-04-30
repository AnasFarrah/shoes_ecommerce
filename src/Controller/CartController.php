<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart_items' => [], // We'll populate this later
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add(int $id): Response
    {
        // Add to cart logic will be implemented later
        $this->addFlash('success', 'Produit ajouté au panier');
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove(int $id): Response
    {
        // Remove from cart logic will be implemented later
        $this->addFlash('success', 'Produit retiré du panier');
        return $this->redirectToRoute('app_cart');
    }
} 
<?php

namespace App\Command;

use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:cleanup-products',
    description: 'Supprime les produits excédentaires, ne garde que les 25 plus récents',
)]
class CleanupProductsCommand extends Command
{
    private $produitRepository;
    private $entityManager;

    public function __construct(ProduitRepository $produitRepository, EntityManagerInterface $entityManager)
    {
        $this->produitRepository = $produitRepository;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Récupérer tous les produits triés par ID décroissant
        $allProducts = $this->produitRepository->findBy([], ['id' => 'DESC']);
        
        // Garder uniquement les 25 premiers
        $productsToKeep = array_slice($allProducts, 0, 25);
        $productsToDelete = array_slice($allProducts, 25);

        if (empty($productsToDelete)) {
            $io->success('Aucun produit à supprimer. La base de données contient déjà 25 produits ou moins.');
            return Command::SUCCESS;
        }

        // Supprimer les produits excédentaires
        foreach ($productsToDelete as $product) {
            $this->entityManager->remove($product);
        }

        $this->entityManager->flush();

        $io->success(sprintf('%d produits ont été supprimés avec succès.', count($productsToDelete)));

        return Command::SUCCESS;
    }
} 
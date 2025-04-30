<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 *
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function findByUser($userId)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.utilisateur = :val')
            ->setParameter('val', $userId)
            ->orderBy('c.dateCommande', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findWithDetails($id)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :val')
            ->setParameter('val', $id)
            ->leftJoin('c.produits', 'p')
            ->addSelect('p')
            ->leftJoin('c.utilisateur', 'u')
            ->addSelect('u')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
} 
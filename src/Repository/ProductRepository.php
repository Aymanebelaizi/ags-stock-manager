<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) { parent::__construct($registry, Product::class); }

    /**
     * Calcule le nombre de produits par catégorie pour les graphiques
     */
    public function getProductsCountByCategory(): array
    {
        return $this->createQueryBuilder('p')
            ->select('c.name as categoryName, COUNT(p.id) as count')
            ->join('p.category', 'c')
            ->groupBy('c.name')
            ->getQuery()
            ->getResult();
    }

    public function findByLowStock(int $threshold): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.quantity <= :val')
            ->setParameter('val', $threshold)
            ->getQuery()->getResult();
    }
}
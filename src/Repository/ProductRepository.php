<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Nombre de produits par catégorie (utile PieChart)
     * Retour: [ ['categoryName' => '...', 'count' => 12], ... ]
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

    /**
     * Produits en stock faible
     */
    public function findByLowStock(int $threshold): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.quantity <= :val')
            ->setParameter('val', $threshold)
            ->orderBy('p.quantity', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

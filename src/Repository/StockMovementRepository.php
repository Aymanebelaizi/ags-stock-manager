<?php

namespace App\Repository;

use App\Entity\StockMovement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StockMovementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockMovement::class);
    }

    /**
     * Calcule la somme totale des quantités pour un type donné (IN ou OUT)
     * Utile pour les barres verte et rouge du graphique
     */
    public function countByType(string $type): int
    {
        $result = $this->createQueryBuilder('m')
            ->select('SUM(m.quantity)')
            ->andWhere('m.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) ($result ?? 0);
    }
}
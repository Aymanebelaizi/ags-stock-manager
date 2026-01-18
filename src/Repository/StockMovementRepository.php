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
     * Somme totale des quantités pour un type (ENTREE / SORTIE)
     */
    public function countByType(string $type): int
    {
        $result = $this->createQueryBuilder('m')
            ->select('COALESCE(SUM(m.quantity), 0)')
            ->andWhere('m.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $result;
    }

    /**
     * Quantité achetée (ENTREE) par jour (ex: 14 derniers jours)
     * Retour: [ ['day' => '2026-01-01', 'qty' => 10], ... ]
     */
    public function getPurchasedQtyByDay(int $days = 14): array
    {
        $from = (new \DateTimeImmutable())->modify("-{$days} days")->setTime(0, 0);

        return $this->createQueryBuilder('m')
            ->select("SUBSTRING(m.createdAt, 1, 10) AS day, COALESCE(SUM(m.quantity), 0) AS qty")
            ->andWhere('m.type = :t')
            ->andWhere('m.createdAt >= :from')
            ->setParameter('t', 'ENTREE')
            ->setParameter('from', $from)
            ->groupBy('day')
            ->orderBy('day', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Produits les plus achetés (ENTREE) sur une période (ex: 30 jours)
     * Retour: [ ['name' => 'Produit A', 'qty' => 120], ... ]
     */
    public function getTopPurchasedProducts(int $limit = 5, int $days = 30): array
    {
        $from = (new \DateTimeImmutable())->modify("-{$days} days")->setTime(0, 0);

        return $this->createQueryBuilder('m')
            ->select('p.name AS name, COALESCE(SUM(m.quantity), 0) AS qty')
            ->join('m.product', 'p')
            ->andWhere('m.type = :t')
            ->andWhere('m.createdAt >= :from')
            ->setParameter('t', 'ENTREE')
            ->setParameter('from', $from)
            ->groupBy('p.id, p.name')
            ->orderBy('qty', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Comparaison ENTREE vs SORTIE sur X jours
     * Retour: ['in' => 100, 'out' => 70]
     */
    public function getInOutTotals(int $days = 30): array
    {
        $from = (new \DateTimeImmutable())->modify("-{$days} days")->setTime(0, 0);

        $rows = $this->createQueryBuilder('m')
            ->select('m.type AS type, COALESCE(SUM(m.quantity), 0) AS qty')
            ->andWhere('m.createdAt >= :from')
            ->setParameter('from', $from)
            ->groupBy('m.type')
            ->getQuery()
            ->getResult();

        $in = 0; $out = 0;
        foreach ($rows as $r) {
            if (($r['type'] ?? '') === 'ENTREE') $in = (int) $r['qty'];
            if (($r['type'] ?? '') === 'SORTIE') $out = (int) $r['qty'];
        }

        return ['in' => $in, 'out' => $out];
    }
}

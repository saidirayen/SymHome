<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * Retourne le CA groupé par mois sur les 12 derniers mois.
     * Format: [['mois' => '2025-01', 'total' => 1234.5], ...]
     */
    public function getChiffreAffairesParMois(): array
    {
        $raw = $this->createQueryBuilder('c')
            ->select("DATE_FORMAT(c.date_creation, '%Y-%m') AS mois, SUM(c.total) AS total")
            ->where('c.date_creation >= :debut')
            ->setParameter('debut', new \DateTime('-12 months'))
            ->groupBy('mois')
            ->orderBy('mois', 'ASC')
            ->getQuery()
            ->getResult();

        return $raw;
    }
}
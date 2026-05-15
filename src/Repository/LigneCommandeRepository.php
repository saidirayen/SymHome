<?php

namespace App\Repository;

use App\Entity\LigneCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LigneCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneCommande::class);
    }

    /**
     * Retourne les meubles les plus vendus (par quantité totale).
     */
    public function findTopMeubles(int $limit = 5): array
    {
        return $this->createQueryBuilder('l')
            ->select('m.nom, SUM(l.quantite) AS totalVendu')
            ->join('l.meuble', 'm')
            ->groupBy('m.id')
            ->orderBy('totalVendu', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
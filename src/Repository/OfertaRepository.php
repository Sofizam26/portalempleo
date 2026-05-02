<?php

namespace App\Repository;

use App\Entity\Oferta;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class OfertaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oferta::class);
    }

    public function buscarOfertasHome(): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.anunciante', 'a')
            ->addSelect('a')
            ->orderBy('o.fecha_publicacion', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
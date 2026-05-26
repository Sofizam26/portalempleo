<?php

namespace App\Repository;

use App\Entity\Candidato;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class CandidatoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidato::class);
    }

    public function buscarPerfilesHome(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.nombre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function buscarCandidatosPorFiltro(string $filtro): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.nombre LIKE :filtro
                OR c.ciudad LIKE :filtro
                OR c.telefono LIKE :filtro
                OR c.titulo_profesional LIKE :filtro
                OR c.descripcion LIKE :filtro')
            ->setParameter('filtro', '%' . $filtro . '%')
            ->orderBy('c.nombre', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
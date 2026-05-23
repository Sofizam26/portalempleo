<?php

namespace App\Repository;

use App\Entity\Anunciante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AnuncianteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Anunciante::class);
    }

    public function buscarAnunciantesPorFiltro(string $filtro): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.nombre_anunciante LIKE :filtro 
                OR a.descripcion LIKE :filtro
                OR a.tipo LIKE :filtro
                OR a.ciudad LIKE :filtro')
            ->setParameter('filtro', '%' . $filtro . '%')
            ->orderBy('a.nombre_anunciante', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
?>
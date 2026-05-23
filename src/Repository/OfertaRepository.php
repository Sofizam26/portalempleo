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
    public function buscarOfertasPorFiltro(string $filtro): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.anunciante', 'a')
            ->addSelect('a')
            ->andWhere('o.titulo LIKE :filtro
                OR o.descripcion LIKE :filtro
                OR o.ciudad LIKE :filtro
                OR o.tipo_contrato LIKE :filtro
                OR a.nombre_anunciante LIKE :filtro')
            ->setParameter('filtro', '%' . $filtro . '%')
            ->orderBy('o.fecha_publicacion', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function guardar(Oferta $oferta, bool $flush = true): void
    {
        $this->getEntityManager()->persist($oferta);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function eliminar(Oferta $oferta, bool $flush = true): void
    {
        $this->getEntityManager()->remove($oferta);

        if($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
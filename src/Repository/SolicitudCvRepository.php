<?php

namespace App\Repository;

use App\Entity\SolicitudCv;
use App\Entity\Usuario;
use App\Entity\Candidato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SolicitudCvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitudCv::class);
    }

    public function buscarSolicitud(Usuario $usuario, Candidato $candidato): ?SolicitudCv
    {
        return $this->findOneBy(['usuarioSolicitante' => $usuario, 'candidato' => $candidato]);
    }
}
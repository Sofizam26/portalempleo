<?php

namespace App\Repository;

use App\Entity\Conversacion;
use App\Entity\Candidato;
use App\Entity\Anunciante;
use App\Entity\Oferta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ConversacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversacion::class);
    }

    public function findConversacion(Candidato $candidato, Anunciante $anunciante, ?Oferta $oferta): ?Conversacion
    {
        return $this->findOneBy([
            'candidato' => $candidato,
            'anunciante' => $anunciante,
            'oferta' => $oferta
        ]);
    }

    public function findByCandidato(Candidato $candidato): array
    {
        return $this->findBy(
            ['candidato' => $candidato],
            ['fechaCreacion' => 'DESC']
        );
    }

    public function findByAnunciante(Anunciante $anunciante): array
    {
        return $this->findBy(
            ['anunciante' => $anunciante],
            ['fechaCreacion' => 'DESC']
        );
    }
}
<?php

namespace App\Repository;

use App\Entity\Mensaje;
use App\Entity\Conversacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MensajeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mensaje::class);
    }

    public function findMensajesOrdenados(Conversacion $conversacion): array
    {
        return $this->findBy(['conversacion' => $conversacion], ['fechaEnvio' => 'ASC']);
    }

    public function countNoLeidos(Conversacion $conversacion, string $rol): int
    {
        return $this->count([
            'conversacion' => $conversacion,
            'leido' => false,
            'remitente' => $rol === 'candidato' ? 'anunciante' : 'candidato'
        ]);
    }
}
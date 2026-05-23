<?php

namespace App\Repository;

use App\Entity\RegistroPendiente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RegistroPendienteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegistroPendiente::class);
    }

    public function findByEmail(string $email): ?RegistroPendiente
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findByToken(string $token): ?RegistroPendiente
    {
        return $this->findOneBy(['token' => $token]);
    }
    public function save(RegistroPendiente $pendiente): void
    {
        $em = $this->getEntityManager();
        $em->persist($pendiente);
        $em->flush();
    }

    public function remove(RegistroPendiente $pendiente): void
    {
        $em = $this->getEntityManager();
        $em->remove($pendiente);
        $em->flush();
    }
}
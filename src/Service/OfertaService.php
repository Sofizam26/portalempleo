<?php

namespace App\Service;

use App\Entity\Oferta;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;

class OfertaService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function crearOferta(Oferta $oferta, Usuario $usuario): void
    {
        if ($usuario->getRol() !=='anunciante') {
            throw new LogicException('ERROR: Rol incorrecto.');
        }

        $anunciante = $usuario->getAnunciante();

        if (!$anunciante) {
            throw new LogicException('ERROR: El usuario no existe');
        }

        $oferta->setAnunciante($anunciante);
        $oferta->setFechaPublicacion(new \DateTime());

        $this->entityManager->persist($oferta);
        $this->entityManager->flush();
    }

}

?>
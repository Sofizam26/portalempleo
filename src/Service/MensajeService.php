<?php

namespace App\Service;

use App\Entity\Conversacion;
use App\Entity\Mensaje;
use App\Repository\MensajeRepository;
use Doctrine\ORM\EntityManagerInterface;

class MensajeService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MensajeRepository $mensajeRepo
    ) {
    }

    public function obtenerMensajesOrdenados(Conversacion $conversacion): array
    {
        return $this->mensajeRepo->findMensajesOrdenados($conversacion);
    }

    public function enviarMensaje(Conversacion $conversacion, string $remitente, string $texto): void
    {
        $mensaje = new Mensaje();
        $mensaje->setConversacion($conversacion);
        $mensaje->setRemitente($remitente);
        $mensaje->setMensaje($texto);

        $this->em->persist($mensaje);
        $this->em->flush();
    }

    public function marcarMensajesComoLeidos(Conversacion $conversacion, string $rol): void
    {
        foreach ($conversacion->getMensajes() as $mensaje) {
            if ($mensaje->getRemitente() !== $rol) {
                $mensaje->setLeido(true);
            }
        }

        $this->em->flush();
    }
}
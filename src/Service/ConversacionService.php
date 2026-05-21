<?php

namespace App\Service;

use App\Entity\Conversacion;
use App\Entity\Oferta;
use App\Entity\Candidato;
use App\Entity\Anunciante;
use App\Repository\ConversacionRepository;
use Doctrine\ORM\EntityManagerInterface;

class ConversacionService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ConversacionRepository $conversacionRepo
    ) {
    }

    public function obtenerOcrearConversacion(Candidato $candidato, Anunciante $anunciante, ?Oferta $oferta): Conversacion
    {
        $conversacion = $this->conversacionRepo->findConversacion($candidato, $anunciante, $oferta);

        if (!$conversacion) {
            $conversacion = new Conversacion();
            $conversacion->setCandidato($candidato);
            $conversacion->setAnunciante($anunciante);
            $conversacion->setOferta($oferta);

            $this->em->persist($conversacion);
            $this->em->flush();
        }

        return $conversacion;
    }
}
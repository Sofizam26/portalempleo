<?php

namespace App\Controller;

use App\Entity\Oferta;
use App\Entity\Candidato;
use App\Entity\Conversacion;
use App\Service\ConversacionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class ConversacionController extends AbstractController
{
    #[Route('/conversaciones', name: 'lista_conversaciones')]
    public function listaConversaciones()
    {
        $usuario = $this->getUser();

        if ($usuario->getRol() === 'candidato') {
            $conversaciones = $usuario->getCandidato()->getConversaciones();
        } else {
            $conversaciones = $usuario->getAnunciante()->getConversaciones();
        }

        return $this->render('conversacion/lista.html.twig', [
            'conversaciones' => $conversaciones
        ]);
    }

    #[Route('/conversacion/iniciar/{idOferta}/{idCandidato}', name: 'iniciar_conversacion')]
    public function iniciarConversacion(Oferta $idOferta, Candidato $idCandidato, ConversacionService $service)
    {
        $usuario = $this->getUser();

        if ($usuario->getRol() !== 'anunciante') {
            throw $this->createAccessDeniedException();
        }

        $anunciante = $usuario->getAnunciante();

        if ($idOferta->getAnunciante()->getId() !== $anunciante->getId()) {
            throw $this->createAccessDeniedException();
        }

        $conversacion = $service->obtenerOcrearConversacion($idCandidato, $anunciante, $idOferta);

        return $this->redirectToRoute('ver_conversacion', ['id' => $conversacion->getId()]);
    }

    #[Route('/conversacion/{id}', name: 'ver_conversacion')]
    public function verConversacion(Conversacion $conversacion, ConversacionService $service)
    {
        $mensajes = $service->obtenerMensajesOrdenados($conversacion);

        return $this->render('conversacion/ver.html.twig', [
            'conversacion' => $conversacion,
            'mensajes' => $mensajes
        ]);
    }
}
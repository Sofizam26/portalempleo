<?php

namespace App\Controller\Chat;

use App\Entity\Usuario;
use App\Entity\Conversacion;
use App\Service\ConversacionService;
use App\Service\MensajeService;
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

        return $this->render('chat/lista.html.twig', [
            'conversaciones' => $conversaciones
        ]);
    }
    #[Route('/conversacion/iniciar/{idUsuario}', name: 'iniciar_conversacion')]
    public function iniciarConversacion(Usuario $idUsuario, ConversacionService $service)
    {
        $usuarioActual = $this->getUser();

        if ($usuarioActual->getId() === $idUsuario->getId()) {
            throw $this->createAccessDeniedException();
        }

        if ($usuarioActual->getRol() === 'candidato' && $idUsuario->getRol() === 'anunciante') {
            $candidato = $usuarioActual->getCandidato();
            $anunciante = $idUsuario->getAnunciante();
        } elseif ($usuarioActual->getRol() === 'anunciante' && $idUsuario->getRol() === 'candidato') {
            $candidato = $idUsuario->getCandidato();
            $anunciante = $usuarioActual->getAnunciante();
        } else {
            throw $this->createAccessDeniedException();
        }

        $conversacion = $service->obtenerOcrearConversacion($candidato, $anunciante);

        return $this->redirectToRoute('ver_conversacion', ['id' => $conversacion->getId()]);
    }

    #[Route('/conversacion/{id}', name: 'ver_conversacion')]
    public function verConversacion(Conversacion $conversacion, MensajeService $mensajeService)
    {
        $mensajes = $mensajeService->obtenerMensajesOrdenados($conversacion);

        return $this->render('chat/ver.html.twig', [
            'conversacion' => $conversacion,
            'mensajes' => $mensajes
        ]);
    }
}
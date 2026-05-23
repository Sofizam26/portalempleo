<?php

namespace App\Controller\Chat;

use App\Entity\Conversacion;
use App\Service\MensajeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class MensajeController extends AbstractController
{
    #[Route('/conversacion/{id}/enviar', name: 'enviar_mensaje', methods: ['POST'])]
    public function enviarMensaje(
        Conversacion $conversacion,
        Request $request,
        MensajeService $service
    ) {
        $texto = trim($request->request->get('mensaje'));

        if ($texto !== '') {
            $service->enviarMensaje($conversacion, $this->getUser()->getRol(), $texto);
        }

        return $this->redirectToRoute('ver_conversacion', ['id' => $conversacion->getId()]);
    }

    #[Route('/conversacion/{id}/leer', name: 'marcar_leidos')]
    public function marcarLeidos(Conversacion $conversacion, MensajeService $service)
    {
        $service->marcarMensajesComoLeidos($conversacion, $this->getUser()->getRol());

        return $this->redirectToRoute('ver_conversacion', ['id' => $conversacion->getId()]);
    }
}
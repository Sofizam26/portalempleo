<?php

namespace App\Controller\Registro;

use App\Entity\RegistroPendiente;
use App\Form\Registro\RegistroAnuncianteFormType;
use App\Service\RegistroService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AnuncianteController extends AbstractController
{
    #[Route('/registro/anunciante', name: 'registro_anunciante')]
    public function registroAnunciante(Request $request, RegistroService $registroService)
    {
        $pendiente = new RegistroPendiente();
        $form = $this->createForm(RegistroAnuncianteFormType::class, $pendiente);
        $form->handleRequest($request);

        $error = null;
        $mensaje = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordPlano = $form->get('password')->getData();
            $resultado = $registroService->crearRegistroPendiente($pendiente, $passwordPlano, 'anunciante');
            $error = $resultado['error'];
            $mensaje = $resultado['mensaje'];
        }

        return $this->render('registro/anunciante.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
            'mensaje' => $mensaje,
        ]);
    }
}
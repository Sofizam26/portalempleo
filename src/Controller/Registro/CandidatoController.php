<?php

namespace App\Controller\Registro;

use App\Entity\RegistroPendiente;
use App\Form\Registro\RegistroCandidatoFormType;
use App\Service\RegistroService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CandidatoController extends AbstractController
{
    #[Route('/registro/candidato', name: 'registro_candidato')]
    public function registroCandidato(Request $request, RegistroService $registroService)
    {
        $pendiente = new RegistroPendiente();
        $form = $this->createForm(RegistroCandidatoFormType::class, $pendiente);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $passwordPlano = $form->get('password')->getData();

            $resultado = $registroService->crearRegistroPendienteCandidato($pendiente, $passwordPlano);

            return $this->render('registro/candidato.html.twig', [
                'form' => $form->createView(),
                'error' => $resultado['error'],
                'mensaje' => $resultado['mensaje'],
            ]);
        }

        return $this->render('registro/candidato.html.twig', [
            'form' => $form->createView(),
            'error' => null,
            'mensaje' => null,
        ]);
    }
}
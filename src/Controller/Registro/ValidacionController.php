<?php

namespace App\Controller\Registro;

use App\Service\RegistroService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class ValidacionController extends AbstractController
{
    #[Route('/validar/{token}', name: 'validar_registro')]
    public function validar(string $token, RegistroService $registroService)
    {
        $resultado = $registroService->validarRegistro($token);

        return $this->render('registro/validacion.html.twig', [
            'error' => $resultado['error'],
            'mensaje' => $resultado['mensaje'],
        ]);
    }
}
<?php

namespace App\Controller;

use App\Service\HomeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(HomeService $homeService): Response
    {
        // Usuario logueado
        $usuario = $this->getUser();

        if (!$usuario) {
            return $this->redirectToRoute('ctrl_login');
        }

        // Traer datos home de Usuario
        $datosHome = $homeService->obtenerDatosHome($usuario);

        return $this->render($datosHome['vista'], $datosHome['parametros']);
    }
}
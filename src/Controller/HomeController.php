<?php
namespace App\Controller;

use App\Service\HomeService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->redirectToRoute('ctrl_login');
    }

    #[Route('/home', name: 'app_home')]
    public function home(HomeService $homeService): Response
    {
        // Usuario logueado
        $usuario = $this->getUser();

        // Traer datos home de Usuario
        $datosHome = $homeService->obtenerDatosHome($usuario);

        return $this->render($datosHome['vista'], $datosHome['parametros']);
    }
}
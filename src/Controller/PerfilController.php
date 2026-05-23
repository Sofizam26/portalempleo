<?php
namespace App\Controller;

use App\Entity\Usuario;
use App\Service\PerfilService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PerfilController extends AbstractController
{
    #[Route('/mi_perfil', name: 'app_miperfil')]
    public function miPerfil(PerfilService $perfilService): Response
    {
        // Usuario logueado
        $usuario = $this->getUser();

        if (!$usuario) {
            return $this->redirectToRoute('ctrl_login');
        }

        $datos = $perfilService->obtenerPerfil($usuario);

        return $this->render($datos['vista'], $datos['parametros']);
    }

    #[Route('/perfil/anunciante/{id}', name: 'app_perfilAnunciante')]
    public function perfilAnunciante(int $id, PerfilService $perfilService): Response
    {
        $datos = $perfilService->obtenerPerfilAnunciante($id);

        return $this->render($datos['vista'], $datos['parametros']);
    }

    #[Route('/perfil/candidato/{id}', name: 'app_perfilCandidato')]
    public function perfilCandidato(int $id, PerfilService $perfilService): Response
    {
        $datos = $perfilService->obtenerPerfilCandidato($id);

        return $this->render($datos['vista'], $datos['parametros']);
    }
}
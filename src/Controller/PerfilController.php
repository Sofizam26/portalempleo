<?php
namespace App\Controller;

use App\Service\PerfilService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/mi_perfil/editar', name: 'app_editarPerfil', methods: ['POST'])]
    public function editarPerfil(Request $request, PerfilService $perfilService): Response
    {
        $usuario = $this->getUser();

        if (!$usuario) {
            return $this->redirectToRoute('ctrl_login');
        }

        try{
            $perfilService->editarPerfil($usuario, $request, $this->getParameter('kernel.project_dir'));
            $this->addFlash('success', 'Perfil actualizado.');
        } catch (\LogicException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_miperfil');
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
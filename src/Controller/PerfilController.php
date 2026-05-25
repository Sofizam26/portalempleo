<?php
namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\SolicitudCv;
use App\Service\PerfilService;
use App\Repository\CandidatoRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SolicitudCvRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PerfilController extends AbstractController
{
    #[Route('/mi_perfil', name: 'app_miperfil')]
    public function miPerfil(PerfilService $perfilService): Response
    {
        // Usuario logueado
        $usuario = $this->getUser();

        $datos = $perfilService->obtenerPerfil($usuario);

        return $this->render($datos['vista'], $datos['parametros']);
    }

    #[Route('/mi_perfil/editar', name: 'app_editarPerfil', methods: ['POST'])]
    public function editarPerfil(Request $request, PerfilService $perfilService): Response
    {
        $usuario = $this->getUser();

        try{
            $perfilService->editarPerfil($usuario, $request, $this->getParameter('kernel.project_dir'));
            $this->addFlash('success', 'Perfil actualizado.');
        } catch (\LogicException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_miperfil');
    }

    #[Route('/mi_perfil/cv/ver', name: 'app_verMiCv', methods: ['GET'])]
    public function verCvPropio(): Response
    {
        $usuario = $this->getUser();

        if (!$usuario instanceof Usuario) {
            return $this->redirectToRoute('ctrl_login');
        }

        if ($usuario->getRol() !== 'candidato') {
            throw $this->createAccessDeniedException('Solo los candidatos pueden ver su currículum.');
        }
        
        $candidato = $usuario->getCandidato();

        if(!$candidato || !$candidato->getCvPdf()) {
            throw $this->createNotFoundException('No tiene un curriculum subido.');
        }

        $rutaAbsoluta = $this->getParameter('kernel.project_dir') . '/var/' . $candidato->getCvPdf();

        if (!file_exists($rutaAbsoluta)) {
            throw $this->createNotFoundException('El archivo del currículum no existe');
        }

        return $this->file($rutaAbsoluta, null, ResponseHeaderBag::DISPOSITION_INLINE);
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
        $usuario = $this->getUser();

        if (!$usuario instanceof Usuario) {
            $usuario = null;
        }

        $datos = $perfilService->obtenerPerfilCandidato($id, $usuario);

        return $this->render($datos['vista'], $datos['parametros']);
    }

    #[Route('/perfil/candidato/{id}/solicitar-cv', name: 'app_solicitarCv', methods: ['POST'])]
    public function solicitarCv(int $id, CandidatoRepository $candidatoRepository, SolicitudCvRepository $solicitudCvRepository, EntityManagerInterface $em, Request $request): Response
    {
        $usuario = $this->getUser();

        $candidato = $candidatoRepository->find($id);

        if (!$candidato) {
            throw $this->createNotFoundException('Candidato no encontrado.');
        }

        if ($candidato->getUsuario() === 'usuario') {
            $this->addFlash('error', 'No puedes solicitar acceso a tu propio currículum.');
            return $this->redirectToRoute('app_perfilCandidato', ['id' => $id]);
        }

        if (!$candidato->getCvPdf()) {
            $this->addFlash('error', 'Este usuario no tiene un currículum subido.');
            return $this->redirectToRoute('app_perfilCandidato', ['id' => $id]);
        }

        $solicitudExistente = $solicitudCvRepository->buscarSolicitud($usuario, $candidato);
        
        if ($solicitudExistente) {
            $this->addFlash('error', 'Ya existe una solicitud para ver este currículum.');
            return $this->redirectToRoute('app_perfilCandidato', ['id' => $id]);
        }

        $solicitud = New SolicitudCv();
        $solicitud->setUsuarioSolicitante($usuario);
        $solicitud->setCandidato($candidato);
        $solicitud->setEstado('pendiente');
        $solicitud->setMensaje($request->request->get('mensaje') ?: null);

        $em->persist($solicitud);
        $em->flush();

        $this->addFlash('success', 'Solicitud enviada correctamente.');

        return $this->redirectToRoute('app_perfilCandidato', ['id' => $id]);
    }

    #[Route('/perfil/candidato/{id}/cv/ver', name: 'app_verCv', methods: ['GET'])]
    public function verCv(int $id, CandidatoRepository $candidatoRepository, SolicitudCvRepository $solicitudCvRepository): Response
    {
        $usuario = $this->getUser();

        $candidato = $candidatoRepository->find($id);

        if (!$candidato || !$candidato->getCvPdf()) {
            throw $this->createNotFoundException('Curriculum no disponible.');
        }

        $esPropietario = $candidato->getUsuario() === $usuario;
        $esPublico = $candidato->getCvPublico();
        $solicitud = $solicitudCvRepository->buscarSolicitud($usuario, $candidato);
        $tienePermiso = $solicitud && $solicitud->getEstado() === 'aceptada';

        if (!$esPropietario && !$esPublico && !$tienePermiso) {
            throw $this->createAccessDeniedException('No tienes permiso para ver este currículum.');
        }

        $rutaAbsoluta = $this->getParameter('kernel.project_dir') . '/var/' . $candidato->getCvPdf();

        if (!file_exists($rutaAbsoluta)) {
            throw $this->createNotFoundException('El currículum no existe.');
        }

        return $this->file($rutaAbsoluta, null, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    #[Route('/mi_perfil/cv/solicitud/{id}/{estado}', name: 'app_responderSolicitudCv', methods: ['POST'])]
    public function responderSolicitudCv(int $id, string $estado, SolicitudCvRepository $solicitudCvRepository, EntityManagerInterface $em): Response 
    {
        $usuario = $this->getUser();

        $solicitud = $solicitudCvRepository->find($id);

        if (!$solicitud) {
            throw $this->createNotFoundException('Solicitud no encontrada.');
        }

        if ($solicitud->getCandidato()->getUsuario() !== $usuario) {
            throw $this->createAccessDeniedException('No puedes gestionar esta solicitud.');
        }

        if (!in_array($estado, ['aceptada', 'rechazada'], true)) {
            throw new \LogicException('Estado no válido.');
        }

        $solicitud->setEstado($estado);
        $solicitud->setFechaRespuesta(new \DateTime());

        $em->flush();

        $this->addFlash('success', 'Solicitud actualizada correctamente.');

        return $this->redirectToRoute('app_miperfil');
    }
}
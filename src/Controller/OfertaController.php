<?php
namespace App\Controller;

use App\Entity\Oferta;
use App\Service\OfertaService;
use App\Repository\OfertaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OfertaController extends AbstractController
{
    #[Route('/oferta/publicar', name: 'app_publicar_oferta', methods:['POST'])]
    public function publicar(Request $request, OfertaService $ofertaService): Response
    {
        $usuario = $this->getUser();

        try {
            $oferta = new Oferta();
            $oferta->setTitulo($request->request->get('titulo'));
            $oferta->setDescripcion($request->request->get('descripcion'));
            $oferta->setSalario($request->request->get('salario'));
            $oferta->setTipoContrato($request->request->get('tipo_contrato'));
            $oferta->setCiudad($request->request->get('ciudad'));

            $ofertaService->crearOferta($oferta, $usuario);
            $this->addFlash('success', 'Oferta publicada correctamente.');
        } catch (\Throwable $e){
            $this->addFlash('error', $e->getMessage());
        }
        
        return $this->redirectToRoute('app_home');
    }

    #[Route('/oferta/{id}/editar', name: 'app_editar_oferta', methods:['POST'])]
    public function editar(int $id, Request $request, OfertaRepository $ofertaRepository, OfertaService $ofertaService): Response
    {
        $usuario = $this->getUser();

        $oferta = $ofertaRepository->find($id);

        if (!$oferta) {
            throw $this->createNotFoundException('Oferta no encontrada.');
        }

        try {
            $ofertaService->editarOferta($oferta, $usuario, $request->request->all());
            $this->addFlash('success', 'Oferta actualizada correctamente.');
        } catch (\LogicException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_miperfil');
    }

    #[Route('/oferta/{id}/eliminar', name: 'app_eliminar_oferta', methods: ['POST'])]
    public function eliminar(int $id, OfertaRepository $ofertaRepository, OfertaService $ofertaService): Response 
    {
        $usuario = $this->getUser();

        $oferta = $ofertaRepository->find($id);

        if (!$oferta) {
            throw $this->createNotFoundException('Oferta no encontrada.');
        }

        try {
            $ofertaService->eliminarOferta($oferta, $usuario);
            $this->addFlash('success', 'Oferta eliminada correctamente.');
        } catch (\LogicException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_miperfil');
    }
}
?>
<?php
namespace App\Controller;

use App\Entity\Oferta;
use App\Service\OfertaService;
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

        if (!$usuario) {
            return $this->redirectToRoute('ctrl_login');
        }

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
}
?>
<?php
namespace App\Controller;

use App\Entity\Oferta;
use App\Form\OfertaFormType;
use App\Service\OfertaService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use LogicException;

class OfertaController extends AbstractController
{
    #[Route('/oferta/publicar', name: 'app_publicar_oferta')]
    public function publicar(Request $request, OfertaService $ofertaService): Response
    {
        $usuario = $this->getUser();

        if (!$usuario) {
            return $this->redirectToRoute('ctrl_login');
        }

        if ($usuario->getRoles() !='anunciante') {
            throw new LogicException('ERROR: Solo los anunciantes pueden publicar ofertas');
        }

        $oferta = new Oferta();
        $form = $this->createForm(OfertaFormType::class, $oferta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ofertaService->crearOferta($oferta. $usuario);
            $this->addFlash('success', 'Oferta publicada correctamente.');
            
            return $this->redirectToRoute('app_home');
        }

        return $this->render('oferta/publicar.html.twig', [
            'form' => $form->createView()
            ]);
    }
}
?>
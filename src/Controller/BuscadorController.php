<?php
namespace App\Controller;

use App\Service\BuscadorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BuscadorController extends AbstractController
{
    #[Route('/buscar', name: 'app_buscador')]
    public function buscar(Request $request, BuscadorService $buscadorService): Response
    {
        $filtro = $request->query->get('q', '');
        $resultados = $buscadorService->buscar($filtro);

        return $this->render('buscador/resultados.html.twig', [
                'filtro' => $resultados['filtro'],
                'candidatos' => $resultados['candidatos'],
                'anunciantes' => $resultados['anunciantes'],
                'ofertas' => $resultados['ofertas']
            ]);
    }
}

?>
<?php
namespace App\Controller;

use App\Service\NavService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NavController extends AbstractController
{
    #[Route('/nav/component', name: 'app_nav')]
    public function component(NavService $navService, RequestStack $requestStack): Response
    {
        $request = $requestStack->getCurrentRequest();
        $usuario = $this->getUser();

        $menu = $navService->obtenerMenu($usuario, $request?->attributes->get('_route'));
    
        return $this->render('components/_nav.html.twig', ['menu' => $menu]);
        }
}
?>
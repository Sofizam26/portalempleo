<?php

namespace App\Controller\Registro;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class RolController extends AbstractController
{
    #[Route('/registro/rol', name: 'registro_rol')]
    public function elegirRol()
    {
        return $this->render('registro/rol.html.twig');
    }
}
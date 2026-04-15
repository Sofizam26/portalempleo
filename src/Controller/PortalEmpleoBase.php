<?php 
    namespace App\Controller;
    use Doctrine\ORM\EntityManager;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Attribute\Route;

    class PortalEmpleoBase extends AbstractController
    {
        // //////////////////////////////////////////////
        //                                            //
        //      Controladores Basicos de la app      //
        //                                          //
        // //////////////////////////////////////////

        #[Route('/home', name:'app_Home')]
        public function home(EntityManager $em) {
            // $usuarioAcutal = $this->getUser();

            return $this->render('home.html.twig');
        }
    }
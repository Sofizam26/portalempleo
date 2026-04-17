<?php

namespace App\Service;

use App\Entity\Usuario;
use App\Repository\CandidatoRepository;
use App\Repository\OfertaRepository;

class HomeService
{
    public function __construct(
        private CandidatoRepository $candidatoRepository,
        private OfertaRepository $ofertaRepository
    ) {
    }

    public function obtenerDatosHome(Usuario $usuario): array
    {
        // Anunciantes veras todos los perfiles Candidatos
        if ($usuario->getRol() == 'anunciante') {
            $candidatos = $this->candidatoRepository->buscarPerfilesHome();

            return [
                'vista' => 'home/anunciante.html.twig',
                'parametros' => ['candidatos' => $candidatos]
            ];
        }
        
        // Candidatos veran Ofertas de los anunciantes
        if ($usuario->getRol() == 'candidato') {
            $ofertas = $this->ofertaRepository->buscarOfertasHome();

            return [
                'vista' => 'home/candidato.html.twig',
                'parametros' => ['ofertas' => $ofertas]
            ];
        }

        return [
            'vista' => 'home/index.html.twig',
            'parametros' => []
        ];
    }
}
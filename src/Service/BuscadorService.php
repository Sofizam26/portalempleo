<?php

namespace App\Service;

use App\Repository\AnuncianteRepository;
use App\Repository\CandidatoRepository;
use App\Repository\OfertaRepository;
use App\Repository\OfertaRepositoryy;

class BuscadorService
{
    public function __construct(
        private CandidatoRepository $candidatoRepository,
        private AnuncianteRepository $anuncianteRepository,
        private OfertaRepository $ofertaRepository
    ) {}

    public function buscar(string $filtro): array
    {
        $filtro = trim($filtro);

        if ($filtro === '') {
            return [
                'filtro' => '',
                'candidatos' => [],
                'anunciantes' => [],
                'ofertas' => []
            ];
        }

        return [
            'filtro' => $filtro,
            'candidatos' => $this->candidatoRepository->buscarCandidatosPorFiltro($filtro),
            'anunciantes' => $this->anuncianteRepository->buscarAnunciantesPorFiltro($filtro),
            'ofertas' => $this->ofertaRepository->buscarOfertasPorFiltro($filtro),
        ];
    }
}
?>
<?php

namespace App\Service;

use App\Entity\Oferta;
use App\Entity\Usuario;
use App\Repository\OfertaRepository;
use LogicException;

class OfertaService
{
    public function __construct(
        private OfertaRepository $ofertaRepository
    ) {}

    public function crearOferta(Oferta $oferta, Usuario $usuario): void
    {
        if ($usuario->getRol() !=='anunciante') {
            throw new LogicException('ERROR: Rol incorrecto.');
        }

        $anunciante = $usuario->getAnunciante();

        if (!$anunciante) {
            throw new LogicException('ERROR: El usuario no existe');
        }

        $oferta->setAnunciante($anunciante);
        $oferta->setFechaPublicacion(new \DateTime());

        $this->ofertaRepository->guardar($oferta);
    }

    public function editarOferta(Oferta $oferta, Usuario $usuario, array $datos): void
    {
        if ($usuario->getRol() !== 'anunciante') {
            throw new LogicException('ERROR: Rol incorrecto');
        }

        $anunciante = $usuario->getAnunciante();

        if ($oferta->getAnunciante()->getId() !== $anunciante->getId()) {
            throw new LogicException('No puedes editar esta oferta.');
        }

        if (isset($datos['titulo']) && trim($datos['titulo']) !== '') {
            $oferta->setTitulo($datos['titulo']);
        }

        if (isset($datos['descripcion'])) {
            $oferta->setDescripcion($datos['descripcion']);
        }

        if (isset($datos['salario']) && $datos['salario'] !== '') {
            $oferta->setSalario($datos['salario']);
        }

        if (isset($datos['tipo_contrato']) && trim($datos['tipo_contrato']) !== '') {
            $oferta->setTipoContrato($datos['tipo_contrato']);
        }

        if (isset($datos['ciudad']) && trim($datos['ciudad']) !== '') {
            $oferta->setCiudad($datos['ciudad']);
        }

        $this->ofertaRepository->guardar($oferta);
    }

    public function eliminarOferta(Oferta $oferta, Usuario $usuario): void
    {
        if ($usuario->getRol() !== 'anunciante') {
            throw new LogicException('ERROR: Rol incorrecto');
        }

        $anunciante = $usuario->getAnunciante();

        if ($oferta->getAnunciante()->getId() !== $anunciante->getId()) {
            throw new LogicException('No puedes eliminar esta oferta.');
        }

        $this->ofertaRepository->eliminar($oferta);
    }
}

?>
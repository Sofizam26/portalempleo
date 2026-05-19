<?php

namespace App\Service;

use App\Entity\Usuario;
use App\Repository\NavRepository;
use LogicException;

class NavService
{
    public function __construct(
        private NavRepository $navRepository,
    ) {}

    public function obtenerMenu(Usuario $usuario, string $rutaActual = null): array
    {
        $rol = $usuario->getRol();

        if (!in_array($rol, ['candidato', 'anunciante'], true)) {
            throw new LogicException('ERROR: Rol incorrecto.');
        }

        $items = $this->navRepository->obtenerItemsNav();

        foreach ($items as &$item) {
            $item['active'] = $rutaActual != null && $item['route'] === $rutaActual;
        }

        return [
            'usuario' => $usuario,
            'rol' => $rol,
            'items' => $items
        ];
    }
}
?>
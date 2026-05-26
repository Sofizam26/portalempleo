<?php

namespace App\Repository;

class NavRepository
{
    public function obtenerItemsNav(): array
    {
        return [
            [
                'id' => 'home',
                'label' => 'Inicio',
                'route' => 'app_home',
                'icon' => 'home'
            ],
            [
                'id' => 'perfil',
                'label' => 'Mi Perfil',
                'route' => 'app_miperfil',
                'icon' => 'user'
            ],
            [
                'id' => 'conversaciones',
                'label' => 'Conversaciones',
                'route' => 'lista_conversaciones',
                'icon' => 'chat'
            ]
        ];
    }
}
?>
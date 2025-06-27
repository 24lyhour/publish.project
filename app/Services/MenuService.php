<?php

namespace App\Services;

class MenuService
{
    /**
     * Get the main application menu.
     *
     * @return array
     */
    public function getMenu(): array
    {
        return [
            [
            'label' => 'Dashboard',
            'icon' => 'mdi-view-dashboard',
            'route' => 'dashboard',
            'style' => [
                'marginTop' => 10,
            ],
            ],
            [
            'label' => 'Products',
            'icon' => 'mdi-package-variant-closed',
            'route' => 'products.index',
            'style' => [
                'marginTop' => 10,
            ],
            ],
        ];
    }
}
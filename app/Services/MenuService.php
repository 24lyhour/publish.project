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
            ],
            [
            'label' => 'Products',
            'icon' => 'mdi-package-variant-closed',
            'route' => 'products.index',
            ],
        ];
    }
}
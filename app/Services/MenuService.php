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
                'label' => 'LyHour Kouch',
                'image' => 'path/to/image.jpg',
                'route' => 'dashboard'
            ],
             [
                'label' => 'Dashboard',
                'icon' => 'mdi-view-dashboard',
                'route' => 'dashboard',
             ],
            [
                'label' => 'Products',
                'icon' => 'mdi-package-variant-closed',
                'route' => 'products.index',
                'style'=>[
                    'color' => 'red',
                    'marginTop' => '10px'
                ]
            ],
           
        ];
    }
}
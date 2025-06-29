<?php

namespace App\Services;

class MenuService
{
    /**
     * The menus array.
     *
     * @var array
     */
    protected array $menus = [];

    /**
     * Get the main application menu.
     *
     * @return array
     * 
     */
    public function getMenu(): array
    {
        // Flatten and return all menu items for the frontend
        $result = [];
        foreach ($this->menus as $group) {
            foreach ($group['items'] ?? [] as $item) {
                $menuItem = $item;
                if (!empty($item['submenus'])) {
                    $menuItem['submenus'] = $item['submenus'];
                }
                $result[] = $menuItem;
            }
        }
        return $result;
    }

     /**
     * Add a menu item.
     *
     * @param string $group
     * @param string $name
     * @param string $label
     * @param string $route
     * @param string $icon
     * @param int $order
     * @param string $permission
     * @param mixed $parent
     * @param mixed $extra
     * @return void
     * 
     * 
     */
    public function addMenuItem(
    string $group,
    string $name,
    string $label,
    string $route,
    string $icon,
    int $order,
    string $permission,
    $parent = null,
    $extra = null
    )   {
        $this->menus[$group]['items'][$label] = [
            'name' => $name,
            'label' => $label,
            'route' => $route,
            'icon' => $icon,
            'order' => $order,
            'permission' => $permission,
            'parent' => $parent,
            'extra' => $extra,
            'submenus' => [],
            ];
    }

    /**
     * Add Submenu Item
     * 
     * @param $menuKey
     * @param $parentLabel
     * @param $label
     * @param $route
     * 
     * @return void
     * 
     *  
     */
    public function addSubmenuItem($menuKey, $parentLabel, $label, $route, $icon = null, $order = 0, $permission = null)
    {
        
        $this->menus[$menuKey]['items'][$parentLabel]['submenus'][] = [
            'label' => $label,
            'route' => $route,
            'icon' => $icon,
            'order' => $order,
            'permission' => $permission,
        ];
    }
}
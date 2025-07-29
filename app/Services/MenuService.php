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
        // Sort items by order and return all menu items for the frontend
        $result = [];
        foreach ($this->menus as $group) {
            $items = collect($group['items'] ?? [])->sortBy('order')->values()->all();
            foreach ($items as $item) {
                $menuItem = $item;
                if (!empty($item['submenus'])) {
                    // Sort submenus by order
                    $menuItem['submenus'] = collect($item['submenus'])->sortBy('order')->values()->all();
                }
                $result[] = $menuItem;
            }
        }
        return $result;
    }

    /**
     * Add a menu item with enhanced parameter structure matching your reference.
     *
     * @param string $menu The menu group identifier
     * @param string $id The unique menu item ID
     * @param string $title The display title
     * @param string $url The URL/route
     * @param string $icon Material Design Icon
     * @param int $order Display order
     * @param string $permissions Permission required to view
     * @param string|null $route Route pattern for active state
     * @param bool $hasSubmenu Whether this item has submenus
     * @param bool $isPopup Whether this should open as popup/modal
     * @param array $extra Extra configuration
     * @return void
     */
    public function addMenuItem(
        string $menu,
        string $id,
        string $title,
        string $url,
        string $icon,
        int $order = 100,
        string $permissions = '',
        ?string $route = null,
        bool $hasSubmenu = false,
        bool $isPopup = false,
        array $extra = []
    ): void {
        $this->menus[$menu]['items'][$id] = [
            'id' => $id,
            'title' => $title,
            'label' => $title, // Keep backward compatibility
            'url' => $url,
            'route' => $route ?? $url,
            'icon' => $icon,
            'order' => $order,
            'permissions' => $permissions,
            'hasSubmenu' => $hasSubmenu,
            'isPopup' => $isPopup,
            'submenus' => [],
            'extra' => $extra,
        ];
    }

    /**
     * Add submenu item with enhanced structure.
     *
     * @param string $menu The menu group identifier
     * @param string $parentId Parent menu item ID
     * @param string $title Submenu title
     * @param string $url Submenu URL/route
     * @param int $order Display order
     * @param string $permissions Required permissions
     * @param string|null $route Route pattern for active state
     * @param string|null $icon Optional icon
     * @param bool $isPopup Whether this should open as popup/modal
     * @param array $extra Extra configuration
     * @return void
     */
    public function addSubmenuItem(
        string $menu,
        string $parentId,
        string $title,
        string $url,
        int $order = 10,
        string $permissions = '',
        ?string $route = null,
        ?string $icon = null,
        bool $isPopup = false,
        array $extra = []
    ): void {
        if (!isset($this->menus[$menu]['items'][$parentId])) {
            return; // Parent doesn't exist
        }

        $this->menus[$menu]['items'][$parentId]['submenus'][] = [
            'title' => $title,
            'label' => $title, // Keep backward compatibility
            'url' => $url,
            'route' => $route ?? $url,
            'icon' => $icon,
            'order' => $order,
            'permissions' => $permissions,
            'isPopup' => $isPopup,
            'extra' => $extra,
        ];

        // Mark parent as having submenus
        $this->menus[$menu]['items'][$parentId]['hasSubmenu'] = true;
    }

    /**
     * Check if user has permission to view menu item.
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        // For now, return true. You can implement your permission logic here
        // Example: return auth()->user()->can($permission);
        return true;
    }

    /**
     * Get filtered menu items based on permissions.
     *
     * @return array
     */
    public function getFilteredMenu(): array
    {
        $menu = $this->getMenu();
        $filtered = [];

        foreach ($menu as $item) {
            if ($this->hasPermission($item['permissions'])) {
                // Filter submenus too
                if (!empty($item['submenus'])) {
                    $item['submenus'] = array_filter($item['submenus'], function ($submenu) {
                        return $this->hasPermission($submenu['permissions']);
                    });
                }
                $filtered[] = $item;
            }
        }

        return $filtered;
    }

    /**
     * Clear all menu items (useful for testing).
     *
     * @return void
     */
    public function clearMenu(): void
    {
        $this->menus = [];
    }

    /**
     * Get menu items by group.
     *
     * @param string $group
     * @return array
     */
    public function getMenuGroup(string $group): array
    {
        return $this->menus[$group]['items'] ?? [];
    }
}
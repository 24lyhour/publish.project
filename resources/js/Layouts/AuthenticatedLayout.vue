1


<template>
    <v-app>
        <v-navigation-drawer 
            v-model="drawer" 
            :rail="sidebarMini"
            :width="sidebarMini ? 64 : 280"
            permanent
        >
            <v-list nav>
                <template v-for="(item, i) in menu" :key="i">
                    <!-- Menu item with submenus -->
                    <template v-if="item.hasSubmenu">
                        <!-- Normal expanded view -->
                        <v-list-group v-if="!sidebarMini" :value="item.id">
                            <template v-slot:activator="{ props }">
                                <v-list-item
                                    v-bind="props"
                                    :prepend-icon="item.icon"
                                    :title="item.title"
                                    class="my-1"
                                />
                            </template>
                            
                            <!-- Submenus -->
                            <template v-for="(submenu, j) in item.submenus" :key="`${i}-${j}`">
                                <Link 
                                    v-if="!submenu.isPopup" 
                                    :href="getRouteUrl(submenu.url)" 
                                    as="div"
                                >
                                    <v-list-item
                                        :prepend-icon="submenu.icon"
                                        :title="submenu.title"
                                        :active="isRouteActive(submenu.route)"
                                        class="pl-8 my-1"
                                        link
                                    />
                                </Link>
                                
                                <!-- Popup submenu item -->
                                <v-list-item
                                    v-else
                                    :prepend-icon="submenu.icon"
                                    :title="submenu.title"
                                    class="pl-8 my-1"
                                    link
                                    @click="handlePopupClick(submenu)"
                                />
                            </template>
                        </v-list-group>
                        
                        <!-- Mini sidebar view with hover menu -->
                        <v-menu
                            v-else
                            :open-on-hover="true"
                            location="end"
                            offset="8"
                            transition="slide-x-transition"
                        >
                            <template v-slot:activator="{ props }">
                                <v-list-item
                                    v-bind="props"
                                    :prepend-icon="item.icon"
                                    class="my-1"
                                />
                            </template>
                            
                            <v-list class="submenu-popup">
                                <v-list-subheader>{{ item.title }}</v-list-subheader>
                                <template v-for="(submenu, j) in item.submenus" :key="j">
                                    <Link 
                                        v-if="!submenu.isPopup" 
                                        :href="getRouteUrl(submenu.url)" 
                                        as="div"
                                    >
                                        <v-list-item
                                            :prepend-icon="submenu.icon"
                                            :title="submenu.title"
                                            :active="isRouteActive(submenu.route)"
                                            density="compact"
                                            link
                                        />
                                    </Link>
                                    
                                    <v-list-item
                                        v-else
                                        :prepend-icon="submenu.icon"
                                        :title="submenu.title"
                                        density="compact"
                                        link
                                        @click="handlePopupClick(submenu)"
                                    />
                                </template>
                            </v-list>
                        </v-menu>
                    </template>

                    <!-- Regular menu item (no submenus) -->
                    <template v-else>
                        <!-- Normal view -->
                        <template v-if="!sidebarMini">
                            <!-- Popup menu item -->
                            <v-list-item
                                v-if="item.isPopup"
                                :prepend-icon="item.icon"
                                :title="item.title"
                                class="my-1"
                                link
                                @click="handlePopupClick(item)"
                            />
                            
                            <!-- Regular link menu item -->
                            <Link v-else :href="getRouteUrl(item.url)" as="div">
                                <v-list-item
                                    :prepend-icon="item.icon"
                                    :title="item.title"
                                    :active="isRouteActive(item.route)"
                                    class="my-1"
                                    link
                                />
                            </Link>
                        </template>
                        
                        <!-- Mini sidebar view with tooltip -->
                        <template v-else>
                            <!-- Popup menu item -->
                            <v-tooltip
                                v-if="item.isPopup"
                                location="end"
                                offset="8"
                            >
                                <template v-slot:activator="{ props }">
                                    <v-list-item
                                        v-bind="props"
                                        :prepend-icon="item.icon"
                                        class="my-1"
                                        link
                                        @click="handlePopupClick(item)"
                                    />
                                </template>
                                <span>{{ item.title }}</span>
                            </v-tooltip>
                            
                            <!-- Regular link menu item -->
                            <v-tooltip
                                v-else
                                location="end"
                                offset="8"
                            >
                                <template v-slot:activator="{ props }">
                                    <Link :href="getRouteUrl(item.url)" as="div">
                                        <v-list-item
                                            v-bind="props"
                                            :prepend-icon="item.icon"
                                            :active="isRouteActive(item.route)"
                                            class="my-1"
                                            link
                                        />
                                    </Link>
                                </template>
                                <span>{{ item.title }}</span>
                            </v-tooltip>
                        </template>
                    </template>
                </template>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar>
            <v-app-bar-nav-icon @click="toggleSidebar"></v-app-bar-nav-icon>
            <v-toolbar-title>Dashboard</v-toolbar-title>
        </v-app-bar>

        <v-main style="min-height: 100vh;" class="bg-grey-lighten-4">
            <slot />
            
            <!-- Flash Messages -->
            <v-snackbar v-model="snackbar" :timeout="3000" :color="flashColor" location="top right">
                {{ flashMessage }}
            </v-snackbar>
        </v-main>

        <!-- Popup Modal for menu items -->
        <v-dialog v-model="popupDialog" :max-width="popupSize" persistent>
            <v-card>
                <v-card-title class="d-flex align-center justify-space-between">
                    <span>{{ popupTitle }}</span>
                    <v-btn icon="mdi-close" variant="text" @click="closePopup"></v-btn>
                </v-card-title>
                
                <v-card-text>
                    <div v-if="popupType === 'quick_add_modal'">
                        <v-form @submit.prevent="handleQuickAdd">
                            <v-select
                                v-model="quickAddType"
                                :items="quickAddItems"
                                label="What would you like to add?"
                                variant="outlined"
                                class="mb-4"
                            ></v-select>
                            
                            <v-text-field
                                v-model="quickAddName"
                                label="Name"
                                variant="outlined"
                                class="mb-4"
                            ></v-text-field>
                            
                            <div class="d-flex justify-end gap-2">
                                <v-btn variant="text" @click="closePopup">Cancel</v-btn>
                                <v-btn color="primary" type="submit">Add</v-btn>
                            </div>
                        </v-form>
                    </div>
                    
                    <div v-else>
                        <p>Popup content for: {{ popupTitle }}</p>
                        <p>Type: {{ popupType }}</p>
                    </div>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-app>
</template>

<script setup>
import { ref, watch } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';

const page = usePage();
const menu = page.props.menu;

// Drawer state  
const drawer = ref(true);
const sidebarMini = ref(false);

// Flash message state
const snackbar = ref(false);
const flashMessage = ref('');
const flashColor = ref('success');

// Popup state
const popupDialog = ref(false);
const popupTitle = ref('');
const popupType = ref('');
const popupSize = ref('500px');

// Quick add form state
const quickAddType = ref('');
const quickAddName = ref('');
const quickAddItems = [
    { title: 'Product', value: 'product' },
    { title: 'Category', value: 'category' },
    { title: 'Restaurant', value: 'restaurant' },
];

// Handle popup menu clicks
const handlePopupClick = (item) => {
    popupTitle.value = item.title;
    popupType.value = item.extra?.popup_type || 'default';
    popupSize.value = item.extra?.popup_size === 'large' ? '800px' : 
                     item.extra?.popup_size === 'small' ? '400px' : '500px';
    popupDialog.value = true;
};

// Close popup
const closePopup = () => {
    popupDialog.value = false;
    // Reset form
    quickAddType.value = '';
    quickAddName.value = '';
};

// Toggle sidebar (full/mini only - never hide)
const toggleSidebar = () => {
    sidebarMini.value = !sidebarMini.value;
    drawer.value = true; // Always keep drawer open
};

// Handle quick add form submission
const handleQuickAdd = () => {
    if (!quickAddType.value || !quickAddName.value) {
        return;
    }

    // Route to appropriate create page based on type
    const routes = {
        product: 'products.create',
        category: 'dashboard.categories.create',
        restaurant: 'dashboard.restaurants.create',
    };

    const targetRoute = routes[quickAddType.value];
    if (targetRoute) {
        // You can pass the name as a query parameter or session data
        router.visit(route(targetRoute), {
            data: { name: quickAddName.value },
            method: 'get'
        });
    }
    
    closePopup();
};

// Helper function to get route URL (handles both route names and URLs)
const getRouteUrl = (urlOrRoute) => {
    if (!urlOrRoute) return '#';
    
    // If it's already a URL (starts with # or http), return as is
    if (urlOrRoute.startsWith('#') || urlOrRoute.startsWith('http')) {
        return urlOrRoute;
    }
    
    // Otherwise, treat it as a route name and generate URL
    try {
        return route(urlOrRoute);
    } catch (e) {
        console.warn('Could not generate route for:', urlOrRoute, e);
        return '#';
    }
};

// Helper function to check if route is active (handles wildcards)
const isRouteActive = (routePattern) => {
    if (!routePattern) return false;
    
    try {
        const currentRoute = route().current();
        if (!currentRoute) return false;
        
        // Handle wildcard patterns (e.g., "products.*")
        if (routePattern.includes('*')) {
            const basePattern = routePattern.replace('*', '');
            return currentRoute.startsWith(basePattern);
        }
        
        // Handle exact route matching
        // Use try-catch to avoid Ziggy errors for invalid routes
        try {
            return route().current(routePattern);
        } catch (e) {
            // Fallback to string matching if Ziggy can't handle the pattern
            return currentRoute === routePattern;
        }
    } catch (e) {
        console.warn('Route matching error:', e);
        return false;
    }
};

// Watch for flash messages
watch(() => page.props.flash, (flash) => {
    if (flash.success) {
        flashMessage.value = flash.success;
        flashColor.value = 'success';
        snackbar.value = true;
    } else if (flash.error) {
        flashMessage.value = flash.error;
        flashColor.value = 'error';
        snackbar.value = true;
    }
}, { deep: true });
</script>

<style scoped>
/* Mini sidebar styles */
.v-navigation-drawer--rail {
    overflow: visible !important;
}

/* Force rail mode list items to show icons only */
.v-navigation-drawer--rail .v-list-item {
    justify-content: center !important;
    padding: 8px !important;
    min-height: 48px !important;
    margin: 4px 8px !important;
    border-radius: 8px !important;
}

/* Hide text content in rail mode */
.v-navigation-drawer--rail .v-list-item__content {
    display: none !important;
}

.v-navigation-drawer--rail .v-list-item__append {
    display: none !important;
}

/* Center the icon */
.v-navigation-drawer--rail .v-list-item__prepend {
    margin-inline-end: 0 !important;
    margin-inline-start: 0 !important;
    align-self: center !important;
}

/* Ensure all icons are visible */
.v-navigation-drawer--rail .v-icon {
    opacity: 1 !important;
    display: flex !important;
    font-size: 24px !important;
}

/* Force display of icons in rail mode */
.v-navigation-drawer--rail .v-list-item .v-icon {
    visibility: visible !important;
}

/* Submenu popup styling */
.submenu-popup {
    min-width: 200px;
    max-width: 280px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    background: white;
    border: 1px solid rgba(0, 0, 0, 0.12);
}

.submenu-popup .v-list-subheader {
    font-weight: 600;
    color: #1976d2;
    background-color: #f8f9fa;
    padding: 12px 16px;
    margin: 0;
    border-radius: 8px 8px 0 0;
    border-bottom: 1px solid #e0e0e0;
}

/* Smooth transitions */
.v-navigation-drawer {
    transition: width 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

/* Custom tooltip styling */
.v-tooltip > .v-overlay__content {
    background: rgba(33, 33, 33, 0.9) !important;
    color: white !important;
    padding: 8px 12px !important;
    border-radius: 6px !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) !important;
}

/* Menu transition animations */
.slide-x-transition-enter-active,
.slide-x-transition-leave-active {
    transition: all 0.2s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.slide-x-transition-enter-from {
    opacity: 0;
    transform: translateX(-10px);
}

.slide-x-transition-leave-to {
    opacity: 0;
    transform: translateX(-10px);
}

/* Hover effects */
.v-list-item:hover {
    background-color: rgba(25, 118, 210, 0.08);
}

/* Ensure proper spacing in mini mode */
.v-navigation-drawer--rail .v-list {
    padding: 8px 0;
}

.v-navigation-drawer--rail .v-list-item {
    margin: 4px 8px;
    border-radius: 8px;
}
</style>
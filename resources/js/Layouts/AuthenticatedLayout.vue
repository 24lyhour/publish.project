1


<template>
    <v-app>
        <v-navigation-drawer v-model="drawer" width="280">
            <v-list nav>
                <template v-for="(item, i) in menu" :key="i">
                    <!-- Menu item with submenus -->
                    <v-list-group v-if="item.hasSubmenu" :value="item.id">
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

                    <!-- Regular menu item (no submenus) -->
                    <template v-else>
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
                </template>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar>
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
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
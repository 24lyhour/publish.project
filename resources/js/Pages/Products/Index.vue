<template>
    <AuthenticatedLayout title="Products">
        <v-container class="pa-6" fluid>
            <!-- Simple Header Section -->
            <div class="mb-6">
                <div class="d-flex align-center justify-space-between mb-4">
                    <div class="d-flex align-center">
                        <v-icon icon="mdi-package-variant-closed" size="32" color="primary" class="me-3"></v-icon>
                        <div>
                            <h1 class="text-h4 font-weight-bold mb-1">Products</h1>
                            <p class="text-subtitle-1 text-grey-darken-1">Manage your product inventory</p>
                        </div>
                    </div>
                    <Link :href="route('products.create')">
                    <v-btn color="primary" size="large" variant="flat" prepend-icon="mdi-plus">
                        Create Product
                    </v-btn>
                    </Link>
                </div>
            </div>

            <div>
                <!-- Success/Error Messages (No changes here) -->
                <v-expand-transition>
                    <v-alert v-if="$page.props.flash.success" color="success" variant="elevated" prominent closable
                        class="mb-6 success-alert" border="start" border-color="success">
                        <template #prepend>
                            <v-icon icon="mdi-check-circle-outline" size="24"></v-icon>
                        </template>
                        <div class="d-flex align-center">
                            <div>
                                <div class="text-h6 font-weight-bold mb-1">Success!</div>
                                <div class="text-body-1">{{ $page.props.flash.success }}</div>
                            </div>
                        </div>
                    </v-alert>
                </v-expand-transition>

                <v-expand-transition>
                    <v-alert v-if="$page.props.flash.error" color="error" variant="elevated" prominent closable
                        class="mb-6" border="start" border-color="error">
                        <template #prepend>
                            <v-icon icon="mdi-alert-circle-outline" size="24"></v-icon>
                        </template>
                        <div class="d-flex align-center">
                            <div>
                                <div class="text-h6 font-weight-bold mb-1">Error!</div>
                                <div class="text-body-1">{{ $page.props.flash.error }}</div>
                            </div>
                        </div>
                    </v-alert>
                </v-expand-transition>
            </div>

            <!-- Statistics Cards with Skeleton Loading -->
            <v-row class="mb-6">
                <v-col cols="12" md="3">
                    <v-card class="elevation-0" rounded="lg">
                        <v-card-text class="text-center pa-4">
                            <template v-if="loading">
                                <v-skeleton-loader type="avatar" class="mb-2"></v-skeleton-loader>
                                <v-skeleton-loader type="text" class="mb-1"></v-skeleton-loader>
                                <v-skeleton-loader type="text" width="60%"></v-skeleton-loader>
                            </template>
                            <template v-else>
                                <v-icon size="28" color="primary" class="mb-2">mdi-package-variant</v-icon>
                                <div class="text-h5 font-weight-bold text-primary">{{ totalProducts }}</div>
                                <div class="text-body-2 text-grey-darken-1">Total Products</div>
                            </template>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="3">
                    <v-card class="elevation-0" rounded="lg">
                        <v-card-text class="text-center pa-4">
                            <template v-if="loading">
                                <v-skeleton-loader type="avatar" class="mb-2"></v-skeleton-loader>
                                <v-skeleton-loader type="text" class="mb-1"></v-skeleton-loader>
                                <v-skeleton-loader type="text" width="60%"></v-skeleton-loader>
                            </template>
                            <template v-else>
                                <v-icon size="28" color="success" class="mb-2">mdi-currency-usd</v-icon>
                                <div class="text-h5 font-weight-bold text-success">${{ averagePrice }}</div>
                                <div class="text-body-2 text-grey-darken-1">Average Price</div>
                            </template>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="3">
                    <v-card class="elevation-0" rounded="lg">
                        <v-card-text class="text-center pa-4">
                            <template v-if="loading">
                                <v-skeleton-loader type="avatar" class="mb-2"></v-skeleton-loader>
                                <v-skeleton-loader type="text" class="mb-1"></v-skeleton-loader>
                                <v-skeleton-loader type="text" width="60%"></v-skeleton-loader>
                            </template>
                            <template v-else>
                                <v-icon size="28" color="warning" class="mb-2">mdi-trending-up</v-icon>
                                <div class="text-h5 font-weight-bold text-warning">${{ highestPrice }}</div>
                                <div class="text-body-2 text-grey-darken-1">Highest Price</div>
                            </template>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="3">
                    <v-card class="elevation-0" rounded="lg">
                        <v-card-text class="text-center pa-4">
                            <template v-if="loading">
                                <v-skeleton-loader type="avatar" class="mb-2"></v-skeleton-loader>
                                <v-skeleton-loader type="text" class="mb-1"></v-skeleton-loader>
                                <v-skeleton-loader type="text" width="60%"></v-skeleton-loader>
                            </template>
                            <template v-else>
                                <v-icon size="28" color="info" class="mb-2">mdi-trending-down</v-icon>
                                <div class="text-h5 font-weight-bold text-info">${{ lowestPrice }}</div>
                                <div class="text-body-2 text-grey-darken-1">Lowest Price</div>
                            </template>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>

            <!-- Cool Filters Layout -->
            <v-card class="mb-6 elevation-0 filters-card" rounded="lg">
                <v-card-title class="d-flex align-center pa-4 bg-grey-lighten-5">
                    <v-icon icon="mdi-filter-variant" class="me-2" color="primary"></v-icon>
                    <span class="text-h6 font-weight-bold">Filters</span>
                    <v-spacer></v-spacer>
                    <v-btn icon="mdi-refresh" variant="text" size="small" @click="clearFilters" color="primary">
                        <v-tooltip activator="parent">Clear All Filters</v-tooltip>
                    </v-btn>
                </v-card-title>
                <v-card-text class="pa-4">
                    <template v-if="loading">
                        <v-row>
                            <v-col cols="12" md="3">
                                <v-skeleton-loader type="text-field"></v-skeleton-loader>
                            </v-col>
                            <v-col cols="12" md="2">
                                <v-skeleton-loader type="text-field"></v-skeleton-loader>
                            </v-col>
                            <v-col cols="12" md="2">
                                <v-skeleton-loader type="text-field"></v-skeleton-loader>
                            </v-col>
                            <v-col cols="12" md="2">
                                <v-skeleton-loader type="text-field"></v-skeleton-loader>
                            </v-col>
                            <v-col cols="12" md="2">
                                <v-skeleton-loader type="text-field"></v-skeleton-loader>
                            </v-col>
                            <v-col cols="12" md="1">
                                <v-skeleton-loader type="text-field"></v-skeleton-loader>
                            </v-col>
                        </v-row>
                    </template>
                    <template v-else>
                        <v-row class="filter-row">
                            <v-col cols="12" md="3">
                                <v-text-field v-model="searchQuery" label="Search products..."
                                    prepend-inner-icon="mdi-magnify" variant="outlined" density="comfortable" clearable
                                    hide-details class="filter-input"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="2">
                                <v-select v-model="categoryFilter" :items="categoryOptions" label="Category"
                                    prepend-inner-icon="mdi-tag" variant="outlined" density="comfortable" clearable
                                    hide-details class="filter-input"></v-select>
                            </v-col>
                            <v-col cols="12" md="2">
                                <v-select v-model="priceFilter" :items="priceRanges" label="Price Range"
                                    prepend-inner-icon="mdi-currency-usd" variant="outlined" density="comfortable" clearable
                                    hide-details class="filter-input"></v-select>
                            </v-col>
                            <v-col cols="12" md="2">
                                <v-select v-model="statusFilter" :items="statusOptions" label="Status"
                                    prepend-inner-icon="mdi-check-circle" variant="outlined" density="comfortable" clearable
                                    hide-details class="filter-input"></v-select>
                            </v-col>
                            <v-col cols="12" md="2">
                                <v-select v-model="sortBy" :items="sortOptions" label="Sort by"
                                    prepend-inner-icon="mdi-sort" variant="outlined" density="comfortable"
                                    hide-details class="filter-input"></v-select>
                            </v-col>
                            <v-col cols="12" md="1">
                                <v-btn-toggle v-model="sortDesc" variant="outlined" density="comfortable" class="sort-toggle">
                                    <v-btn :value="false" icon="mdi-sort-ascending" size="small">
                                        <v-tooltip activator="parent">Ascending</v-tooltip>
                                    </v-btn>
                                    <v-btn :value="true" icon="mdi-sort-descending" size="small">
                                        <v-tooltip activator="parent">Descending</v-tooltip>
                                    </v-btn>
                                </v-btn-toggle>
                            </v-col>
                        </v-row>
                    </template>
                </v-card-text>
            </v-card>

            <!-- Cool Products Table -->
            <v-card class="elevation-0 table-card" rounded="lg">
                <v-card-title class="d-flex align-center justify-space-between pa-4 bg-grey-lighten-5">
                    <div class="d-flex align-center">
                        <v-icon icon="mdi-table" class="me-2" color="primary"></v-icon>
                        <span class="text-h6 font-weight-bold">Products Table</span>
                        <template v-if="loading">
                            <v-skeleton-loader type="chip" class="ml-4" width="80px"></v-skeleton-loader>
                        </template>
                        <template v-else>
                            <v-chip class="ml-4" color="primary" size="small" variant="flat">
                                {{ filteredProducts.length }} items
                            </v-chip>
                        </template>
                    </div>
                    <div class="d-flex align-center gap-2">
                        <v-btn-toggle v-model="viewMode" variant="outlined" size="small" class="view-toggle me-2">
                            <v-btn value="table" icon="mdi-table" size="small">
                                <v-tooltip activator="parent">Table View</v-tooltip>
                            </v-btn>
                            <v-btn value="cards" icon="mdi-view-grid" size="small">
                                <v-tooltip activator="parent">Card View</v-tooltip>
                            </v-btn>
                        </v-btn-toggle>
                        <v-btn-toggle v-model="tableDensity" variant="outlined" size="small" class="density-toggle">
                            <v-btn value="compact" icon="mdi-view-compact" size="small">
                                <v-tooltip activator="parent">Compact</v-tooltip>
                            </v-btn>
                            <v-btn value="comfortable" icon="mdi-view-comfortable" size="small">
                                <v-tooltip activator="parent">Comfortable</v-tooltip>
                            </v-btn>
                            <v-btn value="default" icon="mdi-view-module" size="small">
                                <v-tooltip activator="parent">Default</v-tooltip>
                            </v-btn>
                        </v-btn-toggle>
                    </div>
                </v-card-title>

                <!-- Table View -->
                <v-data-table v-if="viewMode === 'table'" v-model:search="searchQuery" v-model:sort-by="tableSortBy" :headers="headers"
                    :items="filteredProducts" :items-per-page="itemsPerPage" :loading="loading" :density="tableDensity"
                    :items-per-page-options="itemsPerPageOptions" class="elevation-0" hover
                    :height="tableHeight">

                    <template #loading>
                        <v-skeleton-loader type="table-row@6"></v-skeleton-loader>
                    </template>

                    <template #item.product="{ item }">
                        <div class="d-flex align-center" :class="getProductCellClass">
                            <v-avatar :image="item.imageUrl || undefined" :size="getAvatarSize" class="me-3 elevation-1"
                                rounded="lg">
                                <v-icon v-if="!item.imageUrl" icon="mdi-package-variant" color="grey"></v-icon>
                            </v-avatar>
                            <div>
                                <div :class="getProductNameClass">{{ item.name }}</div>
                                <div v-if="showProductDetails" class="text-caption text-grey-darken-1 mt-1">
                                    ID: {{ item.id }} • Created: {{ formatDate(item.created_at) }}
                                </div>
                            </div>
                        </div>
                    </template>

                    <template #item.price="{ item }">
                        <v-chip :color="getPriceColor(item.price)" variant="flat" :size="getPriceChipSize"
                            class="font-weight-bold text-white">
                            <v-icon start icon="mdi-currency-usd" :size="getPriceIconSize"></v-icon>
                            {{ formatPrice(item.price) }}
                        </v-chip>
                    </template>

                    <template #item.description="{ item }">
                        <div class="description-cell">
                            <p :class="getDescriptionClass">
                                {{ truncateText(item.description, getDescriptionLength) }}
                            </p>
                            <v-tooltip v-if="item.description && item.description.length > getDescriptionLength"
                                activator="parent" max-width="300px">
                                {{ item.description }}
                            </v-tooltip>
                        </div>
                    </template>

                    <template #item.stock_status="{ item }">
                        <v-chip :color="getStockColor(item.quantity || 0)" variant="tonal" :size="getChipSize"
                            class="font-weight-medium">
                            {{ getStockStatus(item.quantity || 0) }}
                        </v-chip>
                    </template>

                    <template #item.category="{ item }">
                        <v-chip color="blue-grey" variant="flat" :size="getChipSize" class="text-white">
                            {{ item.category || 'No Category' }}
                        </v-chip>
                    </template>

                    <template #item.actions="{ item }">
                        <!-- This container now uses the dynamic class -->
                        <div class="d-flex align-center" :class="getActionClass">
                            <v-tooltip text="View Details">
                                <template #activator="{ props: tooltipProps }">
                                    <v-btn v-bind="tooltipProps" color="grey-darken-3" variant="flat"
                                        :size="getActionButtonSize" icon @click="viewProduct(item)">
                                        <v-icon icon="mdi-eye" :size="getActionIconSize" color="white"></v-icon>
                                    </v-btn>
                                </template>
                            </v-tooltip>
                            <v-tooltip text="Edit Product">
                                <template #activator="{ props: tooltipProps }">
                                    <v-btn v-bind="tooltipProps" color="black" variant="flat"
                                        :size="getActionButtonSize" icon @click="openEditModal(item)">
                                        <v-icon icon="mdi-pencil" :size="getActionIconSize" color="white"></v-icon>
                                    </v-btn>
                                </template>
                            </v-tooltip>
                            <v-tooltip text="Delete Product">
                                <template #activator="{ props: tooltipProps }">
                                    <v-btn v-bind="tooltipProps" color="error" variant="flat"
                                        :size="getActionButtonSize" icon @click="openDeleteModal(item)">
                                        <v-icon icon="mdi-delete" :size="getActionIconSize" color="white"></v-icon>
                                    </v-btn>
                                </template>
                            </v-tooltip>
                        </div>
                    </template>

                    <template #no-data>
                        <div class="text-center py-12">
                            <v-icon size="64" color="grey-lighten-1" class="mb-4">mdi-magnify-close</v-icon>
                            <h3 class="text-h6 text-black mb-2">No Products Found</h3>
                            <p class="text-body-2 text-grey-darken-1">Your search or filter returned no results.</p>
                            <v-btn color="black" variant="elevated" @click="clearFilters" class="mt-4">
                                Clear Filters & Search
                            </v-btn>
                        </div>
                    </template>
                </v-data-table>

                <!-- Card View -->
                <div v-else-if="viewMode === 'cards'" class="pa-4">
                    <template v-if="loading">
                        <v-row>
                            <v-col v-for="n in 8" :key="n" cols="12" sm="6" md="4" lg="3">
                                <v-skeleton-loader type="card" class="mb-4"></v-skeleton-loader>
                            </v-col>
                        </v-row>
                    </template>
                    <template v-else-if="filteredProducts.length">
                        <v-row>
                            <v-col v-for="product in filteredProducts" :key="product.id" cols="12" sm="6" md="4" lg="3">
                                <v-card class="product-card hover-card" rounded="lg" elevation="0">
                                    <div class="product-image-container">
                                        <v-img :src="product.imageUrl || '/placeholder-image.jpg'" 
                                               :alt="product.name" 
                                               aspect-ratio="1" 
                                               cover 
                                               class="product-card-image">
                                            <template #placeholder>
                                                <div class="d-flex align-center justify-center fill-height">
                                                    <v-icon icon="mdi-package-variant" size="48" color="grey-lighten-2"></v-icon>
                                                </div>
                                            </template>
                                        </v-img>
                                        <div class="product-overlay">
                                            <v-btn icon="mdi-eye" variant="flat" color="white" size="small" 
                                                   @click="viewProduct(product)" class="overlay-btn">
                                            </v-btn>
                                            <v-btn icon="mdi-pencil" variant="flat" color="primary" size="small" 
                                                   @click="openEditModal(product)" class="overlay-btn">
                                            </v-btn>
                                            <v-btn icon="mdi-delete" variant="flat" color="error" size="small" 
                                                   @click="openDeleteModal(product)" class="overlay-btn">
                                            </v-btn>
                                        </div>
                                    </div>
                                    <v-card-text class="pa-4">
                                        <div class="d-flex align-center justify-space-between mb-2">
                                            <v-chip :color="getStockColor(product.quantity || 0)" 
                                                    variant="tonal" size="small" class="font-weight-medium">
                                                {{ getStockStatus(product.quantity || 0) }}
                                            </v-chip>
                                            <v-chip :color="getPriceColor(product.price)" 
                                                    variant="flat" size="small" class="font-weight-bold text-white">
                                                <v-icon start icon="mdi-currency-usd" size="14"></v-icon>
                                                {{ formatPrice(product.price) }}
                                            </v-chip>
                                        </div>
                                        <h3 class="text-subtitle-1 font-weight-bold mb-1 product-name">
                                            {{ product.name }}
                                        </h3>
                                        <p class="text-body-2 text-grey-darken-1 mb-2 product-description">
                                            {{ truncateText(product.description, 80) }}
                                        </p>
                                        <div class="d-flex align-center justify-space-between">
                                            <v-chip color="blue-grey" variant="outlined" size="small">
                                                {{ product.category || 'No Category' }}
                                            </v-chip>
                                            <span class="text-caption text-grey-darken-1">
                                                ID: {{ product.id }}
                                            </span>
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </template>
                    <template v-else>
                        <div class="text-center py-12">
                            <v-icon size="64" color="grey-lighten-1" class="mb-4">mdi-magnify-close</v-icon>
                            <h3 class="text-h6 text-black mb-2">No Products Found</h3>
                            <p class="text-body-2 text-grey-darken-1">Your search or filter returned no results.</p>
                            <v-btn color="black" variant="elevated" @click="clearFilters" class="mt-4">
                                Clear Filters & Search
                            </v-btn>
                        </div>
                    </template>
                </div>
            </v-card>
            <!-- END OF MODIFIED SECTION -->


            <!-- Modals (No changes here, they are fine) -->
            <v-dialog v-model="isEditModalOpen" max-width="600" persistent>
                <v-card class="elevation-8" rounded="lg">
                    <v-card-title class="d-flex align-center bg-black text-white pa-6">
                        <v-icon icon="mdi-pencil" class="me-2" color="white"></v-icon>
                        Edit Product
                        <v-spacer></v-spacer>
                        <v-btn icon="mdi-close" variant="text" size="small" @click="closeEditModal"
                            class="text-white"></v-btn>
                    </v-card-title>
                    <v-card-text class="pa-6">
                        <form @submit.prevent="submitEdit">
                            <v-text-field v-model="editForm.name" label="Product Name" prepend-inner-icon="mdi-package"
                                variant="outlined" density="comfortable" :error-messages="editForm.errors.name" required
                                counter="255" class="mb-4"></v-text-field>

                            <v-text-field v-model="editForm.price" label="Price" type="number" step="0.01" min="0"
                                prepend-inner-icon="mdi-currency-usd" variant="outlined" density="comfortable"
                                :error-messages="editForm.errors.price" required class="mb-4"></v-text-field>

                            <v-textarea v-model="editForm.description" label="Description" prepend-inner-icon="mdi-text"
                                variant="outlined" rows="4" counter="1000" :error-messages="editForm.errors.description"
                                class="mb-4"></v-textarea>

                            <v-text-field v-model="editForm.image_url" label="Image URL (Optional)" type="url"
                                prepend-inner-icon="mdi-image" variant="outlined" density="comfortable"
                                :error-messages="editForm.errors.image_url"
                                placeholder="https://example.com/image.jpg"></v-text-field>
                        </form>
                    </v-card-text>
                    <v-card-actions class="pa-6 pt-0">
                        <v-spacer></v-spacer>
                        <v-btn variant="text" @click="closeEditModal" size="large">Cancel</v-btn>
                        <v-btn color="black" variant="elevated" @click="submitEdit" :loading="editForm.processing"
                            size="large" class="elevation-2 text-white">
                            <v-icon start>mdi-content-save</v-icon>
                            Save Changes
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-model="isDeleteModalOpen" max-width="500" persistent>
                <v-card class="elevation-8" rounded="lg">
                    <v-card-title class="d-flex align-center bg-error text-white pa-6">
                        <v-icon icon="mdi-alert-circle" class="me-2" color="white"></v-icon>
                        Confirm Deletion
                    </v-card-title>
                    <v-card-text class="pa-6">
                        <div class="text-center">
                            <v-icon size="64" color="error" class="mb-4">mdi-delete-alert</v-icon>
                            <h3 class="text-h6 mb-4 text-black">Are you sure?</h3>
                            <p class="text-body-1 text-grey-darken-1">
                                You are about to delete the product
                                <strong class="text-black">{{ productToDelete?.name }}</strong>.
                                This action cannot be undone.
                            </p>
                        </div>
                    </v-card-text>
                    <v-card-actions class="pa-6 pt-0">
                        <v-spacer></v-spacer>
                        <v-btn variant="text" @click="closeDeleteModal" size="large">Cancel</v-btn>
                        <v-btn color="error" variant="elevated" @click="submitDelete" :loading="deleteLoading"
                            size="large" class="elevation-2 text-white">
                            <v-icon start>mdi-delete</v-icon>
                            Delete Product
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

        </v-container>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

/**
 * Define Props
 * 
 * 
 */
const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
});

/**
 * Reactive Variables
 * 
 * 
 */
const searchQuery = ref(props.filters?.search || '');
const priceFilter = ref(props.filters?.price_range || null);
const categoryFilter = ref(props.filters?.category_id || null);
const statusFilter = ref(props.filters?.status || null);
const sortBy = ref(props.filters?.sort || 'name');
const sortDesc = ref(props.filters?.direction === 'desc');
const itemsPerPage = ref(10);
const loading = ref(false);
const deleteLoading = ref(false);
const tableDensity = ref('comfortable');
const viewMode = ref('table');

/**
 * Configuration Options
 * 
 * 
 */
const itemsPerPageOptions = [{ value: 5, title: '5' }, { value: 10, title: '10' }, { value: 25, title: '25' }, { value: 50, title: '50' }, { value: -1, title: 'All' }];
// Remove densityOptions since we're using button toggle now
const priceRanges = [{ title: 'Under $10', value: 'under-10' }, { title: '$10 - $50', value: '10-50' }, { title: '$50 - $100', value: '50-100' }, { title: '$100 - $500', value: '100-500' }, { title: 'Over $500', value: 'over-500' },];
const sortOptions = [{ title: 'Name', value: 'name' }, { title: 'Price', value: 'price' }, { title: 'Date Created', value: 'created_at' },];
const statusOptions = [{ title: 'Active', value: 'active' }, { title: 'Inactive', value: 'inactive' },];
const categoryOptions = computed(() => {
    if (!props.categories) return [];
    return props.categories.map(cat => ({ title: cat.name, value: cat.id }));
});
const headers = [{ title: 'Product', key: 'product', align: 'start', sortable: true, width: '30%' }, { title: 'Price', key: 'price', align: 'center', sortable: true, width: '10%' }, { title: 'Description', key: 'description', align: 'start', sortable: false, width: '25%' }, { title: 'Stock', key: 'stock_status', align: 'center', sortable: true, width: '10%' }, { title: 'Category', key: 'category', align: 'center', sortable: true, width: '10%' }, { title: 'Actions', key: 'actions', sortable: false, align: 'center', width: '15%' },];
const tableSortBy = ref([{ key: 'name', order: 'asc' }]);

/**
 * Modal method
 * 
 */
const isEditModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const productToDelete = ref(null);

/**
 * Use Form
 * 
 * 
 */
const editForm = useForm({ id: null, name: '', price: '', description: '', image_url: '', });

/**
 * Computed Properties for Stats & Filtering
 * 
 * 
 */
// Remove currentHeaders since we only use one set of headers now
const totalProducts = computed(() => props.products.data?.length || 0);
const averagePrice = computed(() => { if (!totalProducts.value) return '0.00'; const total = props.products.data.reduce((sum, p) => sum + parseFloat(p.price), 0); return (total / totalProducts.value).toFixed(2); });
const highestPrice = computed(() => { if (!totalProducts.value) return '0.00'; return Math.max(...props.products.data.map(p => parseFloat(p.price))).toFixed(2); });
const lowestPrice = computed(() => { if (!totalProducts.value) return '0.00'; return Math.min(...props.products.data.map(p => parseFloat(p.price))).toFixed(2); });
const filteredProducts = computed(() => {
    return props.products.data || [];
});

// Debounced search function
const debouncedSearch = ref(null);
const applyFilters = () => {
    const filters = {
        search: searchQuery.value,
        category_id: categoryFilter.value,
        price_range: priceFilter.value,
        status: statusFilter.value,
        sort: sortBy.value,
        direction: sortDesc.value ? 'desc' : 'asc',
    };

    // Remove empty filters
    Object.keys(filters).forEach(key => {
        if (!filters[key]) {
            delete filters[key];
        }
    });

    router.get(route('products.index'), filters, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Watch for filter changes with loading state
watch([searchQuery, categoryFilter, priceFilter, statusFilter, sortBy, sortDesc], () => {
    if (debouncedSearch.value) {
        clearTimeout(debouncedSearch.value);
    }
    
    loading.value = true;
    debouncedSearch.value = setTimeout(() => {
        applyFilters();
        setTimeout(() => {
            loading.value = false;
        }, 500);
    }, 300);
});

/**
 * Table Height
 * 
 * 
 */
const tableHeight = computed(() => {
    switch (tableDensity.value) {
        case 'compact': return '450px';
        case 'comfortable': return '550px';
        default: return '650px';
    }
});

/**
 * Get Product Cell Class
 * 
 * 
 */
const getProductCellClass = computed(() => {
    // Controls the vertical padding of the product cell
    switch (tableDensity.value) {
        case 'compact': return 'py-1';
        case 'comfortable': return 'py-2';
        default: return 'py-4';
    }
});

/**
 * Get Avatar Size
 * 
 * 
 * 
 */
const getAvatarSize = computed(() => {
    switch (tableDensity.value) {
        case 'compact': return 32;
        case 'comfortable': return 40;
        default: return 56;
    }
});

/**
 * 
 * Get Product Name Class
 * 
 * 
 */
const getProductNameClass = computed(() => {
    // Controls the font size and weight of the product name
    switch (tableDensity.value) {
        case 'compact': return 'text-body-2 font-weight-bold text-black';
        case 'comfortable': return 'text-subtitle-1 font-weight-bold text-black';
        default: return 'text-h6 font-weight-bold text-black';
    }
});

/**
 * Computed Properties for Product Details
 * 
 */
const showProductDetails = computed(() => tableDensity.value !== 'compact');

/**
 * Get price ship Size & Icon Size
 * 
 */
const getPriceChipSize = computed(() => {
    switch (tableDensity.value) {
        case 'compact': return 'x-small';
        case 'comfortable': return 'small';
        default: return 'default';
    }
});

/**
 * Get Price Icon Size
 * 
 * 
 */
const getPriceIconSize = computed(() => {
    switch (tableDensity.value) {
        case 'compact': return 14;
        case 'comfortable': return 16;
        default: return 18;
    }
});

/**
 * Get Description Class & Length
 * 
 */
const getDescriptionClass = computed(() => {
    switch (tableDensity.value) {
        case 'compact': return 'text-caption text-grey-darken-1 mb-0';
        default: return 'text-body-2 text-grey-darken-1 mb-0';
    }
});

/**
 * Get Description Length
 * 
 * 
 */
const getDescriptionLength = computed(() => {
    switch (tableDensity.value) {
        case 'compact': return 40;
        case 'comfortable': return 70;
        default: return 100;
    }
});

/**
 * 
 * Get chip size & Action Button Size
 * 
 */
const getChipSize = computed(() => {
    // For Stock and Category chips
    switch (tableDensity.value) {
        case 'compact': return 'x-small';
        case 'comfortable': return 'small';
        default: return 'default';
    }
});

/**
 * Get Action Button Size & Icon Size 
 * 
 * Computed
 * 
 */
const getActionButtonSize = computed(() => {
    switch (tableDensity.value) {
        case 'compact': return 'x-small';
        case 'comfortable': return 'small';
        default: return 'small';
    }
});

/**
 * Get Action size & Icon Size
 * 
 * Computed
 * 
 */
const getActionIconSize = computed(() => {
    switch (tableDensity.value) {
        case 'compact': return 16;
        case 'comfortable': return 18;
        default: return 20;
    }
});

/**
 * This is the new computed property you requested, pro!
 * 
 * 
 */
const getActionClass = computed(() => {
    // Controls the spacing between action buttons
    switch (tableDensity.value) {
        case 'compact': return 'gap-1';
        case 'comfortable': return 'gap-2';
        default: return 'gap-2';
    }
});


/**
 * Helper & Event Handler Functions
 * 
 * @param dateString 
 * @returns 
 * 
 * 
 */
const formatDate = (dateString) => new Date(dateString).toLocaleDateString();
const formatPrice = (price) => parseFloat(price).toFixed(2);
const truncateText = (text, length) => (text && text.length > length) ? text.substring(0, length) + '...' : text;
const getPriceColor = (price) => {
    if (price > 100) return 'error';
    if (price > 50) return 'warning';
    if (price > 10) return 'primary';
    return 'success';
};

/**
 * Get stock status 
 * 
 * @param stock 
 * @returns 
 * 
 * 
 */
const getStockStatus = (stock) => {
    if (stock <= 0) return 'Out of Stock';
    if (stock < 10) return 'Low Stock';
    return 'In Stock';
};

/**
 * 
 * Get Stock Color
 * 
 * @param stock 
 * @returns 
 * 
 * 
 */
const getStockColor = (stock) => {
    if (stock <= 0) return 'error';
    if (stock < 10) return 'warning';
    return 'success';
};

/**
 * View emit product
 * 
 * 
 */
const clearFilters = () => {
    searchQuery.value = '';
    priceFilter.value = null;
    categoryFilter.value = null;
    statusFilter.value = null;
    sortBy.value = 'name';
    sortDesc.value = false;

    router.get(route('products.index'), {}, {
        preserveState: true,
        preserveScroll: true,
    });
};
const viewProduct = (product) => router.get(route('products.show', product.id));
const openEditModal = (product) => {
    editForm.reset();
    editForm.id = product.id;
    editForm.name = product.name;
    editForm.price = product.price;
    editForm.description = product.description;
    editForm.image_url = product.image_url;
    isEditModalOpen.value = true;
};

/**
 * Close Emit modal 
 * 
 * 
 */
const closeEditModal = () => { isEditModalOpen.value = false; editForm.reset(); };
const submitEdit = () => {
    editForm.put(route('products.update', editForm.id), {
        preserveScroll: true,
        onSuccess: () => closeEditModal(),
    });
};

/**
 * Handle delete modal
 * 
 * @param product 
 * @returns 
 * 
 * 
 */
const openDeleteModal = (product) => { productToDelete.value = product; isDeleteModalOpen.value = true; };
const closeDeleteModal = () => { isDeleteModalOpen.value = false; productToDelete.value = null; };
const submitDelete = () => {
    if (!productToDelete.value) return;
    deleteLoading.value = true;
    router.delete(route('products.destroy', productToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => closeDeleteModal(),
        onFinish: () => deleteLoading.value = false,
    });
};
</script>

<style scoped>
/* Cool, smooth styles with no shadows */
.v-btn {
    border-radius: 8px !important;
    text-transform: none !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
}

.v-chip {
    border-radius: 6px !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
}

.v-card {
    border: 1px solid #e0e0e0 !important;
    box-shadow: none !important;
    transition: all 0.3s ease !important;
}

.v-card:hover {
    border-color: #d0d0d0 !important;
    box-shadow: none !important;
    transform: translateY(-1px);
}

/* Remove all elevations */
.elevation-0 {
    box-shadow: none !important;
}

/* Cool filter styling */
.filters-card {
    overflow: hidden;
}

.filter-row {
    transition: all 0.3s ease;
}

.filter-input {
    transition: all 0.3s ease;
}

.filter-input:focus-within {
    transform: translateY(-2px);
}

.sort-toggle {
    border-radius: 8px !important;
    transition: all 0.3s ease;
}

.density-toggle {
    border-radius: 8px !important;
    transition: all 0.3s ease;
}

.view-toggle {
    border-radius: 8px !important;
    transition: all 0.3s ease;
}

/* Table styling */
.table-card {
    overflow: hidden;
}

.v-data-table {
    border-radius: 0 0 8px 8px;
    box-shadow: none !important;
}

.v-data-table .v-data-table__tr:hover {
    background: #f5f5f5 !important;
    transform: translateX(2px);
    transition: all 0.3s ease;
}

/* Skeleton loading animations */
.v-skeleton-loader {
    border-radius: 8px !important;
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
    0% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
    100% {
        opacity: 1;
    }
}

/* Gap Utilities */
.gap-1 {
    gap: 0.25rem;
}

.gap-2 {
    gap: 0.5rem;
}

/* Smooth transitions for all interactive elements */
.v-btn-toggle .v-btn {
    transition: all 0.3s ease !important;
}

.v-btn-toggle .v-btn:hover {
    background: rgba(25, 118, 210, 0.1) !important;
}

.v-text-field:focus-within,
.v-select:focus-within {
    transform: translateY(-1px);
}

/* Product Card Styles */
.product-card {
    border: 1px solid #e0e0e0 !important;
    box-shadow: none !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    overflow: hidden;
}

.product-card:hover {
    border-color: #d0d0d0 !important;
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.product-image-container {
    position: relative;
    overflow: hidden;
}

.product-card-image {
    transition: transform 0.3s ease;
}

.product-card:hover .product-card-image {
    transform: scale(1.05);
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.overlay-btn {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
    transition: all 0.2s ease !important;
}

.overlay-btn:hover {
    transform: scale(1.1);
}

.product-name {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
    height: 2.8em;
}

.product-description {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
    height: 2.8em;
}

/* Responsive adjustments for cards */
@media (max-width: 960px) {
    .product-card:hover {
        transform: translateY(-2px);
    }
    
    .product-overlay {
        opacity: 1;
        background: rgba(0, 0, 0, 0.4);
    }
}

@media (max-width: 600px) {
    .product-card:hover {
        transform: none;
    }
    
    .product-card:hover .product-card-image {
        transform: scale(1.02);
    }
}
</style>
<template>
    <AuthenticatedLayout title="Products">
        <v-container class="pa-6" fluid>
            <!-- Hero Header Section (No changes here) -->
            <div class="mb-8">
                <div class="d-flex align-center justify-space-between mb-4">
                    <div class="d-flex align-center">
                        <v-avatar class="me-4 bg-black" size="48">
                            <v-icon icon="mdi-package-variant-closed" size="24" color="white"></v-icon>
                        </v-avatar>
                        <div>
                            <h1 class="text-h3 font-weight-bold text-black mb-1">Products</h1>
                            <p class="text-subtitle-1 text-grey-darken-1">Manage your product inventory</p>
                        </div>
                    </div>
                    <Link :href="route('products.create')">
                    <v-btn color="black" size="large" variant="elevated" prepend-icon="mdi-plus"
                        class="elevation-4 text-white">
                        Create Product
                    </v-btn>
                    </Link>
                </div>

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

            <!-- Advanced Filters & Density Controls (No changes here) -->
            <v-card class="mb-6 elevation-2" rounded="lg">
                <v-card-title class="d-flex align-center bg-black text-white">
                    <v-icon icon="mdi-filter-variant" class="me-2" color="white"></v-icon>
                    Advanced Filters & Display Settings
                    <v-spacer></v-spacer>
                    <v-btn icon="mdi-refresh" variant="text" size="small" @click="clearFilters" class="text-white">
                        <v-tooltip activator="parent">Clear All Filters</v-tooltip>
                    </v-btn>
                </v-card-title>
                <v-card-text class="pa-4">
                    <v-row>
                        <v-col cols="12" md="3">
                            <v-text-field v-model="searchQuery" label="Search products..."
                                prepend-inner-icon="mdi-magnify" variant="outlined" density="comfortable" clearable
                                hide-details></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-select v-model="priceFilter" :items="priceRanges" label="Price Range"
                                prepend-inner-icon="mdi-currency-usd" variant="outlined" density="comfortable" clearable
                                hide-details></v-select>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-select v-model="sortBy" :items="sortOptions" label="Sort by"
                                prepend-inner-icon="mdi-sort" variant="outlined" density="comfortable"
                                hide-details></v-select>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-select v-model="tableDensity" :items="densityOptions" label="Table Density"
                                prepend-inner-icon="mdi-table-row-height" variant="outlined" density="comfortable"
                                hide-details></v-select>
                        </v-col>
                        <v-col cols="12" md="" class="d-flex align-center">
                            <v-switch v-model="sortDesc" label="Descending" color="primary" hide-details
                                density="comfortable"></v-switch>
                        </v-col>
                        <v-col cols="12" md="" class="d-flex align-center">
                            <v-switch v-model="showAdvancedView" label="Advance" color="black" hide-details
                                density="comfortable"></v-switch>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>

            <!-- Products Statistics Cards (No changes here) -->
            <v-row class="mb-6">
                <v-col cols="12" md="3">
                    <v-card class="elevation-2" rounded="lg" hover>
                        <v-card-text class="text-center pa-4">
                            <v-icon size="32" color="primary" class="mb-2">mdi-package-variant</v-icon>
                            <div class="text-h4 font-weight-bold text-black">{{ totalProducts }}</div>
                            <div class="text-caption text-grey-darken-1">Total Products</div>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="3">
                    <v-card class="elevation-2" rounded="lg" hover>
                        <v-card-text class="text-center pa-4">
                            <v-icon size="32" color="success" class="mb-2">mdi-currency-usd</v-icon>
                            <div class="text-h4 font-weight-bold text-black">${{ averagePrice }}</div>
                            <div class="text-caption text-grey-darken-1">Average Price</div>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="3">
                    <v-card class="elevation-2" rounded="lg" hover>
                        <v-card-text class="text-center pa-4">
                            <v-icon size="32" color="warning" class="mb-2">mdi-trending-up</v-icon>
                            <div class="text-h4 font-weight-bold text-black">${{ highestPrice }}</div>
                            <div class="text-caption text-grey-darken-1">Highest Price</div>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="3">
                    <v-card class="elevation-2" rounded="lg" hover>
                        <v-card-text class="text-center pa-4">
                            <v-icon size="32" color="info" class="mb-2">mdi-trending-down</v-icon>
                            <div class="text-h4 font-weight-bold text-black">${{ lowestPrice }}</div>
                            <div class="text-caption text-grey-darken-1">Lowest Price</div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>

            <!-- START OF MODIFIED SECTION -->
            <v-card class="elevation-4" rounded="lg">
                <v-card-title class="d-flex align-center justify-space-between pa-4 bg-grey-lighten-5">
                    <div class="d-flex align-center">
                        <v-icon icon="mdi-table" class="me-2"></v-icon>
                        <span class="text-h6 font-weight-bold">Products Table</span>
                        <v-chip class="ml-4" color="primary" size="small">
                            {{ filteredProducts.length }} items
                        </v-chip>
                    </div>
                </v-card-title>

                <v-data-table v-model:search="searchQuery" v-model:sort-by="tableSortBy" :headers="currentHeaders"
                    :items="filteredProducts" :items-per-page="itemsPerPage" :loading="loading" :density="tableDensity"
                    :items-per-page-options="itemsPerPageOptions" class="elevation-0" hover fixed-header
                    :height="tableHeight">

                    <template #loading>
                        <v-skeleton-loader type="table-row@6"></v-skeleton-loader>
                    </template>

                    <template #item.product="{ item }">
                        <div class="d-flex align-center" :class="getProductCellClass">
                            <v-avatar :image="item.image_url || undefined" :size="getAvatarSize"
                                class="me-3 elevation-1" rounded="lg">
                                <v-icon v-if="!item.image_url" icon="mdi-package-variant" color="grey"></v-icon>
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
                        <v-chip :color="getStockColor(item.stock || 0)" variant="tonal" :size="getChipSize"
                            class="font-weight-medium">
                            {{ getStockStatus(item.stock || 0) }}
                        </v-chip>
                    </template>

                    <template #item.category="{ item }">
                        <v-chip color="blue-grey" variant="flat" :size="getChipSize" class="text-white">
                            {{ item.category || 'N/A' }}
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

// Props
const props = defineProps({
    products: Object,
});

// Reactive State
const searchQuery = ref('');
const priceFilter = ref(null);
const sortBy = ref('name');
const sortDesc = ref(false);
const itemsPerPage = ref(10);
const loading = ref(false);
const deleteLoading = ref(false);
const tableDensity = ref('comfortable');
const showAdvancedView = ref(false);

// Configuration Options
const itemsPerPageOptions = [{ value: 5, title: '5' }, { value: 10, title: '10' }, { value: 25, title: '25' }, { value: 50, title: '50' }, { value: -1, title: 'All' }];
const densityOptions = [{ title: 'Compact', value: 'compact' }, { title: 'Comfortable', value: 'comfortable' }, { title: 'Default', value: 'default' }];
const priceRanges = [{ title: 'Under $10', value: 'under-10' }, { title: '$10 - $50', value: '10-50' }, { title: '$50 - $100', value: '50-100' }, { title: '$100 - $500', value: '100-500' }, { title: 'Over $500', value: 'over-500' },];
const sortOptions = [{ title: 'Name', value: 'name' }, { title: 'Price', value: 'price' }, { title: 'Date Created', value: 'created_at' },];
const basicHeaders = [{ title: 'Product', key: 'product', align: 'start', sortable: true, width: '35%' }, { title: 'Price', key: 'price', align: 'center', sortable: true, width: '15%' }, { title: 'Description', key: 'description', align: 'start', sortable: false, width: '35%' }, { title: 'Actions', key: 'actions', sortable: false, align: 'center', width: '15%' },];
const advancedHeaders = [{ title: 'Product', key: 'product', align: 'start', sortable: true, width: '30%' }, { title: 'Price', key: 'price', align: 'center', sortable: true, width: '10%' }, { title: 'Description', key: 'description', align: 'start', sortable: false, width: '25%' }, { title: 'Stock', key: 'stock_status', align: 'center', sortable: true, width: '10%' }, { title: 'Category', key: 'category', align: 'center', sortable: true, width: '10%' }, { title: 'Actions', key: 'actions', sortable: false, align: 'center', width: '15%' },];
const tableSortBy = ref([{ key: 'name', order: 'asc' }]);

// Modal States
const isEditModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const productToDelete = ref(null);

// Edit Form
const editForm = useForm({ id: null, name: '', price: '', description: '', image_url: '', });

// Computed Properties for Stats & Filtering
const currentHeaders = computed(() => showAdvancedView.value ? advancedHeaders : basicHeaders);
const totalProducts = computed(() => props.products.data?.length || 0);
const averagePrice = computed(() => { if (!totalProducts.value) return '0.00'; const total = props.products.data.reduce((sum, p) => sum + parseFloat(p.price), 0); return (total / totalProducts.value).toFixed(2); });
const highestPrice = computed(() => { if (!totalProducts.value) return '0.00'; return Math.max(...props.products.data.map(p => parseFloat(p.price))).toFixed(2); });
const lowestPrice = computed(() => { if (!totalProducts.value) return '0.00'; return Math.min(...props.products.data.map(p => parseFloat(p.price))).toFixed(2); });
const filteredProducts = computed(() => {
    if (!props.products.data) return [];
    if (!priceFilter.value) return props.products.data;
    return props.products.data.filter(p => {
        const price = parseFloat(p.price);
        switch (priceFilter.value) {
            case 'under-10': return price < 10;
            case '10-50': return price >= 10 && price <= 50;
            case '50-100': return price >= 50 && price <= 100;
            case '100-500': return price >= 100 && price <= 500;
            case 'over-500': return price > 500;
            default: return true;
        }
    });
});

// --- REFINED DENSITY-AWARE COMPUTED PROPERTIES ---

const tableHeight = computed(() => {
    switch (tableDensity.value) {
        case 'compact': return '450px';
        case 'comfortable': return '550px';
        default: return '650px';
    }
});

const getProductCellClass = computed(() => {
    // Controls the vertical padding of the product cell
    switch (tableDensity.value) {
        case 'compact': return 'py-1';
        case 'comfortable': return 'py-2';
        default: return 'py-4';
    }
});

const getAvatarSize = computed(() => {
    switch (tableDensity.value) {
        case 'compact': return 32;
        case 'comfortable': return 40;
        default: return 56;
    }
});

const getProductNameClass = computed(() => {
    // Controls the font size and weight of the product name
    switch (tableDensity.value) {
        case 'compact': return 'text-body-2 font-weight-bold text-black';
        case 'comfortable': return 'text-subtitle-1 font-weight-bold text-black';
        default: return 'text-h6 font-weight-bold text-black';
    }
});

const showProductDetails = computed(() => tableDensity.value !== 'compact');

const getPriceChipSize = computed(() => {
    switch (tableDensity.value) {
        case 'compact': return 'x-small';
        case 'comfortable': return 'small';
        default: return 'default';
    }
});

const getPriceIconSize = computed(() => {
    switch (tableDensity.value) {
        case 'compact': return 14;
        case 'comfortable': return 16;
        default: return 18;
    }
});

const getDescriptionClass = computed(() => {
    switch (tableDensity.value) {
        case 'compact': return 'text-caption text-grey-darken-1 mb-0';
        default: return 'text-body-2 text-grey-darken-1 mb-0';
    }
});

const getDescriptionLength = computed(() => {
    switch (tableDensity.value) {
        case 'compact': return 40;
        case 'comfortable': return 70;
        default: return 100;
    }
});

const getChipSize = computed(() => {
    // For Stock and Category chips
    switch (tableDensity.value) {
        case 'compact': return 'x-small';
        case 'comfortable': return 'small';
        default: return 'default';
    }
});

const getActionButtonSize = computed(() => {
    switch (tableDensity.value) {
        case 'compact': return 'x-small';
        case 'comfortable': return 'small';
        default: return 'small';
    }
});

const getActionIconSize = computed(() => {
    switch (tableDensity.value) {
        case 'compact': return 16;
        case 'comfortable': return 18;
        default: return 20;
    }
});

// This is the new computed property you requested, pro!
const getActionClass = computed(() => {
    // Controls the spacing between action buttons
    switch (tableDensity.value) {
        case 'compact': return 'gap-1';
        case 'comfortable': return 'gap-2';
        default: return 'gap-2';
    }
});

// Helper & Event Handler Functions
const formatDate = (dateString) => new Date(dateString).toLocaleDateString();
const formatPrice = (price) => parseFloat(price).toFixed(2);
const truncateText = (text, length) => (text && text.length > length) ? text.substring(0, length) + '...' : text;
const getPriceColor = (price) => {
    if (price > 100) return 'error';
    if (price > 50) return 'warning';
    if (price > 10) return 'primary';
    return 'success';
};
const getStockStatus = (stock) => {
    if (stock <= 0) return 'Out of Stock';
    if (stock < 10) return 'Low Stock';
    return 'In Stock';
};
const getStockColor = (stock) => {
    if (stock <= 0) return 'error';
    if (stock < 10) return 'warning';
    return 'success';
};
const clearFilters = () => { searchQuery.value = ''; priceFilter.value = null; sortBy.value = 'name'; sortDesc.value = false; };
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
const closeEditModal = () => { isEditModalOpen.value = false; editForm.reset(); };
const submitEdit = () => {
    editForm.put(route('products.update', editForm.id), {
        preserveScroll: true,
        onSuccess: () => closeEditModal(),
    });
};
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
/* A simple CSS helper for gap spacing, as Vuetify 3's gap classes can be limited */
.gap-1 {
    gap: 0.25rem;
    /* 4px */
}

.gap-2 {
    gap: 0.5rem;
    /* 8px */
}
</style>
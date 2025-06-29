<template>
    <AuthenticatedLayout title="Products">
        <v-container class="pa-6" fluid>
            <!-- Hero Header Section -->
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

                <!-- Success Message with Enhanced Styling -->
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

                <!-- Error Message -->
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

            <!-- Advanced Filters Card -->
            <v-card class="mb-6 elevation-2" rounded="lg">
                <v-card-title class="d-flex align-center bg-black text-white">
                    <v-icon icon="mdi-filter-variant" class="me-2" color="white"></v-icon>
                    Advanced Filters
                    <v-spacer></v-spacer>
                    <v-btn icon="mdi-refresh" variant="text" size="small" @click="clearFilters" class="text-white">
                        <v-tooltip activator="parent">Clear All Filters</v-tooltip>
                    </v-btn>
                </v-card-title>
                <v-card-text class="pa-4">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field v-model="searchQuery" label="Search products..."
                                prepend-inner-icon="mdi-magnify" variant="outlined" density="comfortable" clearable
                                hide-details></v-text-field>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-select v-model="priceFilter" :items="priceRanges" label="Price Range"
                                prepend-inner-icon="mdi-currency-usd" variant="outlined" density="comfortable" clearable
                                hide-details></v-select>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-select v-model="sortBy" :items="sortOptions" label="Sort by"
                                prepend-inner-icon="mdi-sort" variant="outlined" density="comfortable"
                                hide-details></v-select>
                        </v-col>
                        <v-col cols="12" md="2" class="d-flex align-center">
                            <v-switch v-model="sortDesc" label="Descending" color="primary" hide-details
                                density="comfortable"></v-switch>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>

            <!-- Products Statistics Cards -->
            <v-row class="mb-6">
                <v-col cols="12" md="3">
                    <v-card class="elevation-2" rounded="lg">
                        <v-card-text class="text-center pa-4">
                            <v-icon size="32" color="primary" class="mb-2">mdi-package-variant</v-icon>
                            <div class="text-h4 font-weight-bold text-black">{{ totalProducts }}</div>
                            <div class="text-caption text-grey-darken-1">Total Products</div>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="3">
                    <v-card class="elevation-2" rounded="lg">
                        <v-card-text class="text-center pa-4">
                            <v-icon size="32" color="success" class="mb-2">mdi-currency-usd</v-icon>
                            <div class="text-h4 font-weight-bold text-black">${{ averagePrice }}</div>
                            <div class="text-caption text-grey-darken-1">Average Price</div>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="3">
                    <v-card class="elevation-2" rounded="lg">
                        <v-card-text class="text-center pa-4">
                            <v-icon size="32" color="warning" class="mb-2">mdi-trending-up</v-icon>
                            <div class="text-h4 font-weight-bold text-black">${{ highestPrice }}</div>
                            <div class="text-caption text-grey-darken-1">Highest Price</div>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="3">
                    <v-card class="elevation-2" rounded="lg">
                        <v-card-text class="text-center pa-4">
                            <v-icon size="32" color="info" class="mb-2">mdi-trending-down</v-icon>
                            <div class="text-h4 font-weight-bold text-black">${{ lowestPrice }}</div>
                            <div class="text-caption text-grey-darken-1">Lowest Price</div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>

            <!-- Products Data Table -->
            <v-card class="elevation-4" rounded="lg">
                <v-data-table v-model:search="searchQuery" v-model:sort-by="tableSortBy" :headers="headers"
                    :items="filteredProducts" :items-per-page="itemsPerPage" :loading="loading" class="elevation-0"
                    hover :items-per-page-options="itemsPerPageOptions">

                    <!-- Loading Slot -->
                    <template #loading>
                        <v-skeleton-loader type="table-row@6"></v-skeleton-loader>
                    </template>

                    <!-- Custom Product Cell -->
                    <template #item.product="{ item }">
                        <div class="d-flex align-center py-3">
                            <v-avatar :image="item.image_url || 'https://via.placeholder.com/150'" size="56"
                                class="me-4 elevation-2" rounded="lg">
                                <v-icon v-if="!item.image_url" icon="mdi-package" color="grey-darken-1"></v-icon>
                            </v-avatar>
                            <div>
                                <div class="text-h6 font-weight-bold text-black">{{ item.name }}</div>
                                <div class="text-caption text-grey-darken-1">ID: {{ item.id }}</div>
                                <div class="text-caption text-grey-darken-1">
                                    Created: {{ formatDate(item.created_at) }}
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Custom Price Cell -->
                    <template #item.price="{ item }">
                        <v-chip :color="getPriceColor(item.price)" variant="flat" size="large"
                            class="font-weight-bold text-white">
                            <v-icon start icon="mdi-currency-usd" color="white"></v-icon>
                            {{ formatPrice(item.price) }}
                        </v-chip>
                    </template>

                    <!-- Custom Description Cell -->
                    <template #item.description="{ item }">
                        <div class="description-cell">
                            <p class="text-body-2 mb-0" style="max-width: 300px; line-height: 1.4;">
                                {{ truncateText(item.description, 100) }}
                            </p>
                            <v-tooltip v-if="item.description && item.description.length > 100" activator="parent">
                                {{ item.description }}
                            </v-tooltip>
                        </div>
                    </template>

                    <!-- Custom Actions Cell -->
                    <template #item.actions="{ item }">
                        <div class="d-flex gap-2 justify-center">
                            <v-btn color="grey-darken-3" variant="flat" size="small" icon="mdi-eye"
                                @click="viewProduct(item)" class="elevation-1 text-white">
                                <v-icon color="white"></v-icon>
                                <v-tooltip activator="parent">View Details</v-tooltip>
                            </v-btn>
                            <v-btn color="black" variant="flat" size="small" icon="mdi-pencil"
                                @click="openEditModal(item)" class="elevation-1 text-white">
                                <v-icon color="white"></v-icon>
                                <v-tooltip activator="parent">Edit Product</v-tooltip>
                            </v-btn>
                            <v-btn color="error" variant="outlined" size="small" icon="mdi-delete"
                                @click="openDeleteModal(item)" class="elevation-1">
                                <v-icon></v-icon>
                                <v-tooltip activator="parent">Delete Product</v-tooltip>
                            </v-btn>
                        </div>
                    </template>

                    <!-- No Data Slot -->
                    <template #no-data>
                        <div class="text-center py-8">
                            <v-icon size="64" color="grey-lighten-1" class="mb-4">mdi-package-variant</v-icon>
                            <h3 class="text-h6 text-black mb-2">No Products Found</h3>
                            <p class="text-body-2 text-grey-darken-1">Try adjusting your search criteria</p>
                            <v-btn color="black" variant="elevated" @click="clearFilters" class="mt-4">
                                Clear Filters
                            </v-btn>
                        </div>
                    </template>

                    <!-- Custom Footer -->
                    <template #bottom>
                        <div class="d-flex align-center justify-space-between pa-4">
                            <div class="text-body-2 text-medium-emphasis">
                                Showing {{ Math.min(itemsPerPage, filteredProducts.length) }} of {{
                                    filteredProducts.length }} products
                            </div>
                        </div>
                    </template>
                </v-data-table>
            </v-card>

            <!-- Custom Pagination -->
            <div v-if="products.links && products.links.length > 3" class="mt-6 d-flex justify-center">
                <v-card class="elevation-2" rounded="pill">
                    <div class="d-flex">
                        <Link v-for="(link, index) in products.links" :key="link.label" :href="link.url"
                            class="pa-3 text-decoration-none" :class="{
                                'bg-black text-white': link.active,
                                'text-black': !link.url && !link.active,
                                'text-grey-darken-1 hover-black': link.url && !link.active
                            }" :style="{
                                borderRadius: index === 0 ? '28px 0 0 28px' :
                                    index === products.links.length - 1 ? '0 28px 28px 0' : '0'
                            }" as="button" :disabled="!link.url" v-html="link.label" />
                    </div>
                </v-card>
            </div>
        </v-container>

        <!-- Enhanced Edit Product Modal -->
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
                    <form @submit.prevent="submitEdit" class="space-y-4">
                        <v-text-field v-model="editForm.name" label="Product Name" prepend-inner-icon="mdi-package"
                            variant="outlined" density="comfortable" :error-messages="editForm.errors.name" required
                            counter="255"></v-text-field>

                        <v-text-field v-model="editForm.price" label="Price" type="number" step="0.01" min="0"
                            prepend-inner-icon="mdi-currency-usd" variant="outlined" density="comfortable"
                            :error-messages="editForm.errors.price" required></v-text-field>

                        <v-textarea v-model="editForm.description" label="Description" prepend-inner-icon="mdi-text"
                            variant="outlined" rows="4" counter="1000"
                            :error-messages="editForm.errors.description"></v-textarea>

                        <v-text-field v-model="editForm.image_url" label="Image URL (Optional)" type="url"
                            prepend-inner-icon="mdi-image" variant="outlined" density="comfortable"
                            :error-messages="editForm.errors.image_url"
                            placeholder="https://example.com/image.jpg"></v-text-field>
                    </form>
                </v-card-text>

                <v-card-actions class="pa-6 pt-0">
                    <v-spacer></v-spacer>
                    <v-btn variant="text" @click="closeEditModal" size="large">
                        Cancel
                    </v-btn>
                    <v-btn color="black" variant="elevated" @click="submitEdit" :loading="editForm.processing"
                        size="large" class="elevation-2 text-white">
                        <v-icon start>mdi-content-save</v-icon>
                        Save Changes
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Enhanced Delete Confirmation Modal -->
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
                    <v-btn variant="text" @click="closeDeleteModal" size="large">
                        Cancel
                    </v-btn>
                    <v-btn color="error" variant="elevated" @click="submitDelete" :loading="deleteLoading" size="large"
                        class="elevation-2 text-white">
                        <v-icon start>mdi-delete</v-icon>
                        Delete Product
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

/**
 * Define props for this component
 */
const props = defineProps({
    products: Object,
});

/**
 * Reactive data
 */
const searchQuery = ref('');
const priceFilter = ref(null);
const sortBy = ref('name');
const sortDesc = ref(false);
const itemsPerPage = ref(10);
const loading = ref(false);
const deleteLoading = ref(false);

/**
 * Items per page options
 */
const itemsPerPageOptions = [
    { value: 5, title: '5' },
    { value: 10, title: '10' },
    { value: 25, title: '25' },
    { value: 50, title: '50' },
    { value: -1, title: 'All' }
];

/**
 * Table headers configuration
 */
const headers = ref([
    { title: 'Product', key: 'product', align: 'start', sortable: true, width: '35%' },
    { title: 'Price', key: 'price', align: 'center', sortable: true, width: '15%' },
    { title: 'Description', key: 'description', align: 'start', sortable: false, width: '35%' },
    { title: 'Actions', key: 'actions', sortable: false, align: 'center', width: '15%' },
]);

const tableSortBy = ref([{ key: 'name', order: 'asc' }]);

/**
 * Price ranges for filtering
 */
const priceRanges = [
    { title: 'Under $10', value: 'under-10' },
    { title: '$10 - $50', value: '10-50' },
    { title: '$50 - $100', value: '50-100' },
    { title: '$100 - $500', value: '100-500' },
    { title: 'Over $500', value: 'over-500' },
];

/**
 * Sort options
 */
const sortOptions = [
    { title: 'Name', value: 'name' },
    { title: 'Price', value: 'price' },
    { title: 'Date Created', value: 'created_at' },
];

/**
 * Modal states
 */
const isEditModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const productToDelete = ref(null);

/**
 * Form state for editing
 */
const editForm = useForm({
    id: null,
    name: '',
    price: '',
    description: '',
    image_url: '',
});

/**
 * Computed properties for statistics
 */
const totalProducts = computed(() => {
    return props.products.data?.length || 0;
});

const averagePrice = computed(() => {
    if (!props.products.data || props.products.data.length === 0) return '0.00';
    const total = props.products.data.reduce((sum, product) => sum + parseFloat(product.price), 0);
    return (total / props.products.data.length).toFixed(2);
});

const highestPrice = computed(() => {
    if (!props.products.data || props.products.data.length === 0) return '0.00';
    const highest = Math.max(...props.products.data.map(product => parseFloat(product.price)));
    return highest.toFixed(2);
});

const lowestPrice = computed(() => {
    if (!props.products.data || props.products.data.length === 0) return '0.00';
    const lowest = Math.min(...props.products.data.map(product => parseFloat(product.price)));
    return lowest.toFixed(2);
});

/**
 * Filter products based on search and price filter
 */
const filteredProducts = computed(() => {
    if (!props.products.data) return [];

    let filtered = [...props.products.data];

    // Apply price filter
    if (priceFilter.value) {
        filtered = filtered.filter(product => {
            const price = parseFloat(product.price);
            switch (priceFilter.value) {
                case 'under-10': return price < 10;
                case '10-50': return price >= 10 && price <= 50;
                case '50-100': return price >= 50 && price <= 100;
                case '100-500': return price >= 100 && price <= 500;
                case 'over-500': return price > 500;
                default: return true;
            }
        });
    }

    return filtered;
});

/**
 * Watch for sort changes
 */
watch([sortBy, sortDesc], ([newSortBy, newSortDesc]) => {
    tableSortBy.value = [{ key: newSortBy, order: newSortDesc ? 'desc' : 'asc' }];
});

/**
 * Utility functions
 */
const clearFilters = () => {
    searchQuery.value = '';
    priceFilter.value = null;
    sortBy.value = 'name';
    sortDesc.value = false;
};

/**
 * 
 * @param price 
 * 
 * @returns {string}
 * 
 * Format Price Function
 */
const formatPrice = (price) => {
    return parseFloat(price).toFixed(2);
};

/**
 * 
 * @param date 
 * 
 * @returns {string}
 * 
 * Format Date Function
 */
const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString();
};

/**
 * 
 * @param price 
 * 
 * @returns {string}
 * 
 * Get Price Color Function
 */
const getPriceColor = (price) => {
    const numPrice = parseFloat(price);
    if (numPrice < 10) return 'grey-darken-1';
    if (numPrice < 50) return 'grey-darken-2';
    if (numPrice < 100) return 'grey-darken-3';
    if (numPrice < 500) return 'black';
    return 'grey-darken-4';
};

/**
 * 
 * @param text 
 * 
 * @param length 
 * 
 * @returns {string}
 * 
 * Truncate Text Function
 */
const truncateText = (text, length) => {
    if (!text) return '';
    if (text.length <= length) return text;
    return text.substring(0, length) + '...';
};

/**
 * Product actions
 * 
 * @param product 
 * 
 * @returns {void}
 * 
 * View Product Function
 */
const viewProduct = (product) => {
    router.visit(route('products.show', product.id));
};

/**
 * 
 * @param product 
 * 
 * @returns {void}
 * 
 * Open Edit Modal Function
 * 
 */
const openEditModal = (product) => {
    editForm.id = product.id;
    editForm.name = product.name;
    editForm.price = product.price;
    editForm.description = product.description || '';
    editForm.image_url = product.image_url || '';
    editForm.clearErrors();
    isEditModalOpen.value = true;
};

/**
 * Close Edit Modal Function
 * 
 * @returns {void}
 * 
 * 
 */
const closeEditModal = () => {
    isEditModalOpen.value = false;
    editForm.reset();
    editForm.clearErrors();
};

/**
 * Handle Submit Edit Function
 * 
 * @returns {void}  
 * 
 */
const submitEdit = () => {
    editForm.put(route('products.update', editForm.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeEditModal();
        },
        onError: (errors) => {
            console.log('Edit errors:', errors);
        }
    });
};

/**
 * 
 * @param product 
 * 
 * @returns {void}
 * 
 * Open Delete Modal Function
 */
const openDeleteModal = (product) => {
    productToDelete.value = product;
    isDeleteModalOpen.value = true;
};

/**
 * CLose Delete Modal Function
 * 
 * @returns {void}
 * 
 * 
 */
const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    productToDelete.value = null;
    deleteLoading.value = false;
};

/**
 * Submit delete functions
 * 
 * @returns {void}
 * 
 */
const submitDelete = () => {
    deleteLoading.value = true;
    router.delete(route('products.destroy', productToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
        },
        onError: () => {
            deleteLoading.value = false;
        },
        onFinish: () => {
            deleteLoading.value = false;
        }
    });
};

/**
 * Component mounted
 */
onMounted(() => {
    // Any initialization logic
    console.log('Products component mounted');
});
</script>

<style scoped>
/* Success Alert Custom Styling */
.success-alert {
    background: linear-gradient(135deg, #4caf50 0%, #45a049 100%) !important;
    color: white !important;
    border-left: 4px solid #2e7d32 !important;
}

.success-alert .v-alert__content {
    color: white !important;
}

.success-alert .v-icon {
    color: white !important;
}

/* Hover Effects */
.hover-black:hover {
    color: black !important;
    background-color: rgba(0, 0, 0, 0.05);
}

.description-cell {
    max-width: 300px;
}

/* Card Animations */
.v-card {
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.v-card:hover {
    transform: translateY(-2px);
}

/* Button Animations */
.v-btn {
    transition: all 0.2s ease-in-out;
}

.v-btn:hover {
    transform: translateY(-1px);
}

/* Data Table Row Hover */
.v-data-table :deep(.v-data-table__tr:hover) {
    background-color: rgba(0, 0, 0, 0.04) !important;
}

/* Avatar Hover Effect */
.v-avatar {
    transition: all 0.3s ease;
}

.v-avatar:hover {
    transform: scale(1.05);
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Statistics Cards Animation */
.v-col .v-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.v-col .v-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

/* Loading Animation */
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

.v-skeleton-loader {
    animation: pulse 1.5s ease-in-out infinite;
}

/* Modal Animations */
.v-dialog .v-card {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Alert Transitions */
.v-expand-transition-enter-active,
.v-expand-transition-leave-active {
    transition: all 0.3s ease;
}

.v-expand-transition-enter-from,
.v-expand-transition-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}
</style>
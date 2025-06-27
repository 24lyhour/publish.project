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

                <!-- Success Message with Animation -->
                <v-expand-transition>
                    <v-alert v-if="$page.props.flash.success" color="black" variant="tonal" prominent closable
                        class="mb-6 text-white" border="start">
                        <template #prepend>
                            <v-icon icon="mdi-check-circle" color="white"></v-icon>
                        </template>
                        {{ $page.props.flash.success }}
                    </v-alert>
                </v-expand-transition>
            </div>

            <!-- Advanced Filters Card -->
            <v-card class="mb-6 elevation-2" rounded="lg">
                <v-card-title class="d-flex align-center bg-black text-white">
                    <v-icon icon="mdi-filter-variant" class="me-2" color="white"></v-icon>
                    Advanced Filters
                    <v-spacer></v-spacer>
                    <v-btn icon="mdi-refresh" variant="text" size="small" @click="clearFilters"
                        class="text-white"></v-btn>
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

            <!-- Products Data Table -->
            <v-card class="elevation-4" rounded="lg">
                <v-data-table v-model:search="searchQuery" v-model:sort-by="tableSortBy" :headers="headers"
                    :items="filteredProducts" :items-per-page="itemsPerPage" :loading="loading" class="elevation-0"
                    hover>
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
                            <v-btn color="grey-darken-1" variant="outlined" size="small" icon="mdi-delete"
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
            <div class="mt-6 d-flex justify-center">
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
                    <v-btn icon="mdi-close" variant="text" size="small" @click="isEditModalOpen = false"
                        class="text-white"></v-btn>
                </v-card-title>

                <v-card-text class="pa-6">
                    <form @submit.prevent="submitEdit" class="space-y-4">
                        <v-text-field v-model="editForm.name" label="Product Name" prepend-inner-icon="mdi-package"
                            variant="outlined" density="comfortable" :error-messages="editForm.errors.name"
                            required></v-text-field>

                        <v-text-field v-model="editForm.price" label="Price" type="number" step="0.01"
                            prepend-inner-icon="mdi-currency-usd" variant="outlined" density="comfortable"
                            :error-messages="editForm.errors.price" required></v-text-field>

                        <v-textarea v-model="editForm.description" label="Description" prepend-inner-icon="mdi-text"
                            variant="outlined" rows="4" :error-messages="editForm.errors.description"></v-textarea>
                    </form>
                </v-card-text>

                <v-card-actions class="pa-6 pt-0">
                    <v-spacer></v-spacer>
                    <v-btn variant="text" @click="isEditModalOpen = false" size="large">
                        Cancel
                    </v-btn>
                    <v-btn color="black" variant="elevated" @click="submitEdit" :loading="editForm.processing"
                        size="large" class="elevation-2 text-white">
                        Save Changes
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Enhanced Delete Confirmation Modal -->
        <v-dialog v-model="isDeleteModalOpen" max-width="500" persistent>
            <v-card class="elevation-8" rounded="lg">
                <v-card-title class="d-flex align-center bg-grey-darken-3 text-white pa-6">
                    <v-icon icon="mdi-alert-circle" class="me-2" color="white"></v-icon>
                    Confirm Deletion
                </v-card-title>

                <v-card-text class="pa-6">
                    <div class="text-center">
                        <v-icon size="64" color="grey-darken-2" class="mb-4">mdi-delete-alert</v-icon>
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
                    <v-btn variant="text" @click="isDeleteModalOpen = false" size="large">
                        Cancel
                    </v-btn>
                    <v-btn color="grey-darken-3" variant="elevated" @click="submitDelete" size="large"
                        class="elevation-2 text-white">
                        Delete Product
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

/**
 * Define props for this component
 * 
 * Products Object
 * 
 * @param products 
 * 
 */
const props = defineProps({
    products: Object,
});

/**
 * 
 * @param query 
 * @param priceFilter 
 * @param sortBy 
 * @param sortDesc 
 * @param itemsPerPage 
 * @param loading 
 * 
 */
const searchQuery = ref('');
const priceFilter = ref(null);
const sortBy = ref('name');
const sortDesc = ref(false);
const itemsPerPage = ref(10);
const loading = ref(false);

/**
 * 
 * @param product 
 * 
 * View Product
 * 
 * 
 */
const headers = ref([
    { title: 'Product', key: 'product', align: 'start', sortable: true, width: '30%' },
    { title: 'Price', key: 'price', align: 'center', sortable: true, width: '15%' },
    { title: 'Description', key: 'description', align: 'start', sortable: false, width: '40%' },
    { title: 'Actions', key: 'actions', sortable: false, align: 'center', width: '15%' },
]);

const tableSortBy = ref([{ key: 'name', order: 'asc' }]);

/**
 * Price Ranges
 * 
 * @param title 
 * @param value 
 * 
 */
const priceRanges = [
    { title: 'Under $10', value: 'under-10' },
    { title: '$10 - $50', value: '10-50' },
    { title: '$50 - $100', value: '50-100' },
    { title: '$100 - $500', value: '100-500' },
    { title: 'Over $500', value: 'over-500' },
];

/**
 * Sort Options
 * 
 * @param sortBy 
 * @param sortDesc 
 * 
 * 
 */
const sortOptions = [
    { title: 'Name', value: 'name' },
    { title: 'Price', value: 'price' },
    { title: 'Date Created', value: 'created_at' },
];

/**
 * 
 * @param product 
 * 
 * View Product
 * 
 */
const isEditModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const productToDelete = ref(null);

/**
 * Form State
 * 
 * @param id 
 * @param name 
 * @param price 
 * @param description 
 * 
 */
const editForm = useForm({
    id: null,
    name: '',
    price: '',
    description: '',
});

/**
 * Filter Products
 * 
 * @param product 
 * @param query 
 * 
 * 
 */
const filteredProducts = computed(() => {
    let filtered = [...props.products.data];

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
 * Watch search query
 * 
 * @param query 
 * 
 * Watch Search Query
 * 
 */
watch([sortBy, sortDesc], ([newSortBy, newSortDesc]) => {
    tableSortBy.value = [{ key: newSortBy, order: newSortDesc ? 'desc' : 'asc' }];
});

/**
 * 
 * @param event 
 * 
 * Handle Search Query Change
 * 
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
 * Format Price
 * 
 */
const formatPrice = (price) => {
    return parseFloat(price).toFixed(2);
};

/**
 * get Price Color
 * 
 * 
 * @param price 
 * 
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
 * @param length
 * 
 * Truncate Text 
 */
const truncateText = (text, length) => {
    if (!text) return '';
    if (text.length <= length) return text;
    return text.substring(0, length) + '...';
};

/**
 * 
 * @param product 
 * 
 * View Product
 */
const viewProduct = (product) => {
    // Implement view logic or navigation
    router.visit(route('products.show', product.id));
};

/**
 * 
 * @param product 
 * 
 * Open Edit Modal
 */
const openEditModal = (product) => {
    editForm.id = product.id;
    editForm.name = product.name;
    editForm.price = product.price;
    editForm.description = product.description;
    editForm.errors = {};
    isEditModalOpen.value = true;
};

/**
 * Submit Edit
 * 
 * 
 */
const submitEdit = () => {
    editForm.put(route('products.update', editForm.id), {
        preserveScroll: true,
        onSuccess: () => {
            isEditModalOpen.value = false;
            editForm.reset();
        },
    });
};

/**
 * 
 * @param product 
 * 
 * Open Delete Modal
 * 
 */
const openDeleteModal = (product) => {
    productToDelete.value = product;
    isDeleteModalOpen.value = true;
};

/**
 * Submit Delete
 * 
 * 
 */
const submitDelete = () => {
    router.delete(route('products.destroy', productToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            isDeleteModalOpen.value = false;
            productToDelete.value = null;
        },
    });
};
</script>

<style scoped>
.hover-black:hover {
    color: black !important;
    background-color: rgba(0, 0, 0, 0.05);
}

.description-cell {
    max-width: 300px;
}

.v-card {
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.v-card:hover {
    transform: translateY(-2px);
}

.v-btn {
    transition: all 0.2s ease-in-out;
}

.v-btn:hover {
    transform: translateY(-1px);
}

.v-data-table>>>.v-data-table__tr:hover {
    background-color: rgba(0, 0, 0, 0.04) !important;
}

.v-avatar {
    transition: all 0.3s ease;
}

.v-avatar:hover {
    transform: scale(1.05);
}

/* Custom scrollbar for webkit browsers */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
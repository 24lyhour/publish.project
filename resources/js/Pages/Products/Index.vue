<template>
    <AuthenticatedLayout title="Products">
        <div class="products-page">
            <!-- Modern Header -->
            <div class="page-header">
                <div class="header-content">
                    <div class="header-info">
                        <div class="header-icon">
                            <v-icon icon="mdi-package-variant-closed" size="40" color="primary"></v-icon>
                        </div>
                        <div>
                            <h1 class="page-title">Products</h1>
                            <p class="page-subtitle">Manage your product inventory with ease</p>
                        </div>
                    </div>
                    <v-btn color="primary" size="large" variant="elevated" class="create-btn" @click="createProduct">
                        <v-icon start>mdi-plus</v-icon>
                        Add Product
                    </v-btn>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <v-expand-transition>
                <div v-if="$page.props.flash.success || $page.props.flash.error" class="alerts-container">
                    <v-alert v-if="$page.props.flash.success" 
                             type="success" 
                             variant="tonal" 
                             prominent 
                             closable
                             class="alert-success">
                        {{ $page.props.flash.success }}
                    </v-alert>
                    <v-alert v-if="$page.props.flash.error" 
                             type="error" 
                             variant="tonal" 
                             prominent 
                             closable
                             class="alert-error">
                        {{ $page.props.flash.error }}
                    </v-alert>
                </div>
            </v-expand-transition>

            <!-- Statistics Dashboard -->
            <div class="stats-section">
                <v-row>
                    <v-col cols="12" sm="6" md="3">
                        <div class="stat-card">
                            <div class="stat-icon primary">
                                <v-icon icon="mdi-package-variant" size="24"></v-icon>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ totalProducts }}</div>
                                <div class="stat-label">Total Products</div>
                            </div>
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6" md="3">
                        <div class="stat-card">
                            <div class="stat-icon success">
                                <v-icon icon="mdi-currency-usd" size="24"></v-icon>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">${{ averagePrice }}</div>
                                <div class="stat-label">Average Price</div>
                            </div>
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6" md="3">
                        <div class="stat-card">
                            <div class="stat-icon warning">
                                <v-icon icon="mdi-trending-up" size="24"></v-icon>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">${{ highestPrice }}</div>
                                <div class="stat-label">Highest Price</div>
                            </div>
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6" md="3">
                        <div class="stat-card">
                            <div class="stat-icon info">
                                <v-icon icon="mdi-trending-down" size="24"></v-icon>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">${{ lowestPrice }}</div>
                                <div class="stat-label">Lowest Price</div>
                            </div>
                        </div>
                    </v-col>
                </v-row>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <div class="filters-header">
                    <h3 class="filters-title">
                        <v-icon icon="mdi-filter-variant" class="me-2"></v-icon>
                        Filters & Search
                    </h3>
                    <v-btn variant="text" size="small" @click="clearFilters" class="clear-btn">
                        <v-icon start>mdi-refresh</v-icon>
                        Clear All
                    </v-btn>
                </div>
                <div class="filters-content">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field 
                                v-model="searchQuery" 
                                label="Search products..." 
                                prepend-inner-icon="mdi-magnify" 
                                variant="outlined" 
                                density="comfortable" 
                                clearable
                                hide-details
                                class="search-field">
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-select 
                                v-model="categoryFilter" 
                                :items="categoryOptions" 
                                label="Category" 
                                variant="outlined" 
                                density="comfortable" 
                                clearable
                                hide-details>
                            </v-select>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-select 
                                v-model="priceFilter" 
                                :items="priceRanges" 
                                label="Price Range" 
                                variant="outlined" 
                                density="comfortable" 
                                clearable
                                hide-details>
                            </v-select>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-select 
                                v-model="statusFilter" 
                                :items="statusOptions" 
                                label="Status" 
                                variant="outlined" 
                                density="comfortable" 
                                clearable
                                hide-details>
                            </v-select>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-select 
                                v-model="sortBy" 
                                :items="sortOptions" 
                                label="Sort by" 
                                variant="outlined" 
                                density="comfortable"
                                hide-details>
                            </v-select>
                        </v-col>
                    </v-row>
                </div>
            </div>

            <!-- Products Section -->
            <div class="products-section">
                <div class="products-header">
                    <div class="products-info">
                        <h3 class="products-title">
                            <v-icon icon="mdi-view-grid" class="me-2"></v-icon>
                            Products
                        </h3>
                        <v-chip color="primary" size="small" variant="tonal">
                            {{ filteredProducts.length }} items
                        </v-chip>
                    </div>
                    <div class="view-controls">
                        <v-btn-toggle v-model="viewMode" variant="outlined" density="comfortable">
                            <v-btn value="grid" icon="mdi-view-grid" size="small">
                                <v-tooltip activator="parent">Grid View</v-tooltip>
                            </v-btn>
                            <v-btn value="list" icon="mdi-view-list" size="small">
                                <v-tooltip activator="parent">List View</v-tooltip>
                            </v-btn>
                        </v-btn-toggle>
                    </div>
                </div>

                <!-- Grid View -->
                <div v-if="viewMode === 'grid'" class="products-grid">
                    <template v-if="loading">
                        <div v-for="n in 8" :key="n" class="product-skeleton">
                            <v-skeleton-loader type="card"></v-skeleton-loader>
                        </div>
                    </template>
                    <template v-else-if="filteredProducts.length">
                        <div v-for="product in filteredProducts" :key="product.id" class="product-card">
                            <div class="product-image">
                                <v-img 
                                    :src="product.imageUrl || '/placeholder.jpg'" 
                                    :alt="product.name" 
                                    aspect-ratio="1" 
                                    cover>
                                    <template #placeholder>
                                        <div class="image-placeholder">
                                            <v-icon icon="mdi-package-variant" size="40" color="grey-lighten-2"></v-icon>
                                        </div>
                                    </template>
                                </v-img>
                                <div class="product-actions">
                                    <v-btn icon="mdi-eye" size="small" variant="elevated" @click="viewProduct(product)"></v-btn>
                                    <v-btn icon="mdi-pencil" size="small" variant="elevated" color="primary" @click="editProduct(product)"></v-btn>
                                    <v-btn icon="mdi-delete" size="small" variant="elevated" color="error" @click="deleteProduct(product)"></v-btn>
                                </div>
                            </div>
                            <div class="product-content">
                                <div class="product-badges">
                                    <v-chip 
                                        :color="getStockColor(product.quantity || 0)" 
                                        size="x-small" 
                                        variant="flat"
                                        class="stock-badge">
                                        {{ getStockStatus(product.quantity || 0) }}
                                    </v-chip>
                                    <v-chip 
                                        :color="getPriceColor(product.price)" 
                                        size="x-small" 
                                        variant="flat"
                                        class="price-badge">
                                        ${{ formatPrice(product.price) }}
                                    </v-chip>
                                </div>
                                <h4 class="product-name">{{ product.name }}</h4>
                                <p class="product-description">{{ truncateText(product.description, 60) }}</p>
                                <div class="product-meta">
                                    <span class="product-category">{{ product.category || 'Uncategorized' }}</span>
                                    <span class="product-id">ID: {{ product.id }}</span>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="empty-state">
                            <div class="empty-icon">
                                <v-icon icon="mdi-package-variant-closed" size="80" color="grey-lighten-2"></v-icon>
                            </div>
                            <h3 class="empty-title">No Products Found</h3>
                            <p class="empty-description">Try adjusting your search or filters</p>
                            <v-btn color="primary" @click="clearFilters">Clear Filters</v-btn>
                        </div>
                    </template>
                </div>

                <!-- List View -->
                <div v-else class="products-list">
                    <template v-if="loading">
                        <div v-for="n in 6" :key="n" class="list-skeleton">
                            <v-skeleton-loader type="list-item-avatar-two-line"></v-skeleton-loader>
                        </div>
                    </template>
                    <template v-else-if="filteredProducts.length">
                        <div v-for="product in filteredProducts" :key="product.id" class="product-list-item">
                            <div class="list-item-avatar">
                                <v-avatar size="60" rounded="lg">
                                    <v-img :src="product.imageUrl || '/placeholder.jpg'" :alt="product.name">
                                        <template #placeholder>
                                            <v-icon icon="mdi-package-variant" size="30" color="grey-lighten-2"></v-icon>
                                        </template>
                                    </v-img>
                                </v-avatar>
                            </div>
                            <div class="list-item-content">
                                <div class="list-item-header">
                                    <h4 class="list-item-title">{{ product.name }}</h4>
                                    <div class="list-item-badges">
                                        <v-chip 
                                            :color="getPriceColor(product.price)" 
                                            size="small" 
                                            variant="flat"
                                            class="price-chip">
                                            ${{ formatPrice(product.price) }}
                                        </v-chip>
                                        <v-chip 
                                            :color="getStockColor(product.quantity || 0)" 
                                            size="small" 
                                            variant="tonal">
                                            {{ getStockStatus(product.quantity || 0) }}
                                        </v-chip>
                                    </div>
                                </div>
                                <p class="list-item-description">{{ truncateText(product.description, 100) }}</p>
                                <div class="list-item-meta">
                                    <span class="meta-item">Category: {{ product.category || 'Uncategorized' }}</span>
                                    <span class="meta-item">ID: {{ product.id }}</span>
                                    <span class="meta-item">Created: {{ formatDate(product.created_at) }}</span>
                                </div>
                            </div>
                            <div class="list-item-actions">
                                <v-btn icon="mdi-eye" size="small" variant="text" @click="viewProduct(product)"></v-btn>
                                <v-btn icon="mdi-pencil" size="small" variant="text" color="primary" @click="editProduct(product)"></v-btn>
                                <v-btn icon="mdi-delete" size="small" variant="text" color="error" @click="deleteProduct(product)"></v-btn>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="empty-state">
                            <div class="empty-icon">
                                <v-icon icon="mdi-package-variant-closed" size="80" color="grey-lighten-2"></v-icon>
                            </div>
                            <h3 class="empty-title">No Products Found</h3>
                            <p class="empty-description">Try adjusting your search or filters</p>
                            <v-btn color="primary" @click="clearFilters">Clear Filters</v-btn>
                        </div>
                    </template>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
});

// Reactive Variables
const searchQuery = ref(props.filters?.search || '');
const priceFilter = ref(props.filters?.price_range || null);
const categoryFilter = ref(props.filters?.category_id || null);
const statusFilter = ref(props.filters?.status || null);
const sortBy = ref(props.filters?.sort || 'name');
const sortDesc = ref(props.filters?.direction === 'desc');
const loading = ref(false);
const viewMode = ref('grid');

// Configuration Options
const priceRanges = [
    { title: 'Under $10', value: 'under-10' },
    { title: '$10 - $50', value: '10-50' },
    { title: '$50 - $100', value: '50-100' },
    { title: '$100 - $500', value: '100-500' },
    { title: 'Over $500', value: 'over-500' },
];

const sortOptions = [
    { title: 'Name', value: 'name' },
    { title: 'Price', value: 'price' },
    { title: 'Date Created', value: 'created_at' },
];

const statusOptions = [
    { title: 'Active', value: 'active' },
    { title: 'Inactive', value: 'inactive' },
];

const categoryOptions = computed(() => {
    if (!props.categories) return [];
    return props.categories.map(cat => ({ title: cat.name, value: cat.id }));
});

// Component state - keeping minimal state for UI only

// Computed Properties for Stats & Filtering
const totalProducts = computed(() => props.products.data?.length || 0);
const averagePrice = computed(() => {
    if (!totalProducts.value) return '0.00';
    const total = props.products.data.reduce((sum, p) => sum + parseFloat(p.price), 0);
    return (total / totalProducts.value).toFixed(2);
});
const highestPrice = computed(() => {
    if (!totalProducts.value) return '0.00';
    return Math.max(...props.products.data.map(p => parseFloat(p.price))).toFixed(2);
});
const lowestPrice = computed(() => {
    if (!totalProducts.value) return '0.00';
    return Math.min(...props.products.data.map(p => parseFloat(p.price))).toFixed(2);
});
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

// Helper Functions
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

// Event Handlers
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

// Event Handlers - Using Inertia modals
const createProduct = () => {
    router.get(route('products.create'));
};

const viewProduct = (product) => {
    router.get(route('products.show', product.id));
};

const editProduct = (product) => {
    router.get(route('products.edit', product.id));
};

const deleteProduct = (product) => {
    router.get(route('products.confirm-delete', product.id));
};
</script>

<style scoped>
.products-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 24px;
}

/* Header Styles */
.page-header {
    background: white;
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.header-info {
    display: flex;
    align-items: center;
    gap: 16px;
}

.header-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
    margin-bottom: 4px;
}

.page-subtitle {
    color: #718096;
    margin: 0;
    font-size: 1rem;
}

.create-btn {
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    border-radius: 12px !important;
}

/* Alerts */
.alerts-container {
    margin-bottom: 24px;
}

.alert-success,
.alert-error {
    border-radius: 12px;
    margin-bottom: 16px;
}

/* Stats Section */
.stats-section {
    margin-bottom: 24px;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.stat-icon.primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-icon.success { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stat-icon.warning { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
.stat-icon.info { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 4px;
}

.stat-label {
    color: #718096;
    font-size: 0.875rem;
}

/* Filters Section */
.filters-section {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.filters-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.filters-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
    display: flex;
    align-items: center;
}

.clear-btn {
    border-radius: 8px !important;
}

.filters-content {
    margin-top: 16px;
}

.search-field {
    border-radius: 12px;
}

/* Products Section */
.products-section {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.products-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}

.products-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.products-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
    display: flex;
    align-items: center;
}

.view-controls {
    border-radius: 8px;
}

/* Grid View */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.product-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    border-color: #cbd5e0;
}

.product-image {
    position: relative;
    overflow: hidden;
}

.image-placeholder {
    width: 100%;
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f7fafc;
}

.product-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    opacity: 0;
    transition: all 0.3s ease;
}

.product-card:hover .product-actions {
    opacity: 1;
}

.product-content {
    padding: 20px;
}

.product-badges {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
}

.stock-badge,
.price-badge {
    color: white !important;
    font-weight: 600;
}

.product-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 8px 0;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-description {
    color: #718096;
    margin: 0 0 12px 0;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.875rem;
}

.product-category {
    color: #4a5568;
    font-weight: 500;
}

.product-id {
    color: #a0aec0;
}

/* List View */
.products-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.product-list-item {
    background: #f7fafc;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
}

.product-list-item:hover {
    background: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateX(4px);
}

.list-item-avatar {
    flex-shrink: 0;
}

.list-item-content {
    flex: 1;
    min-width: 0;
}

.list-item-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
    flex-wrap: wrap;
    gap: 8px;
}

.list-item-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

.list-item-badges {
    display: flex;
    gap: 8px;
}

.price-chip {
    color: white !important;
    font-weight: 600;
}

.list-item-description {
    color: #718096;
    margin: 0 0 8px 0;
    line-height: 1.5;
}

.list-item-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.meta-item {
    font-size: 0.875rem;
    color: #a0aec0;
}

.list-item-actions {
    display: flex;
    gap: 4px;
    flex-shrink: 0;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    grid-column: 1 / -1;
}

.empty-icon {
    margin-bottom: 20px;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 8px 0;
}

.empty-description {
    color: #718096;
    margin: 0 0 24px 0;
}

/* Skeletons */
.product-skeleton,
.list-skeleton {
    border-radius: 16px;
    overflow: hidden;
}


/* Responsive Design */
@media (max-width: 768px) {
    .products-page {
        padding: 16px;
    }

    .page-header {
        padding: 20px;
    }

    .header-content {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }

    .products-grid {
        grid-template-columns: 1fr;
    }

    .product-list-item {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }

    .list-item-header {
        flex-direction: column;
        align-items: stretch;
    }

    .list-item-meta {
        justify-content: center;
    }

    .products-header {
        flex-direction: column;
        align-items: stretch;
    }
}

@media (max-width: 480px) {
    .page-title {
        font-size: 1.5rem;
    }

    .stat-card {
        padding: 16px;
    }

    .filters-section {
        padding: 16px;
    }

    .products-section {
        padding: 16px;
    }
}
</style>
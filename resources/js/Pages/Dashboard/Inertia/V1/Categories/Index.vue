<template>
    <AuthenticatedLayout title="Categories">
        <div class="categories-page">
            <!-- Modern Header -->
            <div class="page-header">
                <div class="header-content">
                    <div class="header-info">
                        <div class="header-icon">
                            <v-icon icon="mdi-tag-multiple" size="40" color="primary"></v-icon>
                        </div>
                        <div>
                            <h1 class="page-title">Categories</h1>
                            <p class="page-subtitle">Organize your products with categories</p>
                        </div>
                    </div>
                    <v-btn color="primary" size="large" variant="elevated" class="create-btn" @click="createCategory">
                        <v-icon start>mdi-plus</v-icon>
                        Add Category
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
                                <v-icon icon="mdi-tag-multiple" size="32" color="white"></v-icon>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ categories.data?.length || 0 }}</div>
                                <div class="stat-label">Total Categories</div>
                            </div>
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6" md="3">
                        <div class="stat-card">
                            <div class="stat-icon success">
                                <v-icon icon="mdi-check-circle" size="32" color="white"></v-icon>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ activeCategories }}</div>
                                <div class="stat-label">Active Categories</div>
                            </div>
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6" md="3">
                        <div class="stat-card">
                            <div class="stat-icon warning">
                                <v-icon icon="mdi-file-document-edit" size="32" color="white"></v-icon>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ draftCategories }}</div>
                                <div class="stat-label">Draft Categories</div>
                            </div>
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6" md="3">
                        <div class="stat-card">
                            <div class="stat-icon info">
                                <v-icon icon="mdi-package-variant-closed" size="32" color="white"></v-icon>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ totalProducts }}</div>
                                <div class="stat-label">Total Products</div>
                            </div>
                        </div>
                    </v-col>
                </v-row>
            </div>

            <!-- Filters & Search -->
            <div class="filters-section">
                <v-card class="filter-card" variant="flat">
                    <v-card-text class="pa-6">
                        <v-row align="center">
                            <v-col cols="12" md="4">
                                <v-text-field
                                    v-model="search"
                                    label="Search categories..."
                                    prepend-inner-icon="mdi-magnify"
                                    variant="outlined"
                                    hide-details
                                    clearable
                                    @keyup.enter="filterCategories"
                                    @click:clear="clearSearch"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-select
                                    v-model="selectedStatus"
                                    :items="statusOptions"
                                    label="Filter by Status"
                                    variant="outlined"
                                    hide-details
                                    clearable
                                    @update:model-value="filterCategories"
                                ></v-select>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-select
                                    v-model="selectedSort"
                                    :items="sortOptions"
                                    label="Sort by"
                                    variant="outlined"
                                    hide-details
                                    @update:model-value="filterCategories"
                                ></v-select>
                            </v-col>
                            <v-col cols="12" md="2">
                                <v-btn
                                    color="primary"
                                    variant="flat"
                                    size="large"
                                    block
                                    @click="filterCategories"
                                >
                                    <v-icon start>mdi-filter</v-icon>
                                    Filter
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </div>

            <!-- Categories Data Table -->
            <div class="table-section">
                <v-card class="data-table-card" variant="flat">
                    <v-card-title class="table-header">
                        <div class="table-title">
                            <v-icon icon="mdi-view-list" size="24" class="mr-2"></v-icon>
                            Categories List
                        </div>
                        <v-spacer></v-spacer>
                        <div class="table-actions">
                            <v-btn
                                color="success"
                                variant="tonal"
                                size="small"
                                @click="refreshCategories"
                            >
                                <v-icon start>mdi-refresh</v-icon>
                                Refresh
                            </v-btn>
                        </div>
                    </v-card-title>

                    <v-data-table
                        :headers="headers"
                        :items="categories.data"
                        :loading="loading"
                        class="elevation-0"
                        item-value="id"
                    >
                        <!-- Name Column -->
                        <template v-slot:item.name="{ item }">
                            <div class="category-name-cell">
                                <div class="category-avatar">
                                    <v-avatar size="40" color="primary">
                                        <v-icon icon="mdi-tag" color="white"></v-icon>
                                    </v-avatar>
                                </div>
                                <div class="category-info">
                                    <div class="category-name">{{ item.name }}</div>
                                    <div class="category-type" v-if="item.type">{{ item.type }}</div>
                                </div>
                            </div>
                        </template>

                        <!-- Description Column -->
                        <template v-slot:item.description="{ item }">
                            <div class="description-cell">
                                {{ item.shortDescription }}
                            </div>
                        </template>

                        <!-- Menu Column -->
                        <template v-slot:item.menuName="{ item }">
                            <v-chip
                                :color="item.menu ? 'primary' : 'grey'"
                                variant="tonal"
                                size="small"
                            >
                                <v-icon start :icon="item.menu ? 'mdi-menu' : 'mdi-menu-off'"></v-icon>
                                {{ item.menuName }}
                            </v-chip>
                        </template>

                        <!-- Products Count Column -->
                        <template v-slot:item.products_count="{ item }">
                            <v-chip
                                :color="item.products_count > 0 ? 'success' : 'grey'"
                                variant="tonal"
                                size="small"
                            >
                                <v-icon start icon="mdi-package-variant-closed"></v-icon>
                                {{ item.products_count }}
                            </v-chip>
                        </template>

                        <!-- Status Column -->
                        <template v-slot:item.status="{ item }">
                            <v-chip
                                :color="item.statusColor"
                                variant="tonal"
                                size="small"
                            >
                                <v-icon start :icon="getStatusIcon(item.status)"></v-icon>
                                {{ item.status }}
                            </v-chip>
                        </template>

                        <!-- Price Sale Column -->
                        <template v-slot:item.formattedPriceSale="{ item }">
                            <div class="price-cell" v-if="item.price_sale && item.price_sale !== '0.00'">
                                <span class="price-amount">{{ item.formattedPriceSale }}</span>
                            </div>
                            <span v-else class="text-grey">—</span>
                        </template>

                        <!-- Created Date Column -->
                        <template v-slot:item.created_at_formatted="{ item }">
                            <div class="date-cell">
                                {{ item.created_at_formatted }}
                            </div>
                        </template>

                        <!-- Actions Column -->
                        <template v-slot:item.actions="{ item }">
                            <div class="actions-cell">
                                <v-btn
                                    icon
                                    size="small"
                                    color="primary"
                                    variant="text"
                                    @click="viewCategory(item)"
                                    class="action-btn"
                                >
                                    <v-icon icon="mdi-eye"></v-icon>
                                    <v-tooltip activator="parent">View</v-tooltip>
                                </v-btn>
                                <v-btn
                                    icon
                                    size="small"
                                    color="warning"
                                    variant="text"
                                    @click="editCategory(item)"
                                    class="action-btn"
                                >
                                    <v-icon icon="mdi-pencil"></v-icon>
                                    <v-tooltip activator="parent">Edit</v-tooltip>
                                </v-btn>
                                <v-btn
                                    icon
                                    size="small"
                                    color="error"
                                    variant="text"
                                    @click="deleteCategory(item)"
                                    class="action-btn"
                                >
                                    <v-icon icon="mdi-delete"></v-icon>
                                    <v-tooltip activator="parent">Delete</v-tooltip>
                                </v-btn>
                            </div>
                        </template>

                        <!-- Loading -->
                        <template v-slot:loading>
                            <v-skeleton-loader type="table-row@10"></v-skeleton-loader>
                        </template>

                        <!-- No data -->
                        <template v-slot:no-data>
                            <div class="no-data-container">
                                <v-icon icon="mdi-tag-off" size="64" color="grey"></v-icon>
                                <h3>No categories found</h3>
                                <p>Start by creating your first category</p>
                                <v-btn color="primary" @click="createCategory">
                                    <v-icon start>mdi-plus</v-icon>
                                    Create Category
                                </v-btn>
                            </div>
                        </template>
                    </v-data-table>

                    <!-- Pagination -->
                    <div class="pagination-section" v-if="categories.data && categories.data.length > 0">
                        <v-divider></v-divider>
                        <div class="pagination-controls">
                            <div class="pagination-info">
                                Showing {{ categories.from || 0 }} to {{ categories.to || 0 }} 
                                of {{ categories.total || 0 }} entries
                            </div>
                            <v-pagination
                                v-if="categories.last_page > 1"
                                v-model="currentPage"
                                :length="categories.last_page"
                                :total-visible="7"
                                @update:model-value="changePage"
                            ></v-pagination>
                        </div>
                    </div>
                </v-card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Props
const props = defineProps({
    categories: Object,
    filters: Object
})

// Reactive data
const loading = ref(false)
const search = ref(props.filters?.search || '')
const selectedStatus = ref(props.filters?.status || '')
const selectedSort = ref(props.filters?.sort || 'created_at')
const currentPage = ref(1)

// Data table headers
const headers = [
    { title: 'Category', value: 'name', sortable: true },
    { title: 'Description', value: 'description', sortable: false },
    { title: 'Menu', value: 'menuName', sortable: false },
    { title: 'Products', value: 'products_count', sortable: true },
    { title: 'Status', value: 'status', sortable: true },
    { title: 'Sale Price', value: 'formattedPriceSale', sortable: true },
    { title: 'Created', value: 'created_at_formatted', sortable: true },
    { title: 'Actions', value: 'actions', sortable: false, width: '120px' }
]

// Filter options
const statusOptions = [
    { title: 'Active', value: 'active' },
    { title: 'Inactive', value: 'inactive' },
    { title: 'Draft', value: 'draft' }
]

const sortOptions = [
    { title: 'Name (A-Z)', value: 'name' },
    { title: 'Name (Z-A)', value: 'name_desc' },
    { title: 'Newest First', value: 'created_at' },
    { title: 'Oldest First', value: 'created_at_asc' },
    { title: 'Most Products', value: 'products_count' }
]

// Computed properties
const activeCategories = computed(() => {
    return props.categories?.data?.filter(category => category.status === 'active').length || 0
})

const draftCategories = computed(() => {
    return props.categories?.data?.filter(category => category.status === 'draft').length || 0
})

const totalProducts = computed(() => {
    return props.categories?.data?.reduce((total, category) => total + (category.products_count || 0), 0) || 0
})

// Methods
const createCategory = () => {
    router.get(route('dashboard.categories.create'))
}

const viewCategory = (category) => {
    router.get(route('dashboard.categories.show', category.id))
}

const editCategory = (category) => {
    router.get(route('dashboard.categories.edit', category.id))
}

const deleteCategory = (category) => {
    router.get(route('dashboard.categories.delete', category.id))
}

const filterCategories = () => {
    loading.value = true
    
    const params = {
        search: search.value || undefined,
        status: selectedStatus.value || undefined,
        sort: selectedSort.value || undefined,
        page: 1
    }
    
    // Remove undefined values
    Object.keys(params).forEach(key => {
        if (params[key] === undefined) {
            delete params[key]
        }
    })
    
    router.get(route('dashboard.categories.index'), params, {
        preserveState: true,
        onFinish: () => {
            loading.value = false
        }
    })
}

const clearSearch = () => {
    search.value = ''
    filterCategories()
}

const refreshCategories = () => {
    router.reload({ only: ['categories'] })
}

const changePage = (page) => {
    loading.value = true
    
    const params = {
        search: search.value || undefined,
        status: selectedStatus.value || undefined,
        sort: selectedSort.value || undefined,
        page: page
    }
    
    // Remove undefined values
    Object.keys(params).forEach(key => {
        if (params[key] === undefined) {
            delete params[key]
        }
    })
    
    router.get(route('dashboard.categories.index'), params, {
        preserveState: true,
        onFinish: () => {
            loading.value = false
        }
    })
}

const getStatusIcon = (status) => {
    const icons = {
        active: 'mdi-check-circle',
        inactive: 'mdi-close-circle',
        draft: 'mdi-file-document-edit'
    }
    return icons[status] || 'mdi-help-circle'
}

onMounted(() => {
    currentPage.value = props.categories?.current_page || 1
})
</script>

<style scoped>
.categories-page {
    padding: 24px;
    background: #f5f5f5;
    min-height: 100vh;
}

.page-header {
    margin-bottom: 32px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    padding: 32px;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.header-info {
    display: flex;
    align-items: center;
    gap: 20px;
}

.header-icon {
    background: rgba(var(--v-theme-primary), 0.1);
    padding: 12px;
    border-radius: 12px;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: #1a1a1a;
}

.page-subtitle {
    font-size: 1rem;
    color: #666;
    margin: 4px 0 0 0;
}

.create-btn {
    min-width: 160px;
    height: 48px;
    font-weight: 600;
}

.alerts-container {
    margin-bottom: 24px;
}

.stats-section {
    margin-bottom: 32px;
}

.stat-card {
    background: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 16px;
    height: 100%;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-icon.primary { background: rgb(var(--v-theme-primary)); }
.stat-icon.success { background: rgb(var(--v-theme-success)); }
.stat-icon.warning { background: rgb(var(--v-theme-warning)); }
.stat-icon.info { background: rgb(var(--v-theme-info)); }

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: #666;
    margin-top: 4px;
}

.filters-section {
    margin-bottom: 24px;
}

.filter-card {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 16px;
}

.table-section {
    margin-bottom: 32px;
}

.data-table-card {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 16px;
    overflow: hidden;
}

.table-header {
    padding: 24px 24px 16px 24px;
    border-bottom: 1px solid #e0e0e0;
}

.table-title {
    display: flex;
    align-items: center;
    font-size: 1.25rem;
    font-weight: 600;
    color: #1a1a1a;
}

.table-actions {
    display: flex;
    gap: 12px;
}

.category-name-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.category-info {
    display: flex;
    flex-direction: column;
}

.category-name {
    font-weight: 600;
    color: #1a1a1a;
    font-size: 0.875rem;
}

.category-type {
    font-size: 0.75rem;
    color: #666;
    margin-top: 2px;
}

.description-cell {
    max-width: 200px;
    font-size: 0.875rem;
    color: #666;
}

.price-cell {
    font-weight: 600;
    color: rgb(var(--v-theme-success));
}

.date-cell {
    font-size: 0.875rem;
    color: #666;
}

.actions-cell {
    display: flex;
    gap: 4px;
}

.action-btn {
    width: 32px;
    height: 32px;
}

.no-data-container {
    text-align: center;
    padding: 64px 32px;
    color: #666;
}

.no-data-container h3 {
    margin: 16px 0 8px 0;
    color: #1a1a1a;
}

.no-data-container p {
    margin-bottom: 24px;
}

.pagination-section {
    background: #f8f9fa;
}

.pagination-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 24px;
}

.pagination-info {
    font-size: 0.875rem;
    color: #666;
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }
    
    .pagination-controls {
        flex-direction: column;
        gap: 16px;
    }
}
</style>
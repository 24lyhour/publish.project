<template>
    <AuthenticatedLayout :title="`Category: ${category.displayName}`">
        <div class="category-show-page">
            <!-- Header -->
            <div class="page-header">
                <div class="header-content">
                    <div class="header-info">
                        <div class="header-icon">
                            <v-icon icon="mdi-tag" size="40" color="primary"></v-icon>
                        </div>
                        <div>
                            <h1 class="page-title">{{ category.displayName }}</h1>
                            <p class="page-subtitle">Category Details</p>
                        </div>
                    </div>
                    <div class="header-actions">
                        <v-btn
                            color="warning"
                            variant="tonal"
                            size="large"
                            @click="editCategory"
                        >
                            <v-icon start>mdi-pencil</v-icon>
                            Edit
                        </v-btn>
                        <v-btn
                            color="primary"
                            variant="outlined"
                            size="large"
                            @click="$inertia.get(route('dashboard.categories.index'))"
                        >
                            <v-icon start>mdi-arrow-left</v-icon>
                            Back to List
                        </v-btn>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <v-row>
                <!-- Main Info -->
                <v-col cols="12" md="8">
                    <v-card class="info-card">
                        <v-card-title class="card-title">
                            <v-icon icon="mdi-information" class="mr-2"></v-icon>
                            Basic Information
                        </v-card-title>
                        <v-card-text>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label class="info-label">Name</label>
                                    <div class="info-value">{{ category.name }}</div>
                                </div>

                                <div class="info-item" v-if="category.type">
                                    <label class="info-label">Type</label>
                                    <div class="info-value">{{ category.type }}</div>
                                </div>

                                <div class="info-item">
                                    <label class="info-label">Status</label>
                                    <div class="info-value">
                                        <v-chip
                                            :color="category.statusColor"
                                            variant="tonal"
                                            size="small"
                                        >
                                            <v-icon start :icon="getStatusIcon(category.status)"></v-icon>
                                            {{ category.status }}
                                        </v-chip>
                                    </div>
                                </div>

                                <div class="info-item" v-if="category.menuName">
                                    <label class="info-label">Menu</label>
                                    <div class="info-value">
                                        <v-chip color="primary" variant="tonal" size="small">
                                            <v-icon start icon="mdi-menu"></v-icon>
                                            {{ category.menuName }}
                                        </v-chip>
                                    </div>
                                </div>

                                <div class="info-item" v-if="category.price_sale && category.price_sale !== '0.00'">
                                    <label class="info-label">Sale Price</label>
                                    <div class="info-value price-value">{{ category.formattedPriceSale }}</div>
                                </div>

                                <div class="info-item">
                                    <label class="info-label">Products Count</label>
                                    <div class="info-value">
                                        <v-chip
                                            :color="category.products_count > 0 ? 'success' : 'grey'"
                                            variant="tonal"
                                            size="small"
                                        >
                                            <v-icon start icon="mdi-package-variant-closed"></v-icon>
                                            {{ category.products_count }} Products
                                        </v-chip>
                                    </div>
                                </div>

                                <div class="info-item full-width" v-if="category.description">
                                    <label class="info-label">Description</label>
                                    <div class="info-value description">{{ category.description }}</div>
                                </div>
                            </div>
                        </v-card-text>
                    </v-card>

                    <!-- Badges -->
                    <v-card class="badges-card" v-if="category.badges && category.badges.length > 0">
                        <v-card-title class="card-title">
                            <v-icon icon="mdi-label-multiple" class="mr-2"></v-icon>
                            Badges
                        </v-card-title>
                        <v-card-text>
                            <div class="badges-container">
                                <v-chip
                                    v-for="badge in category.badges"
                                    :key="badge.text"
                                    :color="badge.color"
                                    variant="tonal"
                                    size="default"
                                    class="badge-chip"
                                >
                                    <v-icon start :icon="badge.icon"></v-icon>
                                    {{ badge.text }}
                                </v-chip>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- Sidebar -->
                <v-col cols="12" md="4">
                    <!-- Image -->
                    <v-card class="image-card" v-if="category.imageUrl">
                        <v-card-title class="card-title">
                            <v-icon icon="mdi-image" class="mr-2"></v-icon>
                            Category Image
                        </v-card-title>
                        <v-card-text class="pa-0">
                            <v-img
                                :src="category.imageUrl"
                                :alt="category.name"
                                height="200"
                                cover
                                class="category-image"
                            >
                                <template v-slot:error>
                                    <div class="image-error">
                                        <v-icon icon="mdi-image-broken" size="48" color="grey"></v-icon>
                                        <p>Image not available</p>
                                    </div>
                                </template>
                            </v-img>
                        </v-card-text>
                    </v-card>

                    <!-- Metadata -->
                    <v-card class="metadata-card">
                        <v-card-title class="card-title">
                            <v-icon icon="mdi-clock" class="mr-2"></v-icon>
                            Metadata
                        </v-card-title>
                        <v-card-text>
                            <div class="metadata-grid">
                                <div class="metadata-item">
                                    <label class="metadata-label">Created</label>
                                    <div class="metadata-value">{{ category.created_at_formatted }}</div>
                                </div>
                                <div class="metadata-item">
                                    <label class="metadata-label">Updated</label>
                                    <div class="metadata-value">{{ category.updated_at_formatted }}</div>
                                </div>
                                <div class="metadata-item">
                                    <label class="metadata-label">ID</label>
                                    <div class="metadata-value">#{{ category.id }}</div>
                                </div>
                            </div>
                        </v-card-text>
                    </v-card>

                    <!-- Actions -->
                    <v-card class="actions-card">
                        <v-card-title class="card-title">
                            <v-icon icon="mdi-cog" class="mr-2"></v-icon>
                            Actions
                        </v-card-title>
                        <v-card-text>
                            <div class="actions-list">
                                <v-btn
                                    color="warning"
                                    variant="tonal"
                                    block
                                    size="large"
                                    @click="editCategory"
                                >
                                    <v-icon start>mdi-pencil</v-icon>
                                    Edit Category
                                </v-btn>
                                <v-btn
                                    color="error"
                                    variant="tonal"
                                    block
                                    size="large"
                                    @click="deleteCategory"
                                >
                                    <v-icon start>mdi-delete</v-icon>
                                    Delete Category
                                </v-btn>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { router } from '@inertiajs/vue3'

// Props
const props = defineProps({
    category: Object
})

// Methods
const editCategory = () => {
    router.get(route('dashboard.categories.edit', props.category.id))
}

const deleteCategory = () => {
    router.get(route('dashboard.categories.delete', props.category.id))
}

const getStatusIcon = (status) => {
    const icons = {
        active: 'mdi-check-circle',
        inactive: 'mdi-close-circle',
        draft: 'mdi-file-document-edit'
    }
    return icons[status] || 'mdi-help-circle'
}
</script>

<style scoped>
.category-show-page {
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

.header-actions {
    display: flex;
    gap: 12px;
}

.info-card,
.badges-card,
.image-card,
.metadata-card,
.actions-card {
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 16px;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1a1a1a;
    display: flex;
    align-items: center;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 1rem;
    color: #1a1a1a;
    font-weight: 500;
}

.info-value.description {
    line-height: 1.6;
    color: #666;
}

.price-value {
    color: rgb(var(--v-theme-success));
    font-weight: 600;
    font-size: 1.25rem;
}

.badges-container {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.badge-chip {
    margin: 0;
}

.category-image {
    border-radius: 0 0 16px 16px;
}

.image-error {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #666;
    text-align: center;
}

.metadata-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.metadata-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.metadata-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.metadata-value {
    font-size: 0.875rem;
    color: #1a1a1a;
    font-weight: 500;
}

.actions-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }
    
    .header-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
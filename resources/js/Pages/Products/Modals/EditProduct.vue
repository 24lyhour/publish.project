<template>
    <SakalFormModal
        :show="true"
        title="Edit Product"
        :subtitle="`Update ${product.name}`"
        icon="mdi-pencil-box"
        icon-color="primary"
        :max-width="700"
        method="put"
        :action="route('products.update', product.id)"
        :form-data="formData"
        submit-text="Update Product"
        submit-icon="mdi-content-save"
        submit-color="primary"
        @close="handleClose"
        @cancel="handleClose"
        @success="handleSuccess"
        @error="handleError">
        
        <template #default="{ form, errors, processing }">
            <v-row>
                <!-- Left Column - Form Fields -->
                <v-col cols="12" lg="8">
                    <div class="form-section">
                        <h4 class="section-title">
                            <v-icon icon="mdi-information" class="me-2" color="primary"></v-icon>
                            Basic Information
                        </h4>
                        
                        <v-text-field
                            v-model="form.name"
                            label="Product Name"
                            placeholder="Enter product name"
                            variant="outlined"
                            density="comfortable"
                            :error-messages="errors.name"
                            counter="255"
                            required
                            class="mb-4">
                            <template #prepend-inner>
                                <v-icon icon="mdi-package" color="grey-darken-1"></v-icon>
                            </template>
                        </v-text-field>

                        <v-textarea
                            v-model="form.description"
                            label="Product Description"
                            placeholder="Describe your product..."
                            variant="outlined"
                            density="comfortable"
                            rows="4"
                            :error-messages="errors.description"
                            counter="1000"
                            required
                            class="mb-6">
                            <template #prepend-inner>
                                <v-icon icon="mdi-text" color="grey-darken-1"></v-icon>
                            </template>
                        </v-textarea>
                    </div>

                    <v-divider class="my-6"></v-divider>

                    <div class="form-section">
                        <h4 class="section-title">
                            <v-icon icon="mdi-currency-usd" class="me-2" color="success"></v-icon>
                            Pricing & Inventory
                        </h4>
                        
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="form.price"
                                    label="Price (USD)"
                                    placeholder="0.00"
                                    type="number"
                                    step="0.01"
                                    variant="outlined"
                                    density="comfortable"
                                    :error-messages="errors.price"
                                    required>
                                    <template #prepend-inner>
                                        <v-icon icon="mdi-currency-usd" color="grey-darken-1"></v-icon>
                                    </template>
                                </v-text-field>
                            </v-col>
                            
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="form.quantity"
                                    label="Stock Quantity"
                                    placeholder="0"
                                    type="number"
                                    variant="outlined"
                                    density="comfortable"
                                    :error-messages="errors.quantity">
                                    <template #prepend-inner>
                                        <v-icon icon="mdi-package-variant" color="grey-darken-1"></v-icon>
                                    </template>
                                </v-text-field>
                            </v-col>
                            
                            <v-col cols="12" md="6">
                                <v-select
                                    v-model="form.category_id"
                                    :items="categories"
                                    item-title="name"
                                    item-value="id"
                                    label="Category"
                                    variant="outlined"
                                    density="comfortable"
                                    :error-messages="errors.category_id">
                                    <template #prepend-inner>
                                        <v-icon icon="mdi-tag" color="grey-darken-1"></v-icon>
                                    </template>
                                </v-select>
                            </v-col>
                            
                            <v-col cols="12" md="6">
                                <v-select
                                    v-model="form.status"
                                    :items="statusOptions"
                                    label="Status"
                                    variant="outlined"
                                    density="comfortable"
                                    :error-messages="errors.status">
                                    <template #prepend-inner>
                                        <v-icon icon="mdi-check-circle" color="grey-darken-1"></v-icon>
                                    </template>
                                </v-select>
                            </v-col>
                        </v-row>
                    </div>

                    <v-divider class="my-6"></v-divider>

                    <div class="form-section">
                        <h4 class="section-title">
                            <v-icon icon="mdi-cog" class="me-2" color="warning"></v-icon>
                            Additional Details
                        </h4>
                        
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="form.size"
                                    label="Size"
                                    placeholder="e.g., Large, 500ml, XL"
                                    variant="outlined"
                                    density="comfortable"
                                    :error-messages="errors.size">
                                    <template #prepend-inner>
                                        <v-icon icon="mdi-resize" color="grey-darken-1"></v-icon>
                                    </template>
                                </v-text-field>
                            </v-col>
                            
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="form.type"
                                    label="Type"
                                    placeholder="e.g., Premium, Standard"
                                    variant="outlined"
                                    density="comfortable"
                                    :error-messages="errors.type">
                                    <template #prepend-inner>
                                        <v-icon icon="mdi-shape" color="grey-darken-1"></v-icon>
                                    </template>
                                </v-text-field>
                            </v-col>
                            
                            <v-col cols="12">
                                <v-textarea
                                    v-model="form.ingredients"
                                    label="Ingredients"
                                    placeholder="List ingredients (optional)"
                                    variant="outlined"
                                    density="comfortable"
                                    rows="2"
                                    :error-messages="errors.ingredients">
                                    <template #prepend-inner>
                                        <v-icon icon="mdi-food" color="grey-darken-1"></v-icon>
                                    </template>
                                </v-textarea>
                            </v-col>
                        </v-row>
                    </div>
                </v-col>

                <!-- Right Column - Preview -->
                <v-col cols="12" lg="4">
                    <div class="preview-section">
                        <h4 class="section-title">
                            <v-icon icon="mdi-eye" class="me-2" color="info"></v-icon>
                            Live Preview
                        </h4>
                        
                        <div class="preview-card">
                            <div class="preview-image">
                                <v-img 
                                    v-if="product.imageUrl" 
                                    :src="product.imageUrl" 
                                    aspect-ratio="1" 
                                    cover>
                                </v-img>
                                <div v-else class="image-placeholder">
                                    <v-icon icon="mdi-image-plus" size="40" color="grey-lighten-2"></v-icon>
                                    <p class="placeholder-text">No image</p>
                                </div>
                            </div>
                            
                            <div class="preview-content">
                                <div class="preview-badges">
                                    <v-chip 
                                        :color="getStatusColor(form.status || product.status)" 
                                        size="small" 
                                        variant="flat"
                                        class="status-chip">
                                        {{ form.status || product.status || 'Active' }}
                                    </v-chip>
                                    <v-chip 
                                        color="primary" 
                                        size="small" 
                                        variant="flat"
                                        class="price-chip">
                                        ${{ form.price || product.price || '0.00' }}
                                    </v-chip>
                                </div>
                                
                                <h5 class="preview-name">{{ form.name || product.name }}</h5>
                                <p class="preview-description">
                                    {{ truncateText(form.description || product.description, 80) }}
                                </p>
                                
                                <div class="preview-meta">
                                    <div class="meta-item">
                                        <span class="meta-label">Category:</span>
                                        <span class="meta-value">{{ getCategoryName(form.category_id || product.category_id) }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Stock:</span>
                                        <span class="meta-value">{{ form.quantity || product.quantity || '0' }} units</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Size:</span>
                                        <span class="meta-value">{{ form.size || product.size || 'Not set' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="stats-section">
                            <h5 class="stats-title">Quick Info</h5>
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <v-icon icon="mdi-calendar" size="16" color="primary"></v-icon>
                                    <span class="stat-label">Created:</span>
                                    <span class="stat-value">{{ formatDate(product.created_at) }}</span>
                                </div>
                                <div class="stat-item">
                                    <v-icon icon="mdi-update" size="16" color="warning"></v-icon>
                                    <span class="stat-label">Updated:</span>
                                    <span class="stat-value">{{ formatDate(product.updated_at) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </template>
    </SakalFormModal>
</template>

<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import SakalFormModal from '@/Components/SakalFormModal.vue'

const props = defineProps({
    product: {
        type: Object,
        required: true
    },
    categories: {
        type: Array,
        default: () => []
    }
})

// Form data
const formData = {
    name: props.product.name || '',
    description: props.product.description || '',
    price: props.product.price || '',
    category_id: props.product.category_id || null,
    quantity: props.product.quantity || '',
    status: props.product.status || 'active',
    size: props.product.size || '',
    type: props.product.type || '',
    ingredients: props.product.ingredients || ''
}

// Options
const statusOptions = [
    { title: 'Active', value: 'active' },
    { title: 'Inactive', value: 'inactive' }
]

// Helper methods
const getStatusColor = (status) => {
    return status === 'active' ? 'success' : 'error'
}

const getCategoryName = (categoryId) => {
    if (!categoryId) return 'Uncategorized'
    const category = props.categories.find(cat => cat.id === categoryId)
    return category ? category.name : 'Uncategorized'
}

const truncateText = (text, length) => {
    if (!text) return 'No description available...'
    return text.length > length ? text.substring(0, length) + '...' : text
}

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString()
}

// Event handlers
const handleClose = () => {
    router.visit(route('products.index'))
}

const handleSuccess = () => {
    // Modal will auto-close due to closeOnSuccess: true
}

const handleError = (errors) => {
    console.error('Form submission errors:', errors)
}
</script>

<style scoped>
.form-section {
    margin-bottom: 24px;
}

.section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
}

.preview-section {
    background: #f7fafc;
    border-radius: 12px;
    padding: 20px;
    height: fit-content;
    position: sticky;
    top: 0;
}

.preview-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 20px;
    border: 1px solid #e2e8f0;
}

.preview-image {
    height: 150px;
    position: relative;
}

.image-placeholder {
    height: 100%;
    background: #f7fafc;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.placeholder-text {
    color: #a0aec0;
    margin: 0;
    font-size: 0.75rem;
}

.preview-content {
    padding: 16px;
}

.preview-badges {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
}

.status-chip,
.price-chip {
    color: white !important;
    font-weight: 600;
}

.preview-name {
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 8px 0;
    line-height: 1.4;
}

.preview-description {
    color: #718096;
    margin: 0 0 12px 0;
    line-height: 1.4;
    font-size: 0.875rem;
}

.preview-meta {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.meta-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.75rem;
}

.meta-label {
    color: #a0aec0;
    font-weight: 500;
}

.meta-value {
    color: #4a5568;
    font-weight: 600;
    text-transform: capitalize;
}

.stats-section {
    background: white;
    border-radius: 12px;
    padding: 16px;
    border: 1px solid #e2e8f0;
}

.stats-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 12px 0;
}

.stats-grid {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.75rem;
}

.stat-label {
    color: #a0aec0;
    font-weight: 500;
}

.stat-value {
    color: #4a5568;
    font-weight: 600;
    margin-left: auto;
}

/* Responsive */
@media (max-width: 1024px) {
    .preview-section {
        position: static;
        margin-top: 24px;
    }
}
</style>
<template>
    <div class="product-preview">
        <div class="preview-section">
            <h4 class="section-title">
                <v-icon icon="mdi-eye" class="me-2" color="info"></v-icon>
                Live Preview
            </h4>
            
            <div class="preview-card">
                <div class="preview-image">
                    <v-img 
                        v-if="previewUrl || (product && product.imageUrl)" 
                        :src="previewUrl || product.imageUrl" 
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
                            :color="getStatusColor(form.status || (product && product.status))" 
                            size="small" 
                            variant="flat"
                            class="status-chip">
                            {{ form.status || (product && product.status) || 'Active' }}
                        </v-chip>
                        <v-chip 
                            color="primary" 
                            size="small" 
                            variant="flat"
                            class="price-chip">
                            ${{ form.price || (product && product.price) || '0.00' }}
                        </v-chip>
                    </div>
                    
                    <h5 class="preview-name">{{ form.name || (product && product.name) || 'Product Name' }}</h5>
                    <p class="preview-description">
                        {{ truncateText(form.description || (product && product.description), 80) }}
                    </p>
                    
                    <div class="preview-meta">
                        <div class="meta-item">
                            <span class="meta-label">Category:</span>
                            <span class="meta-value">{{ getCategoryName(form.category_id || (product && product.category_id)) }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Stock:</span>
                            <span class="meta-value">{{ form.quantity || (product && product.quantity) || '0' }} units</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Size:</span>
                            <span class="meta-value">{{ form.size || (product && product.size) || 'Not set' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats (Edit Mode) -->
            <div v-if="product" class="stats-section">
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
    </div>
</template>

<script setup>
const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    product: {
        type: Object,
        default: null
    },
    categories: {
        type: Array,
        default: () => []
    },
    previewUrl: {
        type: String,
        default: null
    }
})

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
</script>

<style scoped>
.product-preview {
    height: fit-content;
    position: sticky;
    top: 0;
}

.preview-section {
    background: #f7fafc;
    border-radius: 12px;
    padding: 20px;
}

.section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
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
</style>
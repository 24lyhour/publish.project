<template>
    <SakalModal
        :show="true"
        title="Confirm Deletion"
        subtitle="This action cannot be undone"
        icon="mdi-alert-circle"
        icon-color="error"
        :max-width="500"
        type="error"
        :persistent="deleting"
        :closable="!deleting"
        :loading="deleting"
        show-default-actions
        :show-cancel="true"
        :show-confirm="true"
        cancel-text="Cancel"
        confirm-text="Delete Product"
        confirm-color="error"
        @close="handleClose"
        @cancel="handleCancel"
        @confirm="handleConfirm">
        
        <div class="delete-content">
            <!-- Warning Icon -->
            <div class="warning-section">
                <div class="warning-icon">
                    <v-icon icon="mdi-delete-alert" size="80" color="error"></v-icon>
                </div>
                <h3 class="warning-title">Are you absolutely sure?</h3>
                <p class="warning-description">
                    You are about to permanently delete the product 
                    <strong class="product-highlight">"{{ product.name }}"</strong>.
                    This action cannot be undone and will remove all associated data.
                </p>
            </div>

            <!-- Product Summary -->
            <div class="product-summary">
                <div class="summary-header">
                    <h4 class="summary-title">Product Information</h4>
                </div>
                
                <div class="summary-content">
                    <div class="product-preview">
                        <div class="product-image">
                            <v-img 
                                v-if="product.imageUrl" 
                                :src="product.imageUrl" 
                                :alt="product.name"
                                aspect-ratio="1" 
                                cover
                                class="preview-img">
                            </v-img>
                            <div v-else class="image-placeholder">
                                <v-icon icon="mdi-package-variant" size="32" color="grey-lighten-2"></v-icon>
                            </div>
                        </div>
                        
                        <div class="product-details">
                            <h5 class="product-name">{{ product.name }}</h5>
                            <p class="product-description">{{ truncateText(product.description, 60) }}</p>
                            
                            <div class="product-meta">
                                <div class="meta-row">
                                    <span class="meta-label">Price:</span>
                                    <span class="meta-value">${{ product.price }}</span>
                                </div>
                                <div class="meta-row">
                                    <span class="meta-label">Category:</span>
                                    <span class="meta-value">{{ product.category || 'Uncategorized' }}</span>
                                </div>
                                <div class="meta-row">
                                    <span class="meta-label">Stock:</span>
                                    <span class="meta-value">{{ product.quantity || 0 }} units</span>
                                </div>
                                <div class="meta-row">
                                    <span class="meta-label">Status:</span>
                                    <v-chip 
                                        :color="getStatusColor(product.status)" 
                                        size="x-small" 
                                        variant="flat"
                                        class="status-chip">
                                        {{ product.status || 'Active' }}
                                    </v-chip>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consequences Warning -->
            <div class="consequences-section">
                <h4 class="consequences-title">
                    <v-icon icon="mdi-information" class="me-2" color="warning"></v-icon>
                    What will be deleted:
                </h4>
                <ul class="consequences-list">
                    <li>
                        <v-icon icon="mdi-check" size="16" color="error" class="me-2"></v-icon>
                        Product information and description
                    </li>
                    <li>
                        <v-icon icon="mdi-check" size="16" color="error" class="me-2"></v-icon>
                        Pricing and inventory data
                    </li>
                    <li>
                        <v-icon icon="mdi-check" size="16" color="error" class="me-2"></v-icon>
                        Associated images and media
                    </li>
                    <li>
                        <v-icon icon="mdi-check" size="16" color="error" class="me-2"></v-icon>
                        Product statistics and history
                    </li>
                </ul>
                <div class="final-warning">
                    <v-icon icon="mdi-alert" class="me-2" color="error"></v-icon>
                    <span>This action is permanent and cannot be reversed!</span>
                </div>
            </div>

            <!-- Confirmation Input -->
            <div class="confirmation-section">
                <p class="confirmation-text">
                    Type <code class="confirmation-code">DELETE</code> to confirm deletion:
                </p>
                <v-text-field
                    v-model="confirmationText"
                    placeholder="Type DELETE to confirm"
                    variant="outlined"
                    density="comfortable"
                    :error="confirmationError"
                    :error-messages="confirmationError ? 'Please type DELETE to confirm' : ''"
                    class="confirmation-input"
                    @keyup.enter="handleConfirm">
                </v-text-field>
            </div>
        </div>
    </SakalModal>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import SakalModal from '@/Components/SakalModal.vue'

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
})

// Component state
const deleting = ref(false)
const confirmationText = ref('')
const confirmationError = ref(false)

// Computed properties
const isConfirmationValid = computed(() => {
    return confirmationText.value.toUpperCase() === 'DELETE'
})

// Helper methods
const getStatusColor = (status) => {
    return status === 'active' ? 'success' : 'error'
}

const truncateText = (text, length) => {
    if (!text) return 'No description available'
    return text.length > length ? text.substring(0, length) + '...' : text
}

// Event handlers
const handleClose = () => {
    if (!deleting.value) {
        router.visit(route('products.index'))
    }
}

const handleCancel = () => {
    if (!deleting.value) {
        router.visit(route('products.index'))
    }
}

const handleConfirm = () => {
    if (deleting.value) return
    
    // Validate confirmation
    if (!isConfirmationValid.value) {
        confirmationError.value = true
        return
    }
    
    confirmationError.value = false
    deleting.value = true
    
    // Perform deletion
    router.delete(route('products.destroy', props.product.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Success handled by redirect
        },
        onError: (errors) => {
            console.error('Delete error:', errors)
            deleting.value = false
        },
        onFinish: () => {
            deleting.value = false
        }
    })
}
</script>

<style scoped>
.delete-content {
    padding: 8px 0;
}

.warning-section {
    text-align: center;
    margin-bottom: 32px;
}

.warning-icon {
    margin-bottom: 16px;
}

.warning-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #e53e3e;
    margin: 0 0 12px 0;
}

.warning-description {
    color: #718096;
    margin: 0;
    line-height: 1.6;
    font-size: 1rem;
}

.product-highlight {
    color: #e53e3e;
    background: rgba(229, 62, 62, 0.1);
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 600;
}

.product-summary {
    background: #f7fafc;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
    border: 1px solid #e2e8f0;
}

.summary-header {
    margin-bottom: 16px;
}

.summary-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

.product-preview {
    display: flex;
    gap: 16px;
    align-items: flex-start;
}

.product-image {
    width: 80px;
    height: 80px;
    flex-shrink: 0;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

.preview-img {
    width: 100%;
    height: 100%;
}

.image-placeholder {
    width: 100%;
    height: 100%;
    background: #f7fafc;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-details {
    flex: 1;
    min-width: 0;
}

.product-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 8px 0;
    line-height: 1.4;
}

.product-description {
    color: #718096;
    margin: 0 0 12px 0;
    line-height: 1.4;
    font-size: 0.875rem;
}

.product-meta {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.875rem;
}

.meta-label {
    color: #a0aec0;
    font-weight: 500;
}

.meta-value {
    color: #4a5568;
    font-weight: 600;
}

.status-chip {
    color: white !important;
    font-weight: 600;
}

.consequences-section {
    background: rgba(229, 62, 62, 0.05);
    border: 1px solid rgba(229, 62, 62, 0.2);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
}

.consequences-title {
    font-size: 1rem;
    font-weight: 600;
    color: #e53e3e;
    margin: 0 0 12px 0;
    display: flex;
    align-items: center;
}

.consequences-list {
    list-style: none;
    padding: 0;
    margin: 0 0 16px 0;
}

.consequences-list li {
    display: flex;
    align-items: center;
    color: #718096;
    margin-bottom: 8px;
    font-size: 0.875rem;
}

.final-warning {
    display: flex;
    align-items: center;
    background: rgba(229, 62, 62, 0.1);
    padding: 12px;
    border-radius: 8px;
    color: #e53e3e;
    font-weight: 600;
    font-size: 0.875rem;
}

.confirmation-section {
    margin-bottom: 16px;
}

.confirmation-text {
    color: #718096;
    margin: 0 0 12px 0;
    font-size: 0.875rem;
    line-height: 1.5;
}

.confirmation-code {
    background: #f1f5f9;
    color: #e53e3e;
    padding: 2px 6px;
    border-radius: 4px;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-weight: 600;
}

.confirmation-input {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
}

.confirmation-input :deep(.v-field__input) {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-weight: 600;
    text-transform: uppercase;
}

/* Responsive */
@media (max-width: 768px) {
    .product-preview {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .product-image {
        width: 100px;
        height: 100px;
    }
    
    .meta-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 2px;
    }
}
</style>
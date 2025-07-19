<template>
    <AuthenticatedLayout title="Delete Product">
        <div>
            <inertia-head :title="__('Delete Product')"></inertia-head>
            <sakal-modal type="error">
                <template #title>
                    {{ __("Delete Product") }}
                </template>

                <div class="delete-content">
                    <div class="product-info">
                        <div class="product-avatar">
                            <v-img 
                                v-if="product.imageUrl" 
                                :src="product.imageUrl" 
                                width="80" 
                                height="80" 
                                cover 
                                class="rounded">
                            </v-img>
                            <v-avatar 
                                v-else 
                                size="80" 
                                color="grey-lighten-2">
                                <v-icon icon="mdi-package" size="40" color="grey-darken-1"></v-icon>
                            </v-avatar>
                        </div>
                        
                        <div class="product-details">
                            <h3 class="product-name">{{ product.name }}</h3>
                            <p class="product-description">{{ truncateText(product.description, 100) }}</p>
                            <div class="product-meta">
                                <v-chip size="small" color="primary" variant="flat" class="me-2">
                                    ${{ product.price }}
                                </v-chip>
                                <v-chip size="small" :color="product.status === 'active' ? 'success' : 'error'" variant="flat">
                                    {{ product.status }}
                                </v-chip>
                            </div>
                        </div>
                    </div>

                    <v-divider class="my-6"></v-divider>

                    <v-alert type="warning" variant="tonal" class="mb-4">
                        <v-alert-title>{{ __("Warning") }}</v-alert-title>
                        {{ __("This action cannot be undone. The product and all its data will be permanently deleted.") }}
                    </v-alert>

                    <div class="confirmation-text">
                        <p class="text-body-1 mb-4">
                            {{ __("Are you sure you want to delete this product?") }}
                        </p>
                        <ul class="text-body-2 text-grey-darken-1 mb-0">
                            <li>{{ __("Product information will be permanently removed") }}</li>
                            <li>{{ __("Associated images will be deleted") }}</li>
                            <li>{{ __("This action cannot be reversed") }}</li>
                        </ul>
                    </div>
                </div>

                <template #footer>
                    <v-btn 
                        variant="outlined" 
                        @click="goBack" 
                        :disabled="form.processing">
                        {{ __("Cancel") }}
                    </v-btn>
                    <v-btn 
                        color="error" 
                        @click="confirmDelete" 
                        prepend-icon="mdi-delete"
                        :loading="form.processing">
                        {{ __("Delete Product") }}
                    </v-btn>
                </template>
            </sakal-modal>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import SakalModal from '@/Shared/Modals/FormSakal/SakalModal.vue'

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
})

// Form for delete operation
const form = useForm({})

// Methods
const confirmDelete = () => {
    form.delete(route('dashboard.products.destroy', props.product.id), {
        onSuccess: () => {
            router.get(route('dashboard.products.index'))
        },
        onError: (errors) => {
            console.error('Error deleting product:', errors)
        }
    })
}

const goBack = () => {
    router.get(route('dashboard.products.index'))
}

const truncateText = (text, length) => {
    if (!text) return 'No description available'
    return text.length > length ? text.substring(0, length) + '...' : text
}

// Translation helper (you can implement this based on your i18n setup)
const __ = (key) => {
    // Simple fallback - replace with your actual translation logic
    const translations = {
        'Delete Product': 'Delete Product',
        'Warning': 'Warning',
        'This action cannot be undone. The product and all its data will be permanently deleted.': 'This action cannot be undone. The product and all its data will be permanently deleted.',
        'Are you sure you want to delete this product?': 'Are you sure you want to delete this product?',
        'Product information will be permanently removed': 'Product information will be permanently removed',
        'Associated images will be deleted': 'Associated images will be deleted',
        'This action cannot be reversed': 'This action cannot be reversed',
        'Cancel': 'Cancel'
    }
    return translations[key] || key
}
</script>

<style scoped>
.delete-content {
    padding: 16px 0;
}

.product-info {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    margin-bottom: 24px;
}

.product-avatar {
    flex-shrink: 0;
}

.product-details {
    flex: 1;
    min-width: 0;
}

.product-name {
    font-size: 1.25rem;
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
    gap: 8px;
    flex-wrap: wrap;
}

.confirmation-text {
    background: #f7fafc;
    border-radius: 8px;
    padding: 16px;
}

.confirmation-text ul {
    padding-left: 20px;
}

.confirmation-text li {
    margin-bottom: 4px;
}
</style>
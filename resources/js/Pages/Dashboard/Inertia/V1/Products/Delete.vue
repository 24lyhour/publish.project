<template>
    <AuthenticatedLayout title="Delete Product">
        <Head :title="__('Delete Product')" />
        
        <sakal-form-modal 
            :show="true" 
            :title="__('Delete Product')"
            subtitle="This action cannot be undone" 
            icon="trash"
            icon-color="red"
            type="error"
            size="lg"
            :submit-text="__('Delete Product')" 
            :cancel-text="__('Cancel')" 
            @cancel="goBack"
            @close="goBack" 
            @submit="confirmDelete">
            
            <div class="space-y-6">
                <!-- Product Info -->
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <img 
                            v-if="product.imageUrl" 
                            :src="product.imageUrl" 
                            :alt="product.name"
                            class="w-20 h-20 rounded-lg object-cover border border-gray-200" />
                        <div 
                            v-else 
                            class="w-20 h-20 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200">
                            <Package class="w-8 h-8 text-gray-400" />
                        </div>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ product.name }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ truncateText(product.description, 100) }}</p>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                ${{ product.price }}
                            </span>
                            <span :class="[
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                product.status === 'active' 
                                    ? 'bg-green-100 text-green-800' 
                                    : 'bg-red-100 text-red-800'
                            ]">
                                {{ product.status }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Warning Alert -->
                <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                    <div class="flex items-start">
                        <AlertTriangle class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" />
                        <div>
                            <h4 class="text-sm font-medium text-yellow-800 mb-1">{{ __("Warning") }}</h4>
                            <p class="text-sm text-yellow-700">
                                {{ __("This action cannot be undone. The product and all its data will be permanently deleted.") }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Details -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-900 mb-3">
                        {{ __("Are you sure you want to delete this product?") }}
                    </p>
                    <ul class="text-xs text-gray-600 space-y-1 ml-4">
                        <li class="flex items-center">
                            <span class="w-1 h-1 bg-gray-400 rounded-full mr-2 flex-shrink-0"></span>
                            {{ __("Product information will be permanently removed") }}
                        </li>
                        <li class="flex items-center">
                            <span class="w-1 h-1 bg-gray-400 rounded-full mr-2 flex-shrink-0"></span>
                            {{ __("Associated images will be deleted") }}
                        </li>
                        <li class="flex items-center">
                            <span class="w-1 h-1 bg-gray-400 rounded-full mr-2 flex-shrink-0"></span>
                            {{ __("This action cannot be reversed") }}
                        </li>
                    </ul>
                </div>
            </div>
        </sakal-form-modal>
    </AuthenticatedLayout>
</template>

<script setup>
import { router, useForm, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import SakalFormModal from '@/Modals/SakalFormModal.vue'
import { Package, AlertTriangle } from 'lucide-vue-next'

const props = defineProps({
    product: {
        type: Object,
        required: true
    },
    isModal: {
        type: Boolean,
        default: false
    },
    baseRoute: {
        type: String,
        default: null
    }
})

// Form for delete operation
const form = useForm({})

// Methods
const confirmDelete = () => {
    form.delete(route('products.destroy', props.product.id), {
        onSuccess: () => {
            if (props.baseRoute) {
                router.get(route(props.baseRoute))
            } else {
                router.get(route('products.index'))
            }
        },
        onError: (errors) => {
            console.error('Error deleting product:', errors)
        }
    })
}

const goBack = () => {
    if (props.baseRoute) {
        router.get(route(props.baseRoute))
    } else {
        router.get(route('products.index'))
    }
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
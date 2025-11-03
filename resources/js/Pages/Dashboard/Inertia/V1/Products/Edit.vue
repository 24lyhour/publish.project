<template>
    <AuthenticatedLayout title="Edit Product">
        <Head :title="__('Edit Product')" />
        
        <sakal-form-modal 
            :show="true" 
            :title="`${__('Edit Product')}: ${product.name}`"
            subtitle="Update the product details below" 
            icon="edit"
            icon-color="blue"
            size="xl"
            :submit-text="__('Update Product')" 
            :cancel-text="__('Cancel')" 
            @cancel="goBack"
            @close="goBack" 
            @submit="handleSubmit">
            
            <ProductForm 
                v-model="form" 
                :categories="props.categories" 
                :product="product"
                :errors="form.errors" 
                :processing="form.processing" />
        </sakal-form-modal>
    </AuthenticatedLayout>
</template>

<script setup>
import { router, useForm, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import SakalFormModal from '@/Modals/SakalFormModal.vue'
import ProductForm from '@/Components/Dashboard/Inertia/V1/ProductForm.vue'
import { editProductSchema } from '@/Shared/Validation/ProductValidation.js'

const props = defineProps({
    product: {
        type: Object,
        required: true
    },
    categories: {
        type: Array,
        default: () => []
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

// Use the standardized validation schema
const schema = editProductSchema

// Form data - initialize with existing product data
const form = useForm({
    name: props.product.name || '',
    description: props.product.description || '',
    price: props.product.price || '',
    category_id: props.product.category_id || null,
    quantity: props.product.quantity || '',
    status: props.product.status || 'active',
    image: null, // File uploads start as null
    size: props.product.size || '',
    type: props.product.type || '',
    ingredients: props.product.ingredients || ''
})

// Methods
const handleSubmit = () => {
    form.patch(route('products.update', props.product.id), {
        onSuccess: () => {
            if (props.baseRoute) {
                router.get(route(props.baseRoute))
            } else {
                router.get(route('products.index'))
            }
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

// Translation helper (you can implement this based on your i18n setup)
const __ = (key) => {
    // Simple fallback - replace with your actual translation logic
    const translations = {
        'Edit Product': 'Edit Product',
        'Cancel': 'Cancel',
        'Update Product': 'Update Product'
    }
    return translations[key] || key
}
</script>
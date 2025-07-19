<template>
    <AuthenticatedLayout title="Edit Product">
        <div>
            <inertia-head :title="__('Edit Product')"></inertia-head>
            <vee-form :validation-schema="schema" @submit="submitCallback()" v-slot="{ meta, setErrors, errors }"
                :initial-values="form">
                <sakal-modal>
                    <template #title>
                        {{ __("Edit Product") }}: {{ product.name }}
                    </template>

                    <ProductForm v-model="form" :categories="props.categories" :product="product" :error-messages="errors" />

                    <template #footer>
                        <v-btn 
                            variant="outlined" 
                            @click="goBack" 
                            :disabled="form.processing">
                            {{ __("Cancel") }}
                        </v-btn>
                        <v-btn 
                            type="submit" 
                            color="primary" 
                            :disabled="!meta.valid || form.processing"
                            @click.prevent="submitCallback(setErrors)" 
                            prepend-icon="mdi-content-save"
                            :loading="form.processing">
                            {{ __("Update Product") }}
                        </v-btn>
                    </template>
                </sakal-modal>
            </vee-form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { reactive } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import SakalModal from '@/Pages/Dashboard/Inertia/V1/Modals/FormSakal/SakalModal.vue'
import ProductForm from '@/Pages/Dashboard/Inertia/V1/ProductForm/ProductForm.vue'
import * as yup from 'yup'

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

// Form validation schema
const schema = yup.object({
    name: yup.string().required('Product name is required').max(255, 'Name too long'),
    description: yup.string().required('Description is required').max(1000, 'Description too long'),
    price: yup.number().required('Price is required').min(0, 'Price must be positive'),
    category_id: yup.number().nullable(),
    quantity: yup.number().min(0, 'Quantity must be positive').nullable(),
    status: yup.string().oneOf(['active', 'inactive']),
    size: yup.string().max(100, 'Size too long').nullable(),
    type: yup.string().max(100, 'Type too long').nullable(),
    ingredients: yup.string().max(1000, 'Ingredients too long').nullable(),
    image: yup.mixed().nullable()
})

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
const submitCallback = (setErrors) => {
    form.patch(route('dashboard.products.update', props.product.id), {
        onSuccess: () => {
            router.get(route('dashboard.products.index'))
        },
        onError: (errors) => {
            if (setErrors) {
                setErrors(errors)
            }
        }
    })
}

const goBack = () => {
    router.get(route('dashboard.products.index'))
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

<style scoped>
/* Minimal styling - the modal handles most of the layout */
</style>
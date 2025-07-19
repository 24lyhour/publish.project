<template>
    <AuthenticatedLayout title="Create Product">
        <div>
            <inertia-head :title="__('Create New Product')"></inertia-head>
            <vee-form :validation-schema="schema" @submit="submitCallback()" v-slot="{ meta, setErrors, errors }"
                :initial-values="form">
                <sakal-modal>
                    <template #title>
                        {{ __("Create a New Product") }}
                    </template>

                    <ProductForm v-model="form" :categories="props.categories" :error-messages="errors" />

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
                            {{ __("Save Product") }}
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

// Form data
const form = useForm({
    name: '',
    description: '',
    price: '',
    category_id: null,
    quantity: '',
    status: 'active',
    image: null,
    size: '',
    type: '',
    ingredients: ''
})

// Methods
const submitCallback = (setErrors) => {
    form.post(route('dashboard.products.store'), {
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
        'Create New Product': 'Create New Product',
        'Create a New Product': 'Create a New Product',
        'Cancel': 'Cancel',
        'Save Product': 'Save Product'
    }
    return translations[key] || key
}
</script>

<style scoped>
/* Minimal styling - the modal handles most of the layout */
</style>
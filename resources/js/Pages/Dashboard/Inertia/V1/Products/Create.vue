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
import SakalModal from '@/Modals/SakalFormModal.vue'
import ProductForm from '@/Components/Dashboard/Inertia/V1/ProductForm.vue'
import { createProductSchema } from '@/Shared/Validation/ProductValidation.js'

const props = defineProps({
    categories: {
        type: Array,
        default: () => []
    }
})

// Use the standardized validation schema
const schema = createProductSchema

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
    form.post(route('products.store'), {
        onSuccess: () => {
            router.get(route('products.index'))
        },
        onError: (errors) => {
            if (setErrors) {
                setErrors(errors)
            }
        }
    })
}

const goBack = () => {
    router.get(route('products.index'))
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
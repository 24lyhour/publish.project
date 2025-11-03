<template>
    <SakalForm 
        :show="true" 
        :title="__('Create New Product')"
        subtitle="Fill in the product details below"
        icon="package-plus"
        icon-color="blue"
        size="xl"
        :submit-text="__('Save Product')" 
        :cancel-text="__('Cancel')" 
        submit-color="blue"
        :processing="form.processing"
        @cancel="goBack"
        @close="goBack" 
        @submit="handleSubmit">
        
        <ProductForm 
            v-model="form" 
            :categories="props.categories" 
            :errors="form.errors" 
            :processing="form.processing" />
    </SakalForm>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3'
import SakalForm from '@/Modals/SakalForm.vue'
import ProductForm from '@/Components/Dashboard/Inertia/V1/ProductForm.vue'

const props = defineProps({
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
const handleSubmit = () => {
    form.post(route('products.store'), {
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

// Translation helper
const __ = (key) => {
    const translations = {
        'Create New Product': 'Create New Product',
        'Save Product': 'Save Product',
        'Cancel': 'Cancel'
    }
    return translations[key] || key
}
</script>
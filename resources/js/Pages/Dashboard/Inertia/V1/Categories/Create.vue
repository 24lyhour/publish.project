<template>
    <AuthenticatedLayout title="Create Category">
        <div>
            <inertia-head :title="__('Create New Category')"></inertia-head>
            <vee-form :validation-schema="schema" @submit="submitCallback()" v-slot="{ meta, setErrors, errors }"
                :initial-values="form">
                <SakalModal>
                    <template #title>
                        {{ __("Create a New Category") }}
                    </template>

                    <CategoryForm v-model="form" :menus="props.menus" :error-messages="errors" />

                    <template #footer>
                        <Button 
                            variant="outline" 
                            @click="goBack" 
                            :disabled="form.processing">
                            {{ __("Cancel") }}
                        </Button>
                        <Button 
                            type="submit"
                            :disabled="!meta.valid || form.processing"
                            @click.prevent="submitCallback(setErrors)"
                            :class="{ 'opacity-50': form.processing }">
                            {{ form.processing ? 'Saving...' : __("Save Category") }}
                        </Button>
                    </template>
                </SakalModal>
            </vee-form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import SakalModal from '@/Modals/SakalFormModal.vue'
import CategoryForm from '@/Components/Dashboard/Inertia/V1/CategoryForm.vue'
import { createCategorySchema } from '@/Shared/Validation/CategoryValidation.js'
import { Button } from '@/Components/ui/button'

const props = defineProps({
    menus: {
        type: Array,
        default: () => []
    }
})

// Use the standardized validation schema
const schema = createCategorySchema

// Form data
const form = useForm({
    name: '',
    description: '',
    type: '',
    menu_id: null,
    price_sale: '',
    status: 'active',
    imageUrl: ''
})

// Methods
const submitCallback = (setErrors) => {
    form.post(route('dashboard.categories.store'), {
        onSuccess: () => {
            router.get(route('dashboard.categories.index'))
        },
        onError: (errors) => {
            if (setErrors) {
                setErrors(errors)
            }
        }
    })
}

const goBack = () => {
    router.get(route('dashboard.categories.index'))
}

// Translation helper (you can implement this based on your i18n setup)
const __ = (key) => {
    // Simple fallback - replace with your actual translation logic
    const translations = {
        'Create New Category': 'Create New Category',
        'Create a New Category': 'Create a New Category',
        'Cancel': 'Cancel',
        'Save Category': 'Save Category'
    }
    return translations[key] || key
}
</script>

<style scoped>
/* Minimal styling - the modal handles most of the layout */
</style>
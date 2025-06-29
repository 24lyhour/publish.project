<template>
    <AuthenticatedLayout title="Edit Product">
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Product</h1>

            <form @submit.prevent="submit" class="bg-white shadow-md rounded-lg p-6 space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input v-model="form.name" type="text" id="name"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                    <input v-model="form.price" type="number" step="0.01" id="price"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <div v-if="form.errors.price" class="text-red-500 text-xs mt-1">{{ form.errors.price }}</div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea v-model="form.description" id="description" rows="4"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <Link :href="route('products.index')" class="text-sm text-gray-600 hover:text-gray-900">Cancel
                    </Link>
                    <v-btn type="submit" color="primary" variant="flat" :loading="form.processing">
                        Save Changes
                    </v-btn>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

/**
 * Props Define Object
 * 
 */
const props = defineProps({
    product: Object,
});

/**
 * Use Form Object
 * 
 */
const form = useForm({
    name: props.product.name,
    price: props.product.price,
    description: props.product.description,
});

/**
 * Handle submits
 * 
 */
const submit = () => {
    form.put(route('products.update', props.product.id));
};
</script>
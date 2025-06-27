<template>
    <!-- I'm assuming you have a layout component, if not, this can be a simple div -->
    <!-- Note: I've wrapped the content in a v-container for better spacing with Vuetify components -->
    <AuthenticatedLayout title="Products">
        <v-container class="p-2" fluid>
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Products
                    <v-icon icon="mdi-package-variant-closed" class="ml-2" />
                </h1>
                <Link :href="route('products.create')">
                <v-btn color="primary" variant="flat">Create Product</v-btn>
                </Link>
            </div>

            <!-- Success Message -->
            <div v-if="$page.props.flash.success"
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ $page.props.flash.success }}</p>
            </div>

            <v-card>
                <v-data-table :headers="headers" :items="products.data" item-value="id" class="elevation-1"
                    hide-default-footer>
                    <template #item="{ internalItem }">
                        <tr>
                            <td>
                                <div class="d-flex align-center py-2">
                                    <v-avatar :image="internalItem.raw.image_url || 'https://via.placeholder.com/150'"
                                        size="40" class="me-4"></v-avatar>
                                    <div>
                                        <div class="font-weight-bold">{{ internalItem.raw.name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">${{ internalItem.raw.price }}</td>
                            <td>
                                <div class="truncate" style="max-width: 300px;">{{ internalItem.raw.description }}</div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-center">
                                    <v-btn color="secondary" variant="tonal" size="small"
                                        @click="openEditModal(internalItem.raw)">
                                        Edit
                                    </v-btn>
                                    <v-btn color="error" variant="tonal" size="small"
                                        @click="openDeleteModal(internalItem.raw)">
                                        Delete
                                    </v-btn>
                                </div>
                            </td>
                        </tr>
                    </template>
                </v-data-table>
            </v-card>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                <div class="flex rounded-md shadow-sm">
                    <Link v-for="(link, index) in products.links" :key="link.label" :href="link.url"
                        class="px-4 py-2 border text-sm font-medium" :class="{
                            'bg-blue-500 text-white': link.active,
                            'text-gray-700 bg-white hover:bg-gray-50': !link.active,
                            'rounded-l-md': index === 0,
                            'rounded-r-md': index === products.links.length - 1,
                            'opacity-50 cursor-not-allowed': !link.url
                        }" v-html="link.label" as="button" :disabled="!link.url" />
                </div>
            </div>
        </v-container>

        <!-- Edit Product Modal -->
        <FlexibleModal v-model="isEditModalOpen" title="Edit Product">
            <form @submit.prevent="submitEdit" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input v-model="editForm.name" type="text" id="name"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <div v-if="editForm.errors.name" class="text-red-500 text-xs mt-1">{{ editForm.errors.name }}</div>
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                    <input v-model="editForm.price" type="number" step="0.01" id="price"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <div v-if="editForm.errors.price" class="text-red-500 text-xs mt-1">{{ editForm.errors.price }}
                    </div>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea v-model="editForm.description" id="description" rows="4"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    <div v-if="editForm.errors.description" class="text-red-500 text-xs mt-1">{{
                        editForm.errors.description }}</div>
                </div>
            </form>
            <template #actions>
                <v-btn color="primary" variant="flat" @click="submitEdit" :loading="editForm.processing">
                    Save Changes
                </v-btn>
                <v-btn variant="text" @click="isEditModalOpen = false">
                    Close
                </v-btn>
            </template>
        </FlexibleModal>

        <!-- Delete Confirmation Modal -->
        <FlexibleModal v-if="productToDelete" v-model="isDeleteModalOpen" title="Confirm Deletion">
            <p class="text-gray-700">
                Are you sure you want to delete the product
                <strong class="font-semibold">{{ productToDelete.name }}</strong>?
                This action cannot be undone.
            </p>
            <template #actions>
                <v-btn color="error" variant="flat" @click="submitDelete">
                    Delete
                </v-btn>
                <v-btn variant="text" @click="isDeleteModalOpen = false">
                    Cancel
                </v-btn>
            </template>
        </FlexibleModal>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import FlexibleModal from '@/Components/FlexibleModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

/**
 * Define props for this component
 * 
 */
const props = defineProps({
    products: Object,
});

const headers = ref([
    { title: 'Product', key: 'name', align: 'start', sortable: false },
    { title: 'Price', key: 'price', align: 'end', sortable: false },
    { title: 'Description', key: 'description', align: 'start', sortable: false, width: '40%' },
    { title: 'Actions', key: 'actions', sortable: false, align: 'center' },
]);

/**
 * Modal State
 * 
 */
const isEditModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const productToDelete = ref(null);
/**
 * 
 * Edit Form
 * 
 * 
 */
const editForm = useForm({
    id: null,
    name: '',
    price: '',
    description: '',
});

/**
 * 
 * @param product 
 * 
 * Open Edit Modal
 */
const openEditModal = (product) => {
    editForm.id = product.id;
    editForm.name = product.name;
    editForm.price = product.price;
    editForm.description = product.description;
    editForm.errors = {}; // Clear previous errors
    isEditModalOpen.value = true;
};

const submitEdit = () => {
    editForm.put(route('products.update', editForm.id), {
        preserveScroll: true,
        onSuccess: () => {
            isEditModalOpen.value = false;
            editForm.reset();
        },
    });
};

/**
 * 
 * @param product 
 * 
 * Open Delete Modal
 */
const openDeleteModal = (product) => {
    productToDelete.value = product;
    isDeleteModalOpen.value = true;
};

/**
 * Submit Delete
 * 
 */
const submitDelete = () => {
    router.delete(route('products.destroy', productToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            isDeleteModalOpen.value = false;
            productToDelete.value = null;
        },
    });
};
</script>
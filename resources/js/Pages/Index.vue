<template>
    <div class="p-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Our Products</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Product Card -->
            <div v-for="product in products" :key="product.id"
                class="bg-white border rounded-lg shadow-md overflow-hidden">
                <img :src="product.imageUrl" :alt="product.name" class="w-full h-56 object-cover" />
                <div class="p-4">
                    <h2 class="text-xl font-semibold text-gray-900">{{ product.name }}</h2>
                    <p class="text-lg text-gray-700 mt-1">{{ product.price }}</p>
                    <v-btn color="primary" variant="tonal" class="mt-4 w-full" @click="openProductModal(product)">
                        More Info
                    </v-btn>
                </div>
            </div>
        </div>

        <!-- The Flexible Modal -->
        <FlexibleModal v-if="selectedProduct" v-model="isModalOpen" :title="selectedProduct.name">
            <!-- Default slot content -->
            <div class="flex flex-col items-center text-center">
                <img :src="selectedProduct.imageUrl" :alt="selectedProduct.name"
                    class="w-full max-w-xs rounded-lg shadow-lg mb-4" />
                <h3 class="text-2xl font-bold text-gray-900">{{ selectedProduct.price }}</h3>
                <p class="mt-2 text-gray-600">{{ selectedProduct.description }}</p>
            </div>

            <!-- Actions slot content -->
            <template #actions>
                <v-btn color="primary" variant="flat" @click="isModalOpen = false">
                    Add to Cart
                </v-btn>
                <v-btn variant="text" @click="isModalOpen = false">
                    Close
                </v-btn>
            </template>
        </FlexibleModal>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import FlexibleModal from '@/Modals/FlexibleModal.vue';

/**
 * Product Data
 * 
 */
const products = ref([
    { id: 1, name: 'Awesome Gadget', price: '$49.99', description: 'This is the best gadget you will ever own. It does amazing things and will change your life.', imageUrl: 'https://via.placeholder.com/400x300' },
    { id: 2, name: 'Super Widget', price: '$29.99', description: 'A must-have widget for every household. Incredibly useful and easy to use.', imageUrl: 'https://via.placeholder.com/400x300' },
    { id: 3, name: 'Pro Tool', price: '$129.99', description: 'The professional tool for serious work. Built to last and perform.', imageUrl: 'https://via.placeholder.com/400x300' },
]);

const isModalOpen = ref(false);
const selectedProduct = ref(null);

/**
 * 
 * @param product 
 * 
 * Open Product Modal
 */
const openProductModal = (product) => {
    selectedProduct.value = product;
    isModalOpen.value = true;
};
</script>
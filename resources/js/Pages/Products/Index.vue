<template>

    <Head title="Products" />
    <AuthenticatedLayout>
        <v-container class="container pa-2" fluid>
            <v-card class="card rounded-xl">
                <v-card-title class="d-flex align-center pe-2">
                    <v-icon icon="mdi-package-variant-closed"></v-icon> &nbsp;
                    Product List

                    <v-spacer></v-spacer>

                    <Link :href="route('products.create')">
                    <v-btn>Create Product</v-btn>
                    </Link>
                </v-card-title>

                <v-data-table :headers="headers" :items="products.data" class="elevation-1">
                    <template #item.actions="{ item }">
                        <v-btn color="primary" variant="tonal" @click="openProductModal(item)">
                            Details
                        </v-btn>
                    </template>
                </v-data-table>
            </v-card>
        </v-container>

        <!-- The Flexible Modal for Product Details -->
        <FlexibleModal v-if="selectedProduct" v-model="isModalOpen" :title="selectedProduct.name">
            <p class="text-lg">{{ selectedProduct.description }}</p>
            <p class="text-2xl font-bold text-right mt-4">{{ selectedProduct.price }}</p>
        </FlexibleModal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import FlexibleModal from '../../Components/FlexibleModal.vue';

/**
 * Define props for this component
 * 
 * Products Object
 */
defineProps({
    products: Object,
});

/**
 * Header Table
 * 
 */
const headers = [
    { title: 'ID', key: 'id', align: 'start', width: '10%' },
    { title: 'Name', key: 'name', align: 'start' },
    { title: 'Description', key: 'description', align: 'start' },
    { title: 'Price', key: 'price', align: 'end', width: '10%' },
    { title: 'Actions', key: 'actions', sortable: false, align: 'center', width: '10%' },
];

/**
 * Modal State
 */
const isModalOpen = ref(false);
const selectedProduct = ref(null);

const openProductModal = (product) => {
    selectedProduct.value = product;
    isModalOpen.value = true;
};

</script>

<style scoped>
/**
* Modify styles
*/
.container {
    border-radius: 20px;
}

.v-btn {
    border-radius: 20px;
    background-color: white;
    color: black;
    border: solid 1px;
    filter: drop-shadow(0px 1px 0px black);
}
</style>
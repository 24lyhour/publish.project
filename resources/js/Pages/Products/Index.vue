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

                <v-data-table :headers="headers" :items="products.data" class="elevation-1"></v-data-table>
            </v-card>
        </v-container>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

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
    { title: 'Price', key: 'price', align: 'end' },
];
</script>

<style scoped>
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
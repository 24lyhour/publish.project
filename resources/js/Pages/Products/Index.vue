<script setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    products: Array,
});

const headers = [
    { title: 'ID', key: 'id', align: 'start', width: '10%' },
    { title: 'Name', key: 'name', align: 'start' },
    { title: 'Description', key: 'description', align: 'start' },
    { title: 'Price', key: 'price', align: 'end' },
];
</script>

<template>

    <Head title="Products" />

    <AuthenticatedLayout>
        <v-container>
            <v-card>
                <v-card-title class="d-flex align-center pe-2">
                    <v-icon icon="mdi-package-variant-closed"></v-icon> &nbsp;
                    Product List
                </v-card-title>

                <v-data-table :headers="headers" :items="products" class="elevation-1"></v-data-table>
            </v-card>
        </v-container>
    </AuthenticatedLayout>
</template>
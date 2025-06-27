<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
    price: '',
});

const submit = () => {
    form.post(route('products.store'));
};
</script>

<template>

    <Head title="Create Product" />

    <AuthenticatedLayout>
        <v-container>
            <v-card>
                <v-card-title>
                    <v-icon icon="mdi-plus-box"></v-icon> &nbsp;
                    Create New Product
                </v-card-title>
                <v-card-text>
                    <form @submit.prevent="submit">
                        <v-text-field v-model="form.name" label="Name"
                            :error-messages="form.errors.name"></v-text-field>
                        <v-textarea v-model="form.description" label="Description"
                            :error-messages="form.errors.description"></v-textarea>
                        <v-text-field v-model="form.price" label="Price" type="number"
                            :error-messages="form.errors.price"></v-text-field>
                        <v-btn type="submit" color="primary" :disabled="form.processing">Create</v-btn>
                    </form>
                </v-card-text>
            </v-card>
        </v-container>
    </AuthenticatedLayout>
</template>
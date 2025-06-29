<template>

    <Head title="Create Product" />

    <AuthenticatedLayout>
        <v-container>
            <v-row justify="center">
                <v-col cols="12" md="8" lg="6">
                    <v-card class="elevation-4" rounded="lg">
                        <v-card-title class="d-flex align-center bg-black text-white pa-4">
                            <v-icon icon="mdi-plus-box-outline" class="me-3" color="white"></v-icon>
                            <span class="text-h6 font-weight-medium">Create New Product</span>
                        </v-card-title>

                        <v-form @submit.prevent="submit">
                            <v-card-text class="pa-6">
                                <p class="text-body-2 text-grey-darken-1 mb-6">
                                    Fill out the details below to add a new product to your inventory.
                                </p>

                                <!-- Image Preview -->
                                <div v-if="previewUrl" class="mb-4 text-center">
                                    <v-img :src="previewUrl" class="mx-auto elevation-2" rounded="lg" max-height="250"
                                        aspect-ratio="16/9" cover></v-img>
                                    <v-btn variant="text" color="red" @click="clearImage" class="mt-2" size="small">
                                        Remove Image
                                    </v-btn>
                                </div>

                                <!-- Image Upload Field -->
                                <v-file-input v-model="form.image" label="Product Image (Optional)" variant="outlined"
                                    prepend-icon="" prepend-inner-icon="mdi-camera" density="comfortable"
                                    :error-messages="form.errors.image" accept="image/*" class="mb-4"></v-file-input>

                                <!-- Other Form Fields -->
                                <v-text-field v-model="form.name" label="Product Name" variant="outlined"
                                    prepend-inner-icon="mdi-package-variant-closed" density="comfortable"
                                    :error-messages="form.errors.name"></v-text-field>

                                <v-textarea v-model="form.description" label="Description" variant="outlined"
                                    prepend-inner-icon="mdi-text-long" density="comfortable" rows="4" class="mt-4"
                                    :error-messages="form.errors.description"></v-textarea>

                                <v-text-field v-model="form.price" label="Price" type="number" step="0.01"
                                    variant="outlined" prepend-inner-icon="mdi-currency-usd" density="comfortable"
                                    class="mt-4" :error-messages="form.errors.price"></v-text-field>
                            </v-card-text>

                            <v-divider></v-divider>

                            <v-card-actions class="pa-4">
                                <v-spacer></v-spacer>
                                <Link :href="route('products.index')" as="button">
                                <v-btn variant="text" size="large">
                                    Back to List
                                </v-btn>
                                </Link>
                                <v-btn type="submit" color="black" variant="elevated" :loading="form.processing"
                                    size="large" class="elevation-2">
                                    Create Product
                                </v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, watch, onUnmounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

/**
 * form UseForm Object 
 * 
 */
const form = useForm({
    name: '',
    description: '',
    price: '',
    image: null,
});

/**
 * Preview Url
 * 
 */
const previewUrl = ref(null);

/**
 * 
 * @param newFile 
 *  
 * Watch Image
 */
watch(() => form.image, (newFile) => {
    if (newFile) {
        // Create a temporary URL for the selected file to show a preview
        previewUrl.value = URL.createObjectURL(newFile);
    } else {
        previewUrl.value = null;
    }
});

/**
 * Clear Image 
 * 
 * handle clear image
 * 
 */
const clearImage = () => {
    form.image = null;
};

/**
 * 
 * @param event 
 * 
 * Submit
 */
const submit = () => {
    form.post(route('products.store'));
};

/**
 * on Unmounted
 * 
 * @param value
 */
onUnmounted(() => {
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
    }
});
</script>

<style scoped>
/* You can add any custom styles here if needed */
</style>
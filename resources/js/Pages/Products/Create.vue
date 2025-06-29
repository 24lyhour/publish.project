<template>

    <Head title="Create Product" />

    <AuthenticatedLayout>
        <v-container fluid class="py-6">
            <v-row justify="center">
                <v-col cols="12" xl="10" lg="11">
                    <div class="form-wrapper">
                        <v-card class="simple-card" rounded="10">
                            <!-- Simple Header -->
                            <v-card-title class="header-section pa-6">
                                <div class="d-flex align-center">
                                    <v-icon icon="mdi-plus-box" size="32" color="primary" class="me-3"></v-icon>
                                    <div>
                                        <h2 class="text-h4 mb-1">Create New Product</h2>
                                        <p class="text-subtitle-1 text-grey-darken-1 mb-0">
                                            Add a new product to your inventory
                                        </p>
                                    </div>
                                </div>
                            </v-card-title>

                            <v-divider></v-divider>

                            <v-form @submit.prevent="submit">
                                <v-card-text class="pa-8">
                                    <v-row>
                                        <!-- Left Column - Main Form -->
                                        <v-col cols="12" lg="8">
                                            <!-- Product Information -->
                                            <div class="mb-8">
                                                <h3 class="text-h6 mb-4 text-grey-darken-2">
                                                    <v-icon icon="mdi-information" class="me-2" color="blue"></v-icon>
                                                    Product Information
                                                </h3>

                                                <v-text-field v-model="form.name" label="Product Name"
                                                    placeholder="Enter product name" variant="outlined" size="large"
                                                    density="comfortable" :error-messages="form.errors.name"
                                                    class="mb-4 big-input" hide-details="auto">
                                                </v-text-field>

                                                <v-textarea v-model="form.description" label="Product Description"
                                                    placeholder="Describe your product..." variant="outlined"
                                                    density="comfortable" rows="5"
                                                    :error-messages="form.errors.description" class="big-input"
                                                    hide-details="auto">
                                                </v-textarea>
                                            </div>

                                            <!-- Pricing -->
                                            <div class="mb-8">
                                                <h3 class="text-h6 mb-4 text-grey-darken-2">
                                                    <v-icon icon="mdi-currency-usd" class="me-2" color="green"></v-icon>
                                                    Pricing
                                                </h3>
                                                <v-row>
                                                    <v-col cols="12" md="6">
                                                        <v-text-field v-model="form.price" label="Price (USD)"
                                                            placeholder="0.00" type="number" step="0.01"
                                                            variant="outlined" size="large" density="comfortable"
                                                            :error-messages="form.errors.price" class="big-input"
                                                            hide-details="auto">
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <v-text-field v-model="form.sku" label="SKU (Optional)"
                                                            placeholder="e.g., PROD-001" variant="outlined" size="large"
                                                            density="comfortable" class="big-input" hide-details="auto">
                                                        </v-text-field>
                                                    </v-col>
                                                </v-row>
                                            </div>

                                            <!-- Additional Details -->
                                            <div class="mb-6">
                                                <h3 class="text-h6 mb-4 text-grey-darken-2">
                                                    <v-icon icon="mdi-cog" class="me-2" color="orange"></v-icon>
                                                    Additional Details
                                                </h3>
                                                <v-row>
                                                    <v-col cols="12" md="6">
                                                        <v-select v-model="form.category"
                                                            :items="['Electronics', 'Clothing', 'Books', 'Home & Garden', 'Sports', 'Other']"
                                                            label="Category" variant="outlined" size="large"
                                                            density="comfortable" class="big-input" hide-details="auto">
                                                        </v-select>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <v-text-field v-model="form.stock_quantity"
                                                            label="Stock Quantity" placeholder="0" type="number"
                                                            variant="outlined" size="large" density="comfortable"
                                                            class="big-input" hide-details="auto">
                                                        </v-text-field>
                                                    </v-col>
                                                </v-row>

                                                <v-row class="mt-2">
                                                    <v-col cols="12">
                                                        <v-switch v-model="form.is_active" label="Active Product"
                                                            color="primary" size="large" hide-details>
                                                        </v-switch>
                                                    </v-col>
                                                </v-row>
                                            </div>
                                        </v-col>

                                        <!-- Right Column - Image Upload -->
                                        <v-col cols="12" lg="4">
                                            <div class="image-section">
                                                <h3 class="text-h6 mb-4 text-grey-darken-2">
                                                    <v-icon icon="mdi-camera" class="me-2" color="purple"></v-icon>
                                                    Product Image
                                                </h3>

                                                <!-- File Upload -->
                                                <v-file-input v-model="form.image" label="Upload Image"
                                                    variant="outlined" size="large" density="comfortable"
                                                    :error-messages="form.errors.image" accept="image/*"
                                                    class="big-input mb-4" hide-details="auto">
                                                </v-file-input>

                                                <!-- Image Preview -->
                                                <div v-if="previewUrl" class="image-preview mb-4">
                                                    <v-img :src="previewUrl" class="preview-img" height="300" cover>
                                                    </v-img>
                                                    <v-btn @click="clearImage" color="red" variant="elevated"
                                                        size="small" class="remove-btn mt-2">
                                                        <v-icon icon="mdi-delete" class="me-1"></v-icon>
                                                        Remove Image
                                                    </v-btn>
                                                </div>

                                                <!-- Upload Placeholder -->
                                                <div v-else class="upload-placeholder">
                                                    <v-card variant="outlined" class="pa-8 text-center">
                                                        <v-icon icon="mdi-image-plus" size="80" color="grey"></v-icon>
                                                        <h4 class="mt-4 text-grey-darken-1">No Image Selected</h4>
                                                        <p class="text-grey">Upload a product image (JPG, PNG, Max 5MB)
                                                        </p>
                                                    </v-card>
                                                </div>
                                            </div>
                                        </v-col>
                                    </v-row>
                                </v-card-text>

                                <v-divider></v-divider>

                                <!-- Action Buttons -->
                                <v-card-actions class="pa-6">
                                    <v-spacer></v-spacer>
                                    <Link :href="route('products.index')" as="button">
                                    <v-btn variant="outlined" size="large" class="me-3" :disabled="form.processing">
                                        <v-icon icon="mdi-arrow-left" class="me-2"></v-icon>
                                        Back to List
                                    </v-btn>
                                    </Link>
                                    <v-btn type="submit" color="primary" variant="elevated" :loading="form.processing"
                                        size="large">
                                        <v-icon icon="mdi-plus" class="me-2"></v-icon>
                                        Create Product
                                    </v-btn>
                                </v-card-actions>
                            </v-form>
                        </v-card>
                    </div>
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
 * Use form
 * 
 */
const form = useForm({
    name: '',
    description: '',
    price: '',
    sku: '',
    category: '',
    stock_quantity: '',
    is_active: true,
    image: null,
});

/**
 * Preview Url
 * 
 */
const previewUrl = ref(null);

/**
 * Watch for image changes
 * 
 * 
 */
watch(() => form.image, (newFile) => {
    if (newFile) {
        previewUrl.value = URL.createObjectURL(newFile);
    } else {
        previewUrl.value = null;
    }
});

/**
 * Clear Image
 * 
 * 
 * 
 */
const clearImage = () => {
    form.image = null;
};

/**
 * Submit Call Back
 * 
 */
const submit = () => {
    form.post(route('products.store'));
};

/**
 * Cleanup
 * 
 * Life cycle Hooks
 */
onUnmounted(() => {
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
    }
});
</script>

<style scoped>
.simple-card {
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #e0e0e0;
}

.header-section {
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
}

.big-input {
    font-size: 16px;
}

.big-input:deep(.v-field) {
    min-height: 56px;
    font-size: 16px;
}

.big-input:deep(.v-field__input) {
    font-size: 16px;
    padding: 16px;
}

.big-input:deep(.v-label) {
    font-size: 16px;
}

.preview-img {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
}

.remove-btn {
    width: 100%;
}

.upload-placeholder .v-card {
    border: 2px dashed #ccc;
    background: #fafafa;
}

@media (max-width: 768px) {
    .simple-card {
        margin: 8px;
    }

    .big-input:deep(.v-field) {
        min-height: 52px;
    }
}
</style>
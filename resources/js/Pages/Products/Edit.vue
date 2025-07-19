<template>
    <AuthenticatedLayout title="Edit Product">
        <v-container fluid class="py-6">
            <v-row justify="center">
                <v-col cols="12" xl="8" lg="10">
                    <div class="form-wrapper">
                        <v-card class="simple-card" rounded="10">
                            <!-- Modern Header -->
                            <v-card-title class="header-section pa-6">
                                <div class="d-flex align-center">
                                    <v-icon icon="mdi-pencil-box" size="32" color="primary" class="me-3"></v-icon>
                                    <div>
                                        <h2 class="text-h4 mb-1">Edit Product</h2>
                                        <p class="text-subtitle-1 text-grey-darken-1 mb-0">
                                            Update product information
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
                                            <div class="mb-6">
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
                                                </v-row>
                                            </div>
                                        </v-col>

                                        <!-- Right Column - Product Preview/Stats -->
                                        <v-col cols="12" lg="4">
                                            <div class="preview-section">
                                                <h3 class="text-h6 mb-4 text-grey-darken-2">
                                                    <v-icon icon="mdi-eye" class="me-2" color="purple"></v-icon>
                                                    Product Preview
                                                </h3>

                                                <!-- Preview Card -->
                                                <v-card variant="outlined" class="preview-card mb-4">
                                                    <v-card-text class="pa-4">
                                                        <div class="d-flex align-center mb-3">
                                                            <v-avatar size="40" class="me-3 elevation-1" rounded="lg">
                                                                <v-icon icon="mdi-package-variant" color="grey"></v-icon>
                                                            </v-avatar>
                                                            <div>
                                                                <div class="text-subtitle-1 font-weight-bold">
                                                                    {{ form.name || 'Product Name' }}
                                                                </div>
                                                                <v-chip color="primary" size="small" variant="flat" class="text-white">
                                                                    ${{ form.price || '0.00' }}
                                                                </v-chip>
                                                            </div>
                                                        </div>
                                                        <p class="text-body-2 text-grey-darken-1 mb-0">
                                                            {{ form.description ? (form.description.length > 100 ? form.description.substring(0, 100) + '...' : form.description) : 'Product description will appear here...' }}
                                                        </p>
                                                    </v-card-text>
                                                </v-card>

                                                <!-- Quick Info -->
                                                <v-card variant="tonal" color="info" class="info-card">
                                                    <v-card-text class="pa-4">
                                                        <div class="d-flex align-center mb-2">
                                                            <v-icon icon="mdi-lightbulb-outline" class="me-2" color="info"></v-icon>
                                                            <span class="text-subtitle-2 font-weight-bold">Quick Tips</span>
                                                        </div>
                                                        <ul class="text-caption text-info-darken-1">
                                                            <li>Use clear, descriptive product names</li>
                                                            <li>Set competitive pricing</li>
                                                            <li>Write detailed descriptions</li>
                                                        </ul>
                                                    </v-card-text>
                                                </v-card>
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
                                            Cancel
                                        </v-btn>
                                    </Link>
                                    <v-btn type="submit" color="primary" variant="elevated" :loading="form.processing"
                                        size="large">
                                        <v-icon icon="mdi-content-save" class="me-2"></v-icon>
                                        Save Changes
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

<style scoped>
.simple-card {
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
}

.simple-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
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

.preview-card {
    border: 2px solid #e3f2fd;
    background: #fafafa;
    transition: all 0.3s ease;
}

.preview-card:hover {
    border-color: #2196f3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.2);
}

.info-card {
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.15);
}

.v-btn {
    border-radius: 8px !important;
    text-transform: none !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
}

.v-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.v-chip {
    border-radius: 6px !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
}

@media (max-width: 768px) {
    .simple-card {
        margin: 8px;
    }

    .big-input:deep(.v-field) {
        min-height: 52px;
    }

    .preview-section {
        margin-top: 2rem;
    }
}

/* Smooth transitions for all interactive elements */
* {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

/* Enhanced focus states for accessibility */
.v-btn:focus-visible {
    outline: 2px solid #1976d2;
    outline-offset: 2px;
}

.big-input:focus-within {
    transform: translateY(-1px);
}
</style>
<template>
    <AuthenticatedLayout title="Create Product">
        <div class="create-product-page">
            <!-- Modern Header -->
            <div class="page-header">
                <div class="header-content">
                    <div class="header-info">
                        <div class="header-icon">
                            <v-icon icon="mdi-plus-box" size="40" color="white"></v-icon>
                        </div>
                        <div>
                            <h1 class="page-title">Create New Product</h1>
                            <p class="page-subtitle">Add a new product to your inventory</p>
                        </div>
                    </div>
                    <Link :href="route('products.index')">
                        <v-btn variant="outlined" size="large" class="back-btn">
                            <v-icon start>mdi-arrow-left</v-icon>
                            Back to Products
                        </v-btn>
                    </Link>
                </div>
            </div>

            <!-- Progress Stepper -->
            <div class="stepper-section">
                <div class="stepper-content">
                    <div class="step" :class="{ active: currentStep >= 1 }">
                        <div class="step-number">1</div>
                        <div class="step-label">Basic Info</div>
                    </div>
                    <div class="step-line" :class="{ active: currentStep >= 2 }"></div>
                    <div class="step" :class="{ active: currentStep >= 2 }">
                        <div class="step-number">2</div>
                        <div class="step-label">Pricing</div>
                    </div>
                    <div class="step-line" :class="{ active: currentStep >= 3 }"></div>
                    <div class="step" :class="{ active: currentStep >= 3 }">
                        <div class="step-number">3</div>
                        <div class="step-label">Media</div>
                    </div>
                </div>
            </div>

            <!-- Main Form Content -->
            <div class="form-section">
                <v-row>
                    <!-- Left Column - Form -->
                    <v-col cols="12" lg="8">
                        <v-form @submit.prevent="submit" class="product-form">
                            <!-- Step 1: Basic Information -->
                            <div v-show="currentStep === 1" class="form-step">
                                <div class="step-header">
                                    <v-icon icon="mdi-information" size="24" color="primary" class="me-2"></v-icon>
                                    <h3 class="step-title">Basic Information</h3>
                                </div>
                                <div class="form-content">
                                    <v-text-field 
                                        v-model="form.name" 
                                        label="Product Name" 
                                        placeholder="Enter a descriptive product name" 
                                        variant="outlined" 
                                        density="comfortable" 
                                        :error-messages="form.errors.name"
                                        class="form-field"
                                        counter="255"
                                        required>
                                        <template #prepend-inner>
                                            <v-icon icon="mdi-package" color="grey-darken-1"></v-icon>
                                        </template>
                                    </v-text-field>

                                    <v-textarea 
                                        v-model="form.description" 
                                        label="Product Description" 
                                        placeholder="Describe your product in detail..." 
                                        variant="outlined" 
                                        density="comfortable" 
                                        rows="5"
                                        :error-messages="form.errors.description" 
                                        class="form-field"
                                        counter="1000">
                                        <template #prepend-inner>
                                            <v-icon icon="mdi-text" color="grey-darken-1"></v-icon>
                                        </template>
                                    </v-textarea>

                                    <v-row>
                                        <v-col cols="12" md="6">
                                            <v-select 
                                                v-model="form.category"
                                                :items="categoryOptions"
                                                label="Category" 
                                                variant="outlined" 
                                                density="comfortable" 
                                                class="form-field">
                                                <template #prepend-inner>
                                                    <v-icon icon="mdi-tag" color="grey-darken-1"></v-icon>
                                                </template>
                                            </v-select>
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <v-text-field 
                                                v-model="form.sku" 
                                                label="SKU (Optional)" 
                                                placeholder="e.g., PROD-001" 
                                                variant="outlined" 
                                                density="comfortable" 
                                                class="form-field">
                                                <template #prepend-inner>
                                                    <v-icon icon="mdi-barcode" color="grey-darken-1"></v-icon>
                                                </template>
                                            </v-text-field>
                                        </v-col>
                                    </v-row>
                                </div>
                                <div class="step-actions">
                                    <v-spacer></v-spacer>
                                    <v-btn color="primary" @click="nextStep" :disabled="!canProceedFromStep1">
                                        Next: Pricing
                                        <v-icon end>mdi-arrow-right</v-icon>
                                    </v-btn>
                                </div>
                            </div>

                            <!-- Step 2: Pricing -->
                            <div v-show="currentStep === 2" class="form-step">
                                <div class="step-header">
                                    <v-icon icon="mdi-currency-usd" size="24" color="success" class="me-2"></v-icon>
                                    <h3 class="step-title">Pricing & Inventory</h3>
                                </div>
                                <div class="form-content">
                                    <v-row>
                                        <v-col cols="12" md="6">
                                            <v-text-field 
                                                v-model="form.price" 
                                                label="Price (USD)" 
                                                placeholder="0.00" 
                                                type="number" 
                                                step="0.01" 
                                                variant="outlined" 
                                                density="comfortable"
                                                :error-messages="form.errors.price" 
                                                class="form-field"
                                                required>
                                                <template #prepend-inner>
                                                    <v-icon icon="mdi-currency-usd" color="grey-darken-1"></v-icon>
                                                </template>
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <v-text-field 
                                                v-model="form.stock_quantity"
                                                label="Stock Quantity" 
                                                placeholder="0" 
                                                type="number" 
                                                variant="outlined" 
                                                density="comfortable"
                                                class="form-field">
                                                <template #prepend-inner>
                                                    <v-icon icon="mdi-package-variant" color="grey-darken-1"></v-icon>
                                                </template>
                                            </v-text-field>
                                        </v-col>
                                    </v-row>

                                    <div class="status-section">
                                        <h4 class="section-subtitle">Product Status</h4>
                                        <v-switch 
                                            v-model="form.is_active" 
                                            label="Product is active and available for sale" 
                                            color="primary" 
                                            class="status-switch">
                                        </v-switch>
                                        <p class="status-description">
                                            {{ form.is_active ? 'Product will be visible to customers' : 'Product will be hidden from customers' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="step-actions">
                                    <v-btn variant="outlined" @click="prevStep">
                                        <v-icon start>mdi-arrow-left</v-icon>
                                        Back
                                    </v-btn>
                                    <v-spacer></v-spacer>
                                    <v-btn color="primary" @click="nextStep" :disabled="!canProceedFromStep2">
                                        Next: Media
                                        <v-icon end>mdi-arrow-right</v-icon>
                                    </v-btn>
                                </div>
                            </div>

                            <!-- Step 3: Media -->
                            <div v-show="currentStep === 3" class="form-step">
                                <div class="step-header">
                                    <v-icon icon="mdi-camera" size="24" color="purple" class="me-2"></v-icon>
                                    <h3 class="step-title">Product Media</h3>
                                </div>
                                <div class="form-content">
                                    <div class="upload-section">
                                        <v-file-input 
                                            v-model="form.image" 
                                            label="Upload Product Image" 
                                            variant="outlined" 
                                            density="comfortable"
                                            :error-messages="form.errors.image" 
                                            accept="image/*"
                                            class="form-field">
                                            <template #prepend-inner>
                                                <v-icon icon="mdi-camera" color="grey-darken-1"></v-icon>
                                            </template>
                                        </v-file-input>

                                        <div class="upload-info">
                                            <p class="upload-text">
                                                <v-icon icon="mdi-information" size="16" class="me-1"></v-icon>
                                                Supported formats: JPG, PNG, GIF (Max size: 5MB)
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="step-actions">
                                    <v-btn variant="outlined" @click="prevStep">
                                        <v-icon start>mdi-arrow-left</v-icon>
                                        Back
                                    </v-btn>
                                    <v-spacer></v-spacer>
                                    <v-btn color="success" size="large" type="submit" :loading="form.processing" class="submit-btn">
                                        <v-icon start>mdi-check</v-icon>
                                        Create Product
                                    </v-btn>
                                </div>
                            </div>
                        </v-form>
                    </v-col>

                    <!-- Right Column - Preview -->
                    <v-col cols="12" lg="4">
                        <div class="preview-section">
                            <div class="preview-header">
                                <v-icon icon="mdi-eye" size="24" class="me-2"></v-icon>
                                <h3 class="preview-title">Live Preview</h3>
                            </div>
                            
                            <!-- Product Preview Card -->
                            <div class="preview-card">
                                <div class="preview-image">
                                    <v-img v-if="previewUrl" :src="previewUrl" aspect-ratio="1" cover>
                                    </v-img>
                                    <div v-else class="image-placeholder">
                                        <v-icon icon="mdi-image-plus" size="60" color="grey-lighten-2"></v-icon>
                                        <p class="placeholder-text">No image selected</p>
                                    </div>
                                    <v-btn v-if="previewUrl" 
                                           icon="mdi-close" 
                                           size="small" 
                                           variant="elevated" 
                                           color="error" 
                                           class="remove-image-btn"
                                           @click="clearImage">
                                    </v-btn>
                                </div>
                                <div class="preview-content">
                                    <div class="preview-badges">
                                        <v-chip 
                                            :color="form.is_active ? 'success' : 'error'" 
                                            size="small" 
                                            variant="flat"
                                            class="status-chip">
                                            {{ form.is_active ? 'Active' : 'Inactive' }}
                                        </v-chip>
                                        <v-chip 
                                            color="primary" 
                                            size="small" 
                                            variant="flat"
                                            class="price-chip">
                                            ${{ form.price || '0.00' }}
                                        </v-chip>
                                    </div>
                                    <h4 class="preview-name">{{ form.name || 'Product Name' }}</h4>
                                    <p class="preview-description">
                                        {{ form.description ? (form.description.length > 100 ? form.description.substring(0, 100) + '...' : form.description) : 'Product description will appear here...' }}
                                    </p>
                                    <div class="preview-meta">
                                        <div class="meta-item">
                                            <span class="meta-label">Category:</span>
                                            <span class="meta-value">{{ form.category || 'Uncategorized' }}</span>
                                        </div>
                                        <div class="meta-item">
                                            <span class="meta-label">SKU:</span>
                                            <span class="meta-value">{{ form.sku || 'Not set' }}</span>
                                        </div>
                                        <div class="meta-item">
                                            <span class="meta-label">Stock:</span>
                                            <span class="meta-value">{{ form.stock_quantity || '0' }} units</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Info -->
                            <div class="progress-info">
                                <h4 class="progress-title">Completion Progress</h4>
                                <v-progress-linear 
                                    :model-value="completionProgress" 
                                    color="primary" 
                                    height="8" 
                                    rounded>
                                </v-progress-linear>
                                <p class="progress-text">{{ completionProgress }}% Complete</p>
                                
                                <div class="checklist">
                                    <div class="checklist-item" :class="{ completed: form.name }">
                                        <v-icon :icon="form.name ? 'mdi-check-circle' : 'mdi-circle-outline'" 
                                                :color="form.name ? 'success' : 'grey'" 
                                                size="20"></v-icon>
                                        <span>Product name</span>
                                    </div>
                                    <div class="checklist-item" :class="{ completed: form.description }">
                                        <v-icon :icon="form.description ? 'mdi-check-circle' : 'mdi-circle-outline'" 
                                                :color="form.description ? 'success' : 'grey'" 
                                                size="20"></v-icon>
                                        <span>Description</span>
                                    </div>
                                    <div class="checklist-item" :class="{ completed: form.price }">
                                        <v-icon :icon="form.price ? 'mdi-check-circle' : 'mdi-circle-outline'" 
                                                :color="form.price ? 'success' : 'grey'" 
                                                size="20"></v-icon>
                                        <span>Price</span>
                                    </div>
                                    <div class="checklist-item" :class="{ completed: form.category }">
                                        <v-icon :icon="form.category ? 'mdi-check-circle' : 'mdi-circle-outline'" 
                                                :color="form.category ? 'success' : 'grey'" 
                                                size="20"></v-icon>
                                        <span>Category</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </v-col>
                </v-row>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, watch, onUnmounted, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

// Form data
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

// Component state
const previewUrl = ref(null);
const currentStep = ref(1);

// Category options
const categoryOptions = [
    'Electronics',
    'Clothing',
    'Books',
    'Home & Garden',
    'Sports',
    'Beauty',
    'Toys',
    'Food & Beverages',
    'Other'
];

// Computed properties
const canProceedFromStep1 = computed(() => {
    return form.name && form.description;
});

const canProceedFromStep2 = computed(() => {
    return form.price && parseFloat(form.price) > 0;
});

const completionProgress = computed(() => {
    let progress = 0;
    const totalFields = 4; // name, description, price, category
    
    if (form.name) progress += 25;
    if (form.description) progress += 25;
    if (form.price) progress += 25;
    if (form.category) progress += 25;
    
    return progress;
});

// Watch for image changes
watch(() => form.image, (newFile) => {
    if (newFile) {
        previewUrl.value = URL.createObjectURL(newFile);
    } else {
        previewUrl.value = null;
    }
});

// Methods
const nextStep = () => {
    if (currentStep.value < 3) {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const clearImage = () => {
    form.image = null;
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
        previewUrl.value = null;
    }
};

const submit = () => {
    form.post(route('products.store'));
};

// Cleanup
onUnmounted(() => {
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
    }
});
</script>

<style scoped>
.create-product-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 24px;
}

/* Header Styles */
.page-header {
    background: white;
    border-radius: 20px;
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.header-info {
    display: flex;
    align-items: center;
    gap: 20px;
}

.header-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.page-title {
    font-size: 2.25rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
    margin-bottom: 4px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.page-subtitle {
    color: #718096;
    margin: 0;
    font-size: 1.125rem;
}

.back-btn {
    border-radius: 12px !important;
    border: 2px solid #e2e8f0 !important;
}

.back-btn:hover {
    border-color: #667eea !important;
    background: rgba(102, 126, 234, 0.05) !important;
}

/* Stepper Styles */
.stepper-section {
    background: white;
    border-radius: 20px;
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.stepper-content {
    display: flex;
    align-items: center;
    justify-content: center;
    max-width: 600px;
    margin: 0 auto;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e2e8f0;
    color: #a0aec0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    transition: all 0.3s ease;
}

.step.active .step-number {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: scale(1.1);
}

.step-label {
    font-size: 0.875rem;
    color: #a0aec0;
    font-weight: 500;
    transition: all 0.3s ease;
}

.step.active .step-label {
    color: #2d3748;
    font-weight: 600;
}

.step-line {
    width: 100px;
    height: 3px;
    background: #e2e8f0;
    margin: 0 20px;
    transition: all 0.3s ease;
}

.step-line.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Form Styles */
.form-section {
    margin-bottom: 24px;
}

.product-form {
    background: white;
    border-radius: 20px;
    padding: 32px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    min-height: 600px;
}

.form-step {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.step-header {
    display: flex;
    align-items: center;
    margin-bottom: 32px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f7fafc;
}

.step-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

.form-content {
    flex: 1;
    margin-bottom: 32px;
}

.form-field {
    margin-bottom: 24px;
}

.section-subtitle {
    font-size: 1.125rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 16px 0;
}

.status-section {
    background: #f7fafc;
    border-radius: 12px;
    padding: 24px;
    margin-top: 24px;
}

.status-switch {
    margin: 16px 0;
}

.status-description {
    color: #718096;
    margin: 0;
    font-size: 0.875rem;
}

.upload-section {
    background: #f7fafc;
    border-radius: 12px;
    padding: 24px;
}

.upload-info {
    margin-top: 16px;
    text-align: center;
}

.upload-text {
    color: #718096;
    margin: 0;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.step-actions {
    display: flex;
    align-items: center;
    gap: 16px;
    padding-top: 16px;
    border-top: 2px solid #f7fafc;
}

.submit-btn {
    box-shadow: 0 4px 12px rgba(72, 187, 120, 0.4);
    border-radius: 12px !important;
}

/* Preview Styles */
.preview-section {
    background: white;
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 24px;
}

.preview-header {
    display: flex;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f7fafc;
}

.preview-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

.preview-card {
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 24px;
    transition: all 0.3s ease;
}

.preview-card:hover {
    border-color: #cbd5e0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.preview-image {
    position: relative;
    height: 200px;
}

.image-placeholder {
    height: 100%;
    background: #f7fafc;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.placeholder-text {
    color: #a0aec0;
    margin: 0;
    font-size: 0.875rem;
}

.remove-image-btn {
    position: absolute;
    top: 8px;
    right: 8px;
}

.preview-content {
    padding: 20px;
}

.preview-badges {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
}

.status-chip,
.price-chip {
    color: white !important;
    font-weight: 600;
}

.preview-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 8px 0;
    line-height: 1.4;
}

.preview-description {
    color: #718096;
    margin: 0 0 16px 0;
    line-height: 1.5;
    font-size: 0.875rem;
}

.preview-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.meta-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.875rem;
}

.meta-label {
    color: #a0aec0;
    font-weight: 500;
}

.meta-value {
    color: #4a5568;
    font-weight: 600;
}

/* Progress Info */
.progress-info {
    background: #f7fafc;
    border-radius: 12px;
    padding: 20px;
}

.progress-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 12px 0;
}

.progress-text {
    color: #718096;
    margin: 8px 0 16px 0;
    font-size: 0.875rem;
    text-align: center;
}

.checklist {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.checklist-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #718096;
    transition: all 0.3s ease;
}

.checklist-item.completed {
    color: #38a169;
    font-weight: 500;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .preview-section {
        position: static;
        margin-top: 24px;
    }
}

@media (max-width: 768px) {
    .create-product-page {
        padding: 16px;
    }

    .page-header {
        padding: 20px;
    }

    .header-content {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }

    .stepper-section {
        padding: 20px;
    }

    .stepper-content {
        flex-direction: column;
        gap: 16px;
    }

    .step-line {
        width: 3px;
        height: 40px;
        margin: 0;
    }

    .product-form {
        padding: 20px;
    }

    .preview-section {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .page-title {
        font-size: 1.75rem;
    }

    .header-icon {
        width: 60px;
        height: 60px;
    }

    .step-number {
        width: 35px;
        height: 35px;
    }

    .form-field {
        margin-bottom: 16px;
    }
}

/* Custom transitions */
.form-step {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Form field focus effects */
.form-field:deep(.v-field--focused) {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
}

/* Button hover effects */
.v-btn {
    transition: all 0.3s ease !important;
}

.v-btn:hover {
    transform: translateY(-2px);
}

/* Enhanced visual feedback */
.v-chip {
    transition: all 0.3s ease !important;
}

.preview-card:hover .v-chip {
    transform: scale(1.05);
}
</style>
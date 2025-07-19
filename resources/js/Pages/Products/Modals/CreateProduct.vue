<template>
    <SakalFormModal
        :show="true"
        title="Create New Product"
        subtitle="Add a new product to your inventory"
        icon="mdi-plus-box"
        icon-color="primary"
        :max-width="800"
        method="post"
        :action="route('products.store')"
        :form-data="initialFormData"
        submit-text="Create Product"
        submit-icon="mdi-check"
        submit-color="primary"
        @close="handleClose"
        @cancel="handleClose"
        @success="handleSuccess"
        @error="handleError">
        
        <template #default="{ form, errors, processing }">
            <!-- Step Progress -->
            <div class="form-steps">
                <div class="step-indicator">
                    <div class="step" :class="{ active: currentStep >= 1, completed: currentStep > 1 }">
                        <div class="step-number">1</div>
                        <span class="step-label">Basic Info</span>
                    </div>
                    <div class="step-line" :class="{ active: currentStep > 1 }"></div>
                    <div class="step" :class="{ active: currentStep >= 2, completed: currentStep > 2 }">
                        <div class="step-number">2</div>
                        <span class="step-label">Details</span>
                    </div>
                    <div class="step-line" :class="{ active: currentStep > 2 }"></div>
                    <div class="step" :class="{ active: currentStep >= 3 }">
                        <div class="step-number">3</div>
                        <span class="step-label">Media</span>
                    </div>
                </div>
            </div>

            <!-- Step 1: Basic Information -->
            <div v-show="currentStep === 1" class="form-step">
                <div class="step-header">
                    <h4>Basic Information</h4>
                    <p>Enter the essential details about your product</p>
                </div>
                
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            v-model="form.name"
                            label="Product Name *"
                            placeholder="Enter a descriptive product name"
                            variant="outlined"
                            density="comfortable"
                            :error-messages="errors.name"
                            counter="255"
                            required
                            class="mb-4">
                            <template #prepend-inner>
                                <v-icon icon="mdi-package" color="grey-darken-1"></v-icon>
                            </template>
                        </v-text-field>
                    </v-col>
                    
                    <v-col cols="12">
                        <v-textarea
                            v-model="form.description"
                            label="Product Description *"
                            placeholder="Describe your product in detail..."
                            variant="outlined"
                            density="comfortable"
                            rows="4"
                            :error-messages="errors.description"
                            counter="1000"
                            required>
                            <template #prepend-inner>
                                <v-icon icon="mdi-text" color="grey-darken-1"></v-icon>
                            </template>
                        </v-textarea>
                    </v-col>
                </v-row>

                <div class="step-actions">
                    <v-spacer></v-spacer>
                    <v-btn 
                        color="primary" 
                        @click="nextStep" 
                        :disabled="!canProceedFromStep1"
                        size="large">
                        Next Step
                        <v-icon end>mdi-arrow-right</v-icon>
                    </v-btn>
                </div>
            </div>

            <!-- Step 2: Details -->
            <div v-show="currentStep === 2" class="form-step">
                <div class="step-header">
                    <h4>Product Details</h4>
                    <p>Set pricing and categorization</p>
                </div>
                
                <v-row>
                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.price"
                            label="Price (USD) *"
                            placeholder="0.00"
                            type="number"
                            step="0.01"
                            variant="outlined"
                            density="comfortable"
                            :error-messages="errors.price"
                            required>
                            <template #prepend-inner>
                                <v-icon icon="mdi-currency-usd" color="grey-darken-1"></v-icon>
                            </template>
                        </v-text-field>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                        <v-select
                            v-model="form.category_id"
                            :items="categories"
                            item-title="name"
                            item-value="id"
                            label="Category"
                            variant="outlined"
                            density="comfortable"
                            :error-messages="errors.category_id">
                            <template #prepend-inner>
                                <v-icon icon="mdi-tag" color="grey-darken-1"></v-icon>
                            </template>
                        </v-select>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.quantity"
                            label="Stock Quantity"
                            placeholder="0"
                            type="number"
                            variant="outlined"
                            density="comfortable"
                            :error-messages="errors.quantity">
                            <template #prepend-inner>
                                <v-icon icon="mdi-package-variant" color="grey-darken-1"></v-icon>
                            </template>
                        </v-text-field>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                        <v-select
                            v-model="form.status"
                            :items="statusOptions"
                            label="Status"
                            variant="outlined"
                            density="comfortable"
                            :error-messages="errors.status">
                            <template #prepend-inner>
                                <v-icon icon="mdi-check-circle" color="grey-darken-1"></v-icon>
                            </template>
                        </v-select>
                    </v-col>
                </v-row>

                <div class="step-actions">
                    <v-btn variant="outlined" @click="prevStep" size="large">
                        <v-icon start>mdi-arrow-left</v-icon>
                        Previous
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn 
                        color="primary" 
                        @click="nextStep" 
                        :disabled="!canProceedFromStep2"
                        size="large">
                        Next Step
                        <v-icon end>mdi-arrow-right</v-icon>
                    </v-btn>
                </div>
            </div>

            <!-- Step 3: Media -->
            <div v-show="currentStep === 3" class="form-step">
                <div class="step-header">
                    <h4>Product Media</h4>
                    <p>Upload images and additional information</p>
                </div>
                
                <v-row>
                    <v-col cols="12">
                        <v-file-input
                            v-model="form.image"
                            label="Product Image"
                            variant="outlined"
                            density="comfortable"
                            :error-messages="errors.image"
                            accept="image/*"
                            prepend-icon=""
                            class="mb-4">
                            <template #prepend-inner>
                                <v-icon icon="mdi-camera" color="grey-darken-1"></v-icon>
                            </template>
                        </v-file-input>
                        
                        <div v-if="previewUrl" class="image-preview">
                            <v-img :src="previewUrl" max-height="200" contain class="rounded"></v-img>
                            <v-btn 
                                icon="mdi-close" 
                                size="small" 
                                variant="elevated" 
                                color="error" 
                                class="remove-btn"
                                @click="clearImage">
                            </v-btn>
                        </div>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.size"
                            label="Size"
                            placeholder="e.g., Large, 500ml, XL"
                            variant="outlined"
                            density="comfortable"
                            :error-messages="errors.size">
                            <template #prepend-inner>
                                <v-icon icon="mdi-resize" color="grey-darken-1"></v-icon>
                            </template>
                        </v-text-field>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.type"
                            label="Type"
                            placeholder="e.g., Premium, Standard"
                            variant="outlined"
                            density="comfortable"
                            :error-messages="errors.type">
                            <template #prepend-inner>
                                <v-icon icon="mdi-shape" color="grey-darken-1"></v-icon>
                            </template>
                        </v-text-field>
                    </v-col>
                </v-row>

                <div class="step-actions">
                    <v-btn variant="outlined" @click="prevStep" size="large">
                        <v-icon start>mdi-arrow-left</v-icon>
                        Previous
                    </v-btn>
                    <v-spacer></v-spacer>
                </div>
            </div>

            <!-- Progress Summary -->
            <div class="progress-summary">
                <v-progress-linear 
                    :model-value="completionProgress" 
                    color="primary" 
                    height="6" 
                    rounded>
                </v-progress-linear>
                <p class="progress-text">{{ completionProgress }}% Complete</p>
            </div>
        </template>
    </SakalFormModal>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import SakalFormModal from '@/Components/SakalFormModal.vue'

const props = defineProps({
    categories: {
        type: Array,
        default: () => []
    }
})

// Form state
const currentStep = ref(1)
const previewUrl = ref(null)

// Form data
const initialFormData = {
    name: '',
    description: '',
    price: '',
    category_id: null,
    quantity: '',
    status: 'active',
    image: null,
    size: '',
    type: ''
}

// Options
const statusOptions = [
    { title: 'Active', value: 'active' },
    { title: 'Inactive', value: 'inactive' }
]

// Computed properties
const canProceedFromStep1 = computed(() => {
    return initialFormData.name && initialFormData.description
})

const canProceedFromStep2 = computed(() => {
    return initialFormData.price && parseFloat(initialFormData.price) > 0
})

const completionProgress = computed(() => {
    let progress = 0
    const totalFields = 5 // name, description, price, category, quantity
    
    if (initialFormData.name) progress += 20
    if (initialFormData.description) progress += 20
    if (initialFormData.price) progress += 20
    if (initialFormData.category_id) progress += 20
    if (initialFormData.quantity) progress += 20
    
    return progress
})

// Methods
const nextStep = () => {
    if (currentStep.value < 3) {
        currentStep.value++
    }
}

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--
    }
}

const clearImage = () => {
    initialFormData.image = null
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value)
        previewUrl.value = null
    }
}

// Watch for image changes
watch(() => initialFormData.image, (newFile) => {
    if (newFile) {
        previewUrl.value = URL.createObjectURL(newFile)
    } else {
        previewUrl.value = null
    }
})

// Event handlers
const handleClose = () => {
    router.visit(route('products.index'))
}

const handleSuccess = () => {
    // Modal will auto-close due to closeOnSuccess: true
}

const handleError = (errors) => {
    console.error('Form submission errors:', errors)
}
</script>

<style scoped>
.form-steps {
    margin-bottom: 32px;
}

.step-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    max-width: 400px;
    margin: 0 auto;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    position: relative;
}

.step-number {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #e2e8f0;
    color: #a0aec0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
}

.step.active .step-number {
    background: #667eea;
    color: white;
    transform: scale(1.1);
}

.step.completed .step-number {
    background: #48bb78;
    color: white;
}

.step-label {
    font-size: 12px;
    color: #a0aec0;
    font-weight: 500;
    transition: all 0.3s ease;
}

.step.active .step-label,
.step.completed .step-label {
    color: #2d3748;
    font-weight: 600;
}

.step-line {
    width: 60px;
    height: 2px;
    background: #e2e8f0;
    margin: 0 16px;
    transition: all 0.3s ease;
}

.step-line.active {
    background: #667eea;
}

.form-step {
    min-height: 300px;
    display: flex;
    flex-direction: column;
}

.step-header {
    text-align: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e2e8f0;
}

.step-header h4 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 8px 0;
}

.step-header p {
    color: #718096;
    margin: 0;
    font-size: 0.875rem;
}

.step-actions {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-top: auto;
    padding-top: 24px;
}

.image-preview {
    position: relative;
    margin-top: 16px;
    max-width: 200px;
}

.remove-btn {
    position: absolute;
    top: 8px;
    right: 8px;
}

.progress-summary {
    margin-top: 24px;
    padding-top: 16px;
    border-top: 1px solid #e2e8f0;
}

.progress-text {
    text-align: center;
    color: #718096;
    margin: 8px 0 0 0;
    font-size: 0.875rem;
}

/* Responsive */
@media (max-width: 768px) {
    .step-indicator {
        flex-direction: column;
        gap: 16px;
    }
    
    .step-line {
        width: 2px;
        height: 30px;
        margin: 0;
    }
}
</style>
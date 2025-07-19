<template>
    <SakalFormModal
        :show="show"
        :title="modalTitle"
        :subtitle="modalSubtitle"
        :icon="modalIcon"
        :icon-color="modalIconColor"
        :max-width="800"
        :method="method"
        :action="action"
        :form-data="formData"
        :submit-text="submitText"
        :submit-icon="submitIcon"
        :submit-color="submitColor"
        :type="type"
        @close="$emit('close')"
        @cancel="$emit('cancel')"
        @success="$emit('success')"
        @error="$emit('error')">
        
        <template #default="{ form, errors, processing }">
            <!-- Step Progress for Create Mode -->
            <div v-if="mode === 'create'" class="form-steps">
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

            <v-row>
                <!-- Form Column -->
                <v-col :cols="mode === 'edit' ? 8 : 12" :lg="mode === 'edit' ? 8 : 12">
                    <!-- Step 1: Basic Information (Create Mode) -->
                    <div v-if="mode === 'create' && currentStep === 1" class="form-step">
                        <div class="step-header">
                            <h4>Basic Information</h4>
                            <p>Enter the essential details about your product</p>
                        </div>
                        
                        <ProductBasicFields 
                            :form="form" 
                            :errors="errors" 
                            :categories="categories" />

                        <div class="step-actions">
                            <v-spacer></v-spacer>
                            <v-btn 
                                color="primary" 
                                @click="nextStep" 
                                :disabled="!canProceedFromStep1(form)"
                                size="large">
                                Next Step
                                <v-icon end>mdi-arrow-right</v-icon>
                            </v-btn>
                        </div>
                    </div>

                    <!-- Step 2: Details (Create Mode) -->
                    <div v-else-if="mode === 'create' && currentStep === 2" class="form-step">
                        <div class="step-header">
                            <h4>Product Details</h4>
                            <p>Set pricing and categorization</p>
                        </div>
                        
                        <ProductDetailFields 
                            :form="form" 
                            :errors="errors" 
                            :categories="categories" />

                        <div class="step-actions">
                            <v-btn variant="outlined" @click="prevStep" size="large">
                                <v-icon start>mdi-arrow-left</v-icon>
                                Previous
                            </v-btn>
                            <v-spacer></v-spacer>
                            <v-btn 
                                color="primary" 
                                @click="nextStep" 
                                :disabled="!canProceedFromStep2(form)"
                                size="large">
                                Next Step
                                <v-icon end>mdi-arrow-right</v-icon>
                            </v-btn>
                        </div>
                    </div>

                    <!-- Step 3: Media (Create Mode) -->
                    <div v-else-if="mode === 'create' && currentStep === 3" class="form-step">
                        <div class="step-header">
                            <h4>Product Media</h4>
                            <p>Upload images and additional information</p>
                        </div>
                        
                        <ProductMediaFields 
                            :form="form" 
                            :errors="errors" 
                            :preview-url="previewUrl"
                            @clear-image="clearImage" />

                        <div class="step-actions">
                            <v-btn variant="outlined" @click="prevStep" size="large">
                                <v-icon start>mdi-arrow-left</v-icon>
                                Previous
                            </v-btn>
                            <v-spacer></v-spacer>
                        </div>
                    </div>

                    <!-- Edit Mode - All Fields -->
                    <div v-else-if="mode === 'edit'" class="edit-form">
                        <div class="form-section">
                            <h4 class="section-title">
                                <v-icon icon="mdi-information" class="me-2" color="primary"></v-icon>
                                Basic Information
                            </h4>
                            <ProductBasicFields 
                                :form="form" 
                                :errors="errors" 
                                :categories="categories" />
                        </div>

                        <v-divider class="my-6"></v-divider>

                        <div class="form-section">
                            <h4 class="section-title">
                                <v-icon icon="mdi-currency-usd" class="me-2" color="success"></v-icon>
                                Pricing & Inventory
                            </h4>
                            <ProductDetailFields 
                                :form="form" 
                                :errors="errors" 
                                :categories="categories" />
                        </div>

                        <v-divider class="my-6"></v-divider>

                        <div class="form-section">
                            <h4 class="section-title">
                                <v-icon icon="mdi-cog" class="me-2" color="warning"></v-icon>
                                Additional Details
                            </h4>
                            <ProductMediaFields 
                                :form="form" 
                                :errors="errors" 
                                :preview-url="previewUrl"
                                @clear-image="clearImage"
                                :show-image="false" />
                        </div>
                    </div>

                    <!-- Progress Summary for Create Mode -->
                    <div v-if="mode === 'create'" class="progress-summary">
                        <v-progress-linear 
                            :model-value="completionProgress(form)" 
                            color="primary" 
                            height="6" 
                            rounded>
                        </v-progress-linear>
                        <p class="progress-text">{{ completionProgress(form) }}% Complete</p>
                    </div>
                </v-col>

                <!-- Preview Column (Edit Mode) -->
                <v-col v-if="mode === 'edit'" cols="12" lg="4">
                    <ProductPreview 
                        :form="form" 
                        :product="product" 
                        :categories="categories"
                        :preview-url="previewUrl" />
                </v-col>
            </v-row>
        </template>
    </SakalFormModal>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import SakalFormModal from './SakalFormModal.vue'
import ProductBasicFields from './ProductFields/ProductBasicFields.vue'
import ProductDetailFields from './ProductFields/ProductDetailFields.vue'
import ProductMediaFields from './ProductFields/ProductMediaFields.vue'
import ProductPreview from './ProductFields/ProductPreview.vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    mode: {
        type: String,
        default: 'create', // 'create' or 'edit'
        validator: value => ['create', 'edit'].includes(value)
    },
    product: {
        type: Object,
        default: null
    },
    categories: {
        type: Array,
        default: () => []
    },
    method: {
        type: String,
        default: 'post'
    },
    action: {
        type: String,
        default: null
    },
    type: {
        type: String,
        default: 'default'
    }
})

defineEmits(['close', 'cancel', 'success', 'error'])

// Component state
const currentStep = ref(1)
const previewUrl = ref(null)

// Computed properties
const modalTitle = computed(() => {
    return props.mode === 'create' ? 'Create New Product' : 'Edit Product'
})

const modalSubtitle = computed(() => {
    return props.mode === 'create' 
        ? 'Add a new product to your inventory'
        : `Update ${props.product?.name || 'product'}`
})

const modalIcon = computed(() => {
    return props.mode === 'create' ? 'mdi-plus-box' : 'mdi-pencil-box'
})

const modalIconColor = computed(() => 'primary')

const submitText = computed(() => {
    return props.mode === 'create' ? 'Create Product' : 'Update Product'
})

const submitIcon = computed(() => {
    return props.mode === 'create' ? 'mdi-check' : 'mdi-content-save'
})

const submitColor = computed(() => 'primary')

const formData = computed(() => {
    if (props.mode === 'edit' && props.product) {
        return {
            name: props.product.name || '',
            description: props.product.description || '',
            price: props.product.price || '',
            category_id: props.product.category_id || null,
            quantity: props.product.quantity || '',
            status: props.product.status || 'active',
            size: props.product.size || '',
            type: props.product.type || '',
            ingredients: props.product.ingredients || '',
            image: null
        }
    }
    
    return {
        name: '',
        description: '',
        price: '',
        category_id: null,
        quantity: '',
        status: 'active',
        image: null,
        size: '',
        type: '',
        ingredients: ''
    }
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
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value)
        previewUrl.value = null
    }
}

const canProceedFromStep1 = (form) => {
    return form.name && form.description
}

const canProceedFromStep2 = (form) => {
    return form.price && parseFloat(form.price) > 0
}

const completionProgress = (form) => {
    let progress = 0
    const totalFields = 5 // name, description, price, category, quantity
    
    if (form.name) progress += 20
    if (form.description) progress += 20
    if (form.price) progress += 20
    if (form.category_id) progress += 20
    if (form.quantity) progress += 20
    
    return progress
}

// Watch for image changes
watch(() => formData.value.image, (newFile) => {
    if (newFile) {
        previewUrl.value = URL.createObjectURL(newFile)
    } else {
        previewUrl.value = null
    }
})
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

.form-section {
    margin-bottom: 24px;
}

.section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
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
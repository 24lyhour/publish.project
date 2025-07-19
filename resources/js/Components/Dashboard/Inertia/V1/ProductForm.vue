<template>
    <div class="product-form">
        <!-- Basic Information Section -->
        <div class="form-section">
            <h4 class="section-title">
                <v-icon icon="mdi-information" class="me-2" color="primary"></v-icon>
                {{ __("Basic Information") }}
            </h4>
            
            <v-text-field
                v-model="formData.name"
                :label="__('Product Name') + ' *'"
                :placeholder="__('Enter a descriptive product name')"
                variant="outlined"
                density="comfortable"
                :error-messages="errorMessages.name"
                counter="255"
                required
                class="mb-4"
                @input="emitUpdate">
                <template #prepend-inner>
                    <v-icon icon="mdi-package" color="grey-darken-1"></v-icon>
                </template>
            </v-text-field>

            <v-textarea
                v-model="formData.description"
                :label="__('Product Description') + ' *'"
                :placeholder="__('Describe your product in detail...')"
                variant="outlined"
                density="comfortable"
                rows="4"
                :error-messages="errorMessages.description"
                counter="1000"
                required
                class="mb-4"
                @input="emitUpdate">
                <template #prepend-inner>
                    <v-icon icon="mdi-text" color="grey-darken-1"></v-icon>
                </template>
            </v-textarea>
        </div>

        <v-divider class="my-6"></v-divider>

        <!-- Pricing & Details Section -->
        <div class="form-section">
            <h4 class="section-title">
                <v-icon icon="mdi-currency-usd" class="me-2" color="success"></v-icon>
                {{ __("Pricing & Details") }}
            </h4>
            
            <v-row>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="formData.price"
                        :label="__('Price (USD)') + ' *'"
                        placeholder="0.00"
                        type="number"
                        step="0.01"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errorMessages.price"
                        required
                        class="mb-4"
                        @input="emitUpdate">
                        <template #prepend-inner>
                            <v-icon icon="mdi-currency-usd" color="grey-darken-1"></v-icon>
                        </template>
                    </v-text-field>
                </v-col>
                
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="formData.quantity"
                        :label="__('Stock Quantity')"
                        placeholder="0"
                        type="number"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errorMessages.quantity"
                        class="mb-4"
                        @input="emitUpdate">
                        <template #prepend-inner>
                            <v-icon icon="mdi-package-variant" color="grey-darken-1"></v-icon>
                        </template>
                    </v-text-field>
                </v-col>
                
                <v-col cols="12" md="6">
                    <v-select
                        v-model="formData.category_id"
                        :items="categories"
                        item-title="name"
                        item-value="id"
                        :label="__('Category')"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errorMessages.category_id"
                        class="mb-4"
                        clearable
                        @update:model-value="emitUpdate">
                        <template #prepend-inner>
                            <v-icon icon="mdi-tag" color="grey-darken-1"></v-icon>
                        </template>
                    </v-select>
                </v-col>
                
                <v-col cols="12" md="6">
                    <v-select
                        v-model="formData.status"
                        :items="statusOptions"
                        :label="__('Status')"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errorMessages.status"
                        class="mb-4"
                        @update:model-value="emitUpdate">
                        <template #prepend-inner>
                            <v-icon icon="mdi-check-circle" color="grey-darken-1"></v-icon>
                        </template>
                    </v-select>
                </v-col>
            </v-row>
        </div>

        <v-divider class="my-6"></v-divider>

        <!-- Additional Details Section -->
        <div class="form-section">
            <h4 class="section-title">
                <v-icon icon="mdi-cog" class="me-2" color="warning"></v-icon>
                {{ __("Additional Details") }}
            </h4>
            
            <v-row>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="formData.size"
                        :label="__('Size')"
                        :placeholder="__('e.g., Large, 500ml, XL')"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errorMessages.size"
                        class="mb-4"
                        @input="emitUpdate">
                        <template #prepend-inner>
                            <v-icon icon="mdi-resize" color="grey-darken-1"></v-icon>
                        </template>
                    </v-text-field>
                </v-col>
                
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="formData.type"
                        :label="__('Type')"
                        :placeholder="__('e.g., Premium, Standard')"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errorMessages.type"
                        class="mb-4"
                        @input="emitUpdate">
                        <template #prepend-inner>
                            <v-icon icon="mdi-shape" color="grey-darken-1"></v-icon>
                        </template>
                    </v-text-field>
                </v-col>
                
                <v-col cols="12" v-if="showImageUpload">
                    <v-file-input
                        v-model="formData.image"
                        :label="__('Product Image')"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errorMessages.image"
                        accept="image/*"
                        prepend-icon=""
                        class="mb-4"
                        @update:model-value="handleImageChange">
                        <template #prepend-inner>
                            <v-icon icon="mdi-camera" color="grey-darken-1"></v-icon>
                        </template>
                    </v-file-input>
                    
                    <!-- Image Preview -->
                    <div v-if="imagePreview" class="image-preview">
                        <v-img :src="imagePreview" max-height="200" contain class="rounded"></v-img>
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
                
                <v-col cols="12">
                    <v-textarea
                        v-model="formData.ingredients"
                        :label="__('Ingredients')"
                        :placeholder="__('List ingredients (optional)')"
                        variant="outlined"
                        density="comfortable"
                        rows="2"
                        :error-messages="errorMessages.ingredients"
                        class="mb-4"
                        @input="emitUpdate">
                        <template #prepend-inner>
                            <v-icon icon="mdi-food" color="grey-darken-1"></v-icon>
                        </template>
                    </v-textarea>
                </v-col>
            </v-row>
        </div>

        <!-- Custom Fields Slot -->
        <slot name="additional-fields" :form="formData" :errors="errorMessages"></slot>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'

const props = defineProps({
    modelValue: {
        type: Object,
        required: true
    },
    errorMessages: {
        type: Object,
        default: () => ({})
    },
    categories: {
        type: Array,
        default: () => []
    },
    product: {
        type: Object,
        default: null
    },
    showImageUpload: {
        type: Boolean,
        default: true
    },
    mode: {
        type: String,
        default: 'create', // 'create' or 'edit'
        validator: value => ['create', 'edit'].includes(value)
    }
})

const emit = defineEmits(['update:modelValue', 'imageChanged'])

// Component state
const formData = ref({ ...props.modelValue })
const imagePreview = ref(null)

// Static data
const statusOptions = computed(() => [
    { title: __('Active'), value: 'active' },
    { title: __('Inactive'), value: 'inactive' },
    { title: __('Draft'), value: 'draft' }
])

// Methods
const emitUpdate = () => {
    emit('update:modelValue', formData.value)
}

const handleImageChange = () => {
    if (formData.value.image) {
        const file = formData.value.image
        if (file instanceof File) {
            // Create preview URL for new file
            imagePreview.value = URL.createObjectURL(file)
            emit('imageChanged', { file, previewUrl: imagePreview.value })
        }
    } else {
        imagePreview.value = null
        emit('imageChanged', { file: null, previewUrl: null })
    }
    emitUpdate()
}

const clearImage = () => {
    if (imagePreview.value && formData.value.image instanceof File) {
        URL.revokeObjectURL(imagePreview.value)
    }
    formData.value.image = null
    imagePreview.value = null
    emit('imageChanged', { file: null, previewUrl: null })
    emitUpdate()
}

// Translation helper
const __ = (key) => {
    // Simple fallback - replace with your actual translation logic
    const translations = {
        'Basic Information': 'Basic Information',
        'Product Name': 'Product Name',
        'Enter a descriptive product name': 'Enter a descriptive product name',
        'Product Description': 'Product Description',
        'Describe your product in detail...': 'Describe your product in detail...',
        'Pricing & Details': 'Pricing & Details',
        'Price (USD)': 'Price (USD)',
        'Stock Quantity': 'Stock Quantity',
        'Category': 'Category',
        'Status': 'Status',
        'Additional Details': 'Additional Details',
        'Size': 'Size',
        'e.g., Large, 500ml, XL': 'e.g., Large, 500ml, XL',
        'Type': 'Type',
        'e.g., Premium, Standard': 'e.g., Premium, Standard',
        'Product Image': 'Product Image',
        'Ingredients': 'Ingredients',
        'List ingredients (optional)': 'List ingredients (optional)',
        'Active': 'Active',
        'Inactive': 'Inactive',
        'Draft': 'Draft'
    }
    return translations[key] || key
}

// Initialize image preview for existing product
onMounted(() => {
    if (props.product && props.product.imageUrl) {
        imagePreview.value = props.product.imageUrl
    }
})

// Watch for external changes to modelValue
watch(() => props.modelValue, (newValue) => {
    formData.value = { ...newValue }
}, { deep: true })

// Watch for changes in formData and sync back
watch(formData, () => {
    emitUpdate()
}, { deep: true })
</script>

<style scoped>
.product-form {
    width: 100%;
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

/* Form field spacing */
.mb-4 {
    margin-bottom: 16px !important;
}

/* Responsive design */
@media (max-width: 768px) {
    .section-title {
        font-size: 1rem;
    }
    
    .image-preview {
        max-width: 150px;
    }
}
</style>
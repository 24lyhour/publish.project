<template>
    <div class="product-form">
        <!-- Basic Information Section -->
        <div class="form-section">
            <h4 class="section-title">
                <v-icon icon="mdi-information" class="me-2" color="primary"></v-icon>
                Basic Information
            </h4>
            
            <v-text-field
                v-model="modelValue.name"
                label="Product Name *"
                placeholder="Enter a descriptive product name"
                variant="outlined"
                density="comfortable"
                :error-messages="errorMessages.name"
                counter="255"
                required
                class="mb-4"
                @input="updateForm">
                <template #prepend-inner>
                    <v-icon icon="mdi-package" color="grey-darken-1"></v-icon>
                </template>
            </v-text-field>

            <v-textarea
                v-model="modelValue.description"
                label="Product Description *"
                placeholder="Describe your product in detail..."
                variant="outlined"
                density="comfortable"
                rows="4"
                :error-messages="errorMessages.description"
                counter="1000"
                required
                class="mb-4"
                @input="updateForm">
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
                Pricing & Details
            </h4>
            
            <v-row>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="modelValue.price"
                        label="Price (USD) *"
                        placeholder="0.00"
                        type="number"
                        step="0.01"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errorMessages.price"
                        required
                        class="mb-4"
                        @input="updateForm">
                        <template #prepend-inner>
                            <v-icon icon="mdi-currency-usd" color="grey-darken-1"></v-icon>
                        </template>
                    </v-text-field>
                </v-col>
                
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="modelValue.quantity"
                        label="Stock Quantity"
                        placeholder="0"
                        type="number"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errorMessages.quantity"
                        class="mb-4"
                        @input="updateForm">
                        <template #prepend-inner>
                            <v-icon icon="mdi-package-variant" color="grey-darken-1"></v-icon>
                        </template>
                    </v-text-field>
                </v-col>
                
                <v-col cols="12" md="6">
                    <v-select
                        v-model="modelValue.category_id"
                        :items="categories"
                        item-title="name"
                        item-value="id"
                        label="Category"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errorMessages.category_id"
                        class="mb-4"
                        @update:model-value="updateForm">
                        <template #prepend-inner>
                            <v-icon icon="mdi-tag" color="grey-darken-1"></v-icon>
                        </template>
                    </v-select>
                </v-col>
                
                <v-col cols="12" md="6">
                    <v-select
                        v-model="modelValue.status"
                        :items="statusOptions"
                        label="Status"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errorMessages.status"
                        class="mb-4"
                        @update:model-value="updateForm">
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
                Additional Details
            </h4>
            
            <v-row>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="modelValue.size"
                        label="Size"
                        placeholder="e.g., Large, 500ml, XL"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errorMessages.size"
                        class="mb-4"
                        @input="updateForm">
                        <template #prepend-inner>
                            <v-icon icon="mdi-resize" color="grey-darken-1"></v-icon>
                        </template>
                    </v-text-field>
                </v-col>
                
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="modelValue.type"
                        label="Type"
                        placeholder="e.g., Premium, Standard"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errorMessages.type"
                        class="mb-4"
                        @input="updateForm">
                        <template #prepend-inner>
                            <v-icon icon="mdi-shape" color="grey-darken-1"></v-icon>
                        </template>
                    </v-text-field>
                </v-col>
                
                <v-col cols="12">
                    <v-file-input
                        v-model="modelValue.image"
                        label="Product Image"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errorMessages.image"
                        accept="image/*"
                        prepend-icon=""
                        class="mb-4"
                        @update:model-value="updateForm">
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
                
                <v-col cols="12">
                    <v-textarea
                        v-model="modelValue.ingredients"
                        label="Ingredients"
                        placeholder="List ingredients (optional)"
                        variant="outlined"
                        density="comfortable"
                        rows="2"
                        :error-messages="errorMessages.ingredients"
                        class="mb-4"
                        @input="updateForm">
                        <template #prepend-inner>
                            <v-icon icon="mdi-food" color="grey-darken-1"></v-icon>
                        </template>
                    </v-textarea>
                </v-col>
            </v-row>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

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
    }
})

const emit = defineEmits(['update:modelValue'])

// Component state
const previewUrl = ref(null)

// Static data
const statusOptions = [
    { title: 'Active', value: 'active' },
    { title: 'Inactive', value: 'inactive' }
]

// Methods
const updateForm = () => {
    emit('update:modelValue', props.modelValue)
}

const clearImage = () => {
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value)
        previewUrl.value = null
    }
    props.modelValue.image = null
    updateForm()
}

// Watch for image changes
watch(() => props.modelValue.image, (newFile) => {
    if (newFile && newFile instanceof File) {
        previewUrl.value = URL.createObjectURL(newFile)
    } else if (typeof newFile === 'string') {
        previewUrl.value = newFile
    } else {
        previewUrl.value = null
    }
})

// Initialize preview if editing existing product
if (props.product && props.product.imageUrl) {
    previewUrl.value = props.product.imageUrl
}
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
</style>
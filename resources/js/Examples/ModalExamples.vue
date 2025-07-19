<template>
    <div class="modal-examples">
        <h2>Reusable Modal Examples</h2>
        <p>Demonstration of how to use the sakal modal system with slots</p>
        
        <div class="example-buttons">
            <!-- Product Form Modal -->
            <v-btn color="primary" @click="showProductModal = true">
                <v-icon start>mdi-package</v-icon>
                Product Modal
            </v-btn>
            
            <!-- Category Form Modal -->
            <v-btn color="success" @click="showCategoryModal = true">
                <v-icon start>mdi-tag</v-icon>
                Category Modal
            </v-btn>
            
            <!-- Flexible Custom Modal -->
            <v-btn color="warning" @click="showFlexibleModal = true">
                <v-icon start>mdi-cog</v-icon>
                Custom Modal
            </v-btn>
            
            <!-- Simple Confirmation Modal -->
            <v-btn color="error" @click="showConfirmModal = true">
                <v-icon start>mdi-delete</v-icon>
                Confirm Modal
            </v-btn>
        </div>
        
        <!-- Product Form Modal -->
        <ProductFormModal
            :show="showProductModal"
            mode="create"
            :categories="[]"
            method="post"
            action="#"
            @close="showProductModal = false"
            @cancel="showProductModal = false"
            @success="handleSuccess('Product')"
            @error="handleError" />
        
        <!-- Category Form Modal -->
        <CategoryFormModal
            :show="showCategoryModal"
            mode="create"
            method="post"
            action="#"
            @close="showCategoryModal = false"
            @cancel="showCategoryModal = false"
            @success="handleSuccess('Category')"
            @error="handleError">
            
            <!-- Using the additional-fields slot -->
            <template #additional-fields="{ form, errors }">
                <v-col cols="12">
                    <v-text-field
                        v-model="form.custom_field"
                        label="Custom Field (via slot)"
                        placeholder="This field was added via slot"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errors.custom_field"
                        class="mb-4">
                        <template #prepend-inner>
                            <v-icon icon="mdi-star" color="warning"></v-icon>
                        </template>
                    </v-text-field>
                </v-col>
            </template>
        </CategoryFormModal>
        
        <!-- Flexible Custom Modal -->
        <FlexibleModal
            :show="showFlexibleModal"
            title="Custom Modal with Slots"
            subtitle="This modal uses slots for maximum flexibility"
            icon="mdi-cog"
            icon-color="warning"
            :max-width="500"
            confirm-text="Save Changes"
            confirm-color="warning"
            @close="showFlexibleModal = false"
            @cancel="showFlexibleModal = false"
            @confirm="handleCustomConfirm">
            
            <!-- Custom content slot -->
            <template #content>
                <div class="custom-content">
                    <v-text-field
                        v-model="customData.name"
                        label="Custom Name"
                        variant="outlined"
                        density="comfortable"
                        class="mb-4" />
                    
                    <v-select
                        v-model="customData.type"
                        :items="['Option 1', 'Option 2', 'Option 3']"
                        label="Custom Type"
                        variant="outlined"
                        density="comfortable"
                        class="mb-4" />
                    
                    <v-card variant="tonal" color="info" class="pa-4">
                        <v-card-text>
                            <v-icon icon="mdi-information" class="me-2"></v-icon>
                            This content is completely customizable using slots!
                        </v-card-text>
                    </v-card>
                </div>
            </template>
        </FlexibleModal>
        
        <!-- Simple Confirmation Modal -->
        <SakalModal
            :show="showConfirmModal"
            title="Confirm Action"
            subtitle="Are you sure you want to proceed?"
            icon="mdi-help-circle"
            icon-color="warning"
            type="warning"
            :max-width="400"
            @close="showConfirmModal = false">
            
            <template #default>
                <p>This action cannot be undone. Please confirm to proceed.</p>
            </template>
            
            <template #footer>
                <div class="confirm-footer">
                    <v-btn variant="outlined" @click="showConfirmModal = false">
                        Cancel
                    </v-btn>
                    <v-btn color="warning" @click="handleConfirm">
                        <v-icon start>mdi-check</v-icon>
                        Confirm
                    </v-btn>
                </div>
            </template>
        </SakalModal>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import ProductFormModal from '../Modals/ProductFormModal.vue'
import CategoryFormModal from '../Modals/CategoryFormModal.vue'
import FlexibleModal from '../Modals/FlexibleModal.vue'
import SakalModal from '../Modals/SakalModal.vue'

// Modal visibility state
const showProductModal = ref(false)
const showCategoryModal = ref(false)
const showFlexibleModal = ref(false)
const showConfirmModal = ref(false)

// Custom data for flexible modal
const customData = ref({
    name: '',
    type: null
})

// Event handlers
const handleSuccess = (type) => {
    console.log(`${type} operation successful`)
    // Close all modals
    showProductModal.value = false
    showCategoryModal.value = false
    showFlexibleModal.value = false
}

const handleError = (error) => {
    console.error('Operation failed:', error)
}

const handleCustomConfirm = () => {
    console.log('Custom confirm:', customData.value)
    showFlexibleModal.value = false
}

const handleConfirm = () => {
    console.log('Action confirmed')
    showConfirmModal.value = false
}
</script>

<style scoped>
.modal-examples {
    padding: 24px;
    max-width: 800px;
    margin: 0 auto;
}

.example-buttons {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin: 24px 0;
}

.custom-content {
    padding: 16px 0;
}

.confirm-footer {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    align-items: center;
}
</style>
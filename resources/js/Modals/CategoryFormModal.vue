<template>
    <SakalFormModal
        :show="show"
        :title="modalTitle"
        :subtitle="modalSubtitle"
        :icon="modalIcon"
        :icon-color="modalIconColor"
        :max-width="600"
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
            <v-row>
                <v-col cols="12">
                    <v-text-field
                        v-model="form.name"
                        label="Category Name *"
                        placeholder="Enter category name"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errors.name"
                        counter="100"
                        required
                        class="mb-4">
                        <template #prepend-inner>
                            <v-icon icon="mdi-tag" color="grey-darken-1"></v-icon>
                        </template>
                    </v-text-field>
                </v-col>
                
                <v-col cols="12">
                    <v-textarea
                        v-model="form.description"
                        label="Description"
                        placeholder="Brief description of this category"
                        variant="outlined"
                        density="comfortable"
                        rows="3"
                        :error-messages="errors.description"
                        counter="500"
                        class="mb-4">
                        <template #prepend-inner>
                            <v-icon icon="mdi-text" color="grey-darken-1"></v-icon>
                        </template>
                    </v-textarea>
                </v-col>
                
                <v-col cols="12" md="6">
                    <v-select
                        v-model="form.status"
                        :items="statusOptions"
                        label="Status"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errors.status"
                        class="mb-4">
                        <template #prepend-inner>
                            <v-icon icon="mdi-check-circle" color="grey-darken-1"></v-icon>
                        </template>
                    </v-select>
                </v-col>
                
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="form.sort_order"
                        label="Sort Order"
                        placeholder="0"
                        type="number"
                        variant="outlined"
                        density="comfortable"
                        :error-messages="errors.sort_order"
                        class="mb-4">
                        <template #prepend-inner>
                            <v-icon icon="mdi-sort-numeric-variant" color="grey-darken-1"></v-icon>
                        </template>
                    </v-text-field>
                </v-col>
            </v-row>
            
            <!-- Custom slot for additional fields -->
            <slot name="additional-fields" :form="form" :errors="errors" :processing="processing"></slot>
        </template>
    </SakalFormModal>
</template>

<script setup>
import { computed } from 'vue'
import SakalFormModal from './SakalFormModal.vue'

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
    category: {
        type: Object,
        default: null
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

// Computed properties
const modalTitle = computed(() => {
    return props.mode === 'create' ? 'Create New Category' : 'Edit Category'
})

const modalSubtitle = computed(() => {
    return props.mode === 'create' 
        ? 'Add a new category to organize your products'
        : `Update ${props.category?.name || 'category'}`
})

const modalIcon = computed(() => {
    return props.mode === 'create' ? 'mdi-plus-box' : 'mdi-pencil-box'
})

const modalIconColor = computed(() => 'primary')

const submitText = computed(() => {
    return props.mode === 'create' ? 'Create Category' : 'Update Category'
})

const submitIcon = computed(() => {
    return props.mode === 'create' ? 'mdi-check' : 'mdi-content-save'
})

const submitColor = computed(() => 'primary')

const formData = computed(() => {
    if (props.mode === 'edit' && props.category) {
        return {
            name: props.category.name || '',
            description: props.category.description || '',
            status: props.category.status || 'active',
            sort_order: props.category.sort_order || 0
        }
    }
    
    return {
        name: '',
        description: '',
        status: 'active',
        sort_order: 0
    }
})

// Static data
const statusOptions = [
    { title: 'Active', value: 'active' },
    { title: 'Inactive', value: 'inactive' }
]
</script>

<style scoped>
/* Custom styles for category modal if needed */
</style>
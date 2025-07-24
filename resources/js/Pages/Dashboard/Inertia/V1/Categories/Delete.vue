<template>
    <div class="category-delete-modal">
        <div class="modal-header">
            <div class="warning-icon">
                <v-icon icon="mdi-alert" size="48" color="error"></v-icon>
            </div>
            <h2 class="modal-title">Delete Category</h2>
            <p class="modal-subtitle">This action cannot be undone</p>
        </div>

        <div class="modal-content">
            <v-alert
                type="warning"
                variant="tonal"
                prominent
                class="warning-alert"
            >
                <template v-slot:title>
                    Are you sure you want to delete this category?
                </template>
                
                <div class="warning-details">
                    <p>You are about to permanently delete:</p>
                    <div class="category-info">
                        <div class="category-name">
                            <v-icon icon="mdi-tag" class="mr-2"></v-icon>
                            <strong>{{ category.displayName }}</strong>
                        </div>
                        <div class="category-meta" v-if="category.type">
                            Type: {{ category.type }}
                        </div>
                        <div class="category-meta" v-if="category.products_count > 0">
                            <v-icon icon="mdi-package-variant-closed" class="mr-1"></v-icon>
                            {{ category.products_count }} products are using this category
                        </div>
                    </div>
                </div>
            </v-alert>

            <v-alert
                v-if="category.products_count > 0"
                type="error"
                variant="tonal"
                prominent
                class="danger-alert"
            >
                <template v-slot:title>
                    Warning: Products will be affected
                </template>
                
                <p>
                    This category is currently used by <strong>{{ category.products_count }} products</strong>. 
                    Deleting this category may affect those products' organization.
                </p>
                
                <p class="mt-2">
                    <strong>Recommendation:</strong> Consider changing the category status to "Inactive" 
                    instead of deleting it permanently.
                </p>
            </v-alert>

            <div class="confirmation-section">
                <v-checkbox
                    v-model="confirmDelete"
                    label="I understand that this action cannot be undone"
                    color="error"
                    hide-details
                ></v-checkbox>
            </div>
        </div>

        <div class="modal-actions">
            <v-btn
                variant="outlined"
                size="large"
                @click="$inertia.get(route('dashboard.categories.index'))"
                :disabled="form.processing"
            >
                Cancel
            </v-btn>
            
            <v-btn
                color="warning"
                variant="tonal"
                size="large"
                @click="editInstead"
                :disabled="form.processing"
            >
                <v-icon start>mdi-pencil</v-icon>
                Edit Instead
            </v-btn>
            
            <v-btn
                color="error"
                variant="flat"
                size="large"
                @click="deleteCategory"
                :loading="form.processing"
                :disabled="form.processing || !confirmDelete"
            >
                <v-icon start>mdi-delete</v-icon>
                Delete Permanently
            </v-btn>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

// Props
const props = defineProps({
    category: Object
})

// Reactive data
const confirmDelete = ref(false)

// Form
const form = useForm({})

// Methods
const deleteCategory = () => {
    form.delete(route('dashboard.categories.destroy', props.category.id), {
        onSuccess: () => {
            // Will redirect automatically
        },
        onError: () => {
            // Errors are handled by the form
        }
    })
}

const editInstead = () => {
    router.get(route('dashboard.categories.edit', props.category.id))
}
</script>

<style scoped>
.category-delete-modal {
    max-width: 600px;
    margin: 0 auto;
}

.modal-header {
    text-align: center;
    margin-bottom: 32px;
}

.warning-icon {
    background: rgba(var(--v-theme-error), 0.1);
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px auto;
}

.modal-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.modal-subtitle {
    color: #666;
    margin: 0;
    font-size: 1rem;
}

.modal-content {
    margin-bottom: 32px;
}

.warning-alert,
.danger-alert {
    margin-bottom: 24px;
}

.warning-details {
    margin-top: 16px;
}

.category-info {
    background: rgba(255, 255, 255, 0.7);
    padding: 16px;
    border-radius: 8px;
    margin-top: 12px;
}

.category-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1a1a1a;
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

.category-meta {
    font-size: 0.875rem;
    color: #666;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
}

.confirmation-section {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 2px dashed #dee2e6;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 16px;
    padding-top: 24px;
    border-top: 1px solid #e0e0e0;
}

@media (max-width: 768px) {
    .modal-actions {
        flex-direction: column-reverse;
    }
}
</style>
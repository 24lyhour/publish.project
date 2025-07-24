<template>
    <div class="category-create-modal">
        <div class="modal-header">
            <h2 class="modal-title">
                <v-icon icon="mdi-tag-plus" class="mr-2"></v-icon>
                Create New Category
            </h2>
            <p class="modal-subtitle">Add a new category to organize your products</p>
        </div>

        <v-form @submit.prevent="submit" ref="formRef">
            <div class="form-content">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            v-model="form.name"
                            label="Category Name"
                            prepend-inner-icon="mdi-tag"
                            variant="outlined"
                            :error-messages="form.errors.name"
                            required
                            autofocus
                        ></v-text-field>
                    </v-col>

                    <v-col cols="12">
                        <v-textarea
                            v-model="form.description"
                            label="Description"
                            prepend-inner-icon="mdi-text"
                            variant="outlined"
                            :error-messages="form.errors.description"
                            rows="3"
                            placeholder="Describe this category..."
                        ></v-textarea>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.type"
                            label="Category Type"
                            prepend-inner-icon="mdi-shape"
                            variant="outlined"
                            :error-messages="form.errors.type"
                            placeholder="e.g., Food, Drinks, Desserts"
                        ></v-text-field>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.price_sale"
                            label="Sale Price"
                            prepend-inner-icon="mdi-currency-usd"
                            variant="outlined"
                            :error-messages="form.errors.price_sale"
                            type="number"
                            step="0.01"
                            min="0"
                            placeholder="0.00"
                        ></v-text-field>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-select
                            v-model="form.status"
                            label="Status"
                            prepend-inner-icon="mdi-check-circle"
                            variant="outlined"
                            :items="statusOptions"
                            :error-messages="form.errors.status"
                        ></v-select>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.imageUrl"
                            label="Image URL"
                            prepend-inner-icon="mdi-image"
                            variant="outlined"
                            :error-messages="form.errors.imageUrl"
                            placeholder="https://example.com/image.jpg"
                        ></v-text-field>
                    </v-col>
                </v-row>
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
                    type="submit"
                    color="primary"
                    variant="flat"
                    size="large"
                    :loading="form.processing"
                    :disabled="form.processing"
                >
                    <v-icon start>mdi-content-save</v-icon>
                    Create Category
                </v-btn>
            </div>
        </v-form>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

// Form reference
const formRef = ref(null)

// Form data
const form = useForm({
    name: '',
    description: '',
    type: '',
    price_sale: '',
    status: 'active',
    imageUrl: ''
})

// Status options
const statusOptions = [
    { title: 'Active', value: 'active' },
    { title: 'Inactive', value: 'inactive' },
    { title: 'Draft', value: 'draft' }
]

// Methods
const submit = () => {
    form.post(route('dashboard.categories.store'), {
        onSuccess: () => {
            // Form will redirect automatically
        },
        onError: () => {
            // Errors are handled by the form
        }
    })
}
</script>

<style scoped>
.category-create-modal {
    max-width: 800px;
    margin: 0 auto;
}

.modal-header {
    text-align: center;
    margin-bottom: 32px;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-subtitle {
    color: #666;
    margin: 0;
}

.form-content {
    margin-bottom: 32px;
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
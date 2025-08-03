<template>
    <div class="category-form">
        <v-row>
            <v-col cols="12">
                <v-text-field
                    v-model="localForm.name"
                    label="Category Name"
                    prepend-inner-icon="mdi-tag"
                    variant="outlined"
                    :error-messages="errorMessages.name"
                    required
                    autofocus
                    @input="updateForm"
                ></v-text-field>
            </v-col>

            <v-col cols="12">
                <v-textarea
                    v-model="localForm.description"
                    label="Description"
                    prepend-inner-icon="mdi-text"
                    variant="outlined"
                    :error-messages="errorMessages.description"
                    rows="3"
                    placeholder="Describe this category..."
                    @input="updateForm"
                ></v-textarea>
            </v-col>

            <v-col cols="12" md="6">
                <v-text-field
                    v-model="localForm.type"
                    label="Category Type"
                    prepend-inner-icon="mdi-shape"
                    variant="outlined"
                    :error-messages="errorMessages.type"
                    placeholder="e.g., Food, Drinks, Desserts"
                    @input="updateForm"
                ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
                <v-select
                    v-model="localForm.menu_id"
                    label="Menu"
                    prepend-inner-icon="mdi-menu"
                    variant="outlined"
                    :items="menuOptions"
                    :error-messages="errorMessages.menu_id"
                    clearable
                    @update:model-value="updateForm"
                ></v-select>
            </v-col>

            <v-col cols="12" md="6">
                <v-text-field
                    v-model="localForm.price_sale"
                    label="Sale Price"
                    prepend-inner-icon="mdi-currency-usd"
                    variant="outlined"
                    :error-messages="errorMessages.price_sale"
                    type="number"
                    step="0.01"
                    min="0"
                    placeholder="0.00"
                    @input="updateForm"
                ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
                <v-select
                    v-model="localForm.status"
                    label="Status"
                    prepend-inner-icon="mdi-check-circle"
                    variant="outlined"
                    :items="statusOptions"
                    :error-messages="errorMessages.status"
                    @update:model-value="updateForm"
                ></v-select>
            </v-col>

            <v-col cols="12">
                <v-text-field
                    v-model="localForm.imageUrl"
                    label="Image URL"
                    prepend-inner-icon="mdi-image"
                    variant="outlined"
                    :error-messages="errorMessages.imageUrl"
                    placeholder="https://example.com/image.jpg"
                    @input="updateForm"
                ></v-text-field>
            </v-col>
        </v-row>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
    modelValue: {
        type: Object,
        required: true
    },
    menus: {
        type: Array,
        default: () => []
    },
    errorMessages: {
        type: Object,
        default: () => ({})
    }
})

const emit = defineEmits(['update:modelValue'])

// Local form state
const localForm = ref({ ...props.modelValue })

// Status options
const statusOptions = [
    { title: 'Active', value: 'active' },
    { title: 'Inactive', value: 'inactive' },
    { title: 'Draft', value: 'draft' }
]

// Menu options
const menuOptions = computed(() => {
    return props.menus.map(menu => ({
        title: menu.menu_name,
        value: menu.id
    }))
})

// Update form method
const updateForm = () => {
    emit('update:modelValue', localForm.value)
}

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
    localForm.value = { ...newValue }
}, { deep: true })
</script>

<style scoped>
.category-form {
    width: 100%;
}
</style>
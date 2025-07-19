<template>
    <div class="product-detail-fields">
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
                    required
                    class="mb-4">
                    <template #prepend-inner>
                        <v-icon icon="mdi-currency-usd" color="grey-darken-1"></v-icon>
                    </template>
                </v-text-field>
            </v-col>
            
            <v-col cols="12" md="6">
                <v-text-field
                    v-model="form.quantity"
                    label="Stock Quantity"
                    placeholder="0"
                    type="number"
                    variant="outlined"
                    density="comfortable"
                    :error-messages="errors.quantity"
                    class="mb-4">
                    <template #prepend-inner>
                        <v-icon icon="mdi-package-variant" color="grey-darken-1"></v-icon>
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
                    :error-messages="errors.category_id"
                    class="mb-4">
                    <template #prepend-inner>
                        <v-icon icon="mdi-tag" color="grey-darken-1"></v-icon>
                    </template>
                </v-select>
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
        </v-row>
    </div>
</template>

<script setup>
defineProps({
    form: {
        type: Object,
        required: true
    },
    errors: {
        type: Object,
        default: () => ({})
    },
    categories: {
        type: Array,
        default: () => []
    }
})

const statusOptions = [
    { title: 'Active', value: 'active' },
    { title: 'Inactive', value: 'inactive' }
]
</script>

<style scoped>
.product-detail-fields {
    width: 100%;
}
</style>
<template>
    <div class="product-basic-fields">
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

        <v-textarea
            v-model="form.description"
            label="Product Description *"
            placeholder="Describe your product in detail..."
            variant="outlined"
            density="comfortable"
            rows="4"
            :error-messages="errors.description"
            counter="1000"
            required
            class="mb-4">
            <template #prepend-inner>
                <v-icon icon="mdi-text" color="grey-darken-1"></v-icon>
            </template>
        </v-textarea>

        <v-row v-if="showCategory">
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
    },
    showCategory: {
        type: Boolean,
        default: false
    }
})
</script>

<style scoped>
.product-basic-fields {
    width: 100%;
}
</style>
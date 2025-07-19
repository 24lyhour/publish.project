<template>
    <div class="product-media-fields">
        <v-row>
            <v-col v-if="showImage" cols="12">
                <v-file-input
                    v-model="form.image"
                    label="Product Image"
                    variant="outlined"
                    density="comfortable"
                    :error-messages="errors.image"
                    accept="image/*"
                    prepend-icon=""
                    class="mb-4">
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
                        @click="$emit('clearImage')">
                    </v-btn>
                </div>
            </v-col>
            
            <v-col cols="12" md="6">
                <v-text-field
                    v-model="form.size"
                    label="Size"
                    placeholder="e.g., Large, 500ml, XL"
                    variant="outlined"
                    density="comfortable"
                    :error-messages="errors.size"
                    class="mb-4">
                    <template #prepend-inner>
                        <v-icon icon="mdi-resize" color="grey-darken-1"></v-icon>
                    </template>
                </v-text-field>
            </v-col>
            
            <v-col cols="12" md="6">
                <v-text-field
                    v-model="form.type"
                    label="Type"
                    placeholder="e.g., Premium, Standard"
                    variant="outlined"
                    density="comfortable"
                    :error-messages="errors.type"
                    class="mb-4">
                    <template #prepend-inner>
                        <v-icon icon="mdi-shape" color="grey-darken-1"></v-icon>
                    </template>
                </v-text-field>
            </v-col>
            
            <v-col cols="12">
                <v-textarea
                    v-model="form.ingredients"
                    label="Ingredients"
                    placeholder="List ingredients (optional)"
                    variant="outlined"
                    density="comfortable"
                    rows="2"
                    :error-messages="errors.ingredients"
                    class="mb-4">
                    <template #prepend-inner>
                        <v-icon icon="mdi-food" color="grey-darken-1"></v-icon>
                    </template>
                </v-textarea>
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
    previewUrl: {
        type: String,
        default: null
    },
    showImage: {
        type: Boolean,
        default: true
    }
})

defineEmits(['clearImage'])
</script>

<style scoped>
.product-media-fields {
    width: 100%;
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
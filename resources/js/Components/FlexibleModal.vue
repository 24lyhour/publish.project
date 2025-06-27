<template>
    <v-dialog :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)" max-width="600px"
        persistent>
        <v-card class="p-2">
            <v-card-title class="text-h5 font-bold flex justify-between items-center">
                <span>{{ title }}</span>
                <v-btn icon="mdi-close" variant="text" @click="$emit('update:modelValue', false)"></v-btn>
            </v-card-title>

            <v-card-text>
                <!-- Default slot for the main content -->
                <slot></slot>
            </v-card-text>

            <v-card-actions class="mt-4">
                <v-spacer></v-spacer>
                <!-- Named slot for action buttons -->
                <slot name="actions">
                    <v-btn color="primary" variant="text" @click="$emit('update:modelValue', false)">
                        Close
                    </v-btn>
                </slot>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    title: {
        type: String,
        default: 'Details',
    },
});

defineEmits(['update:modelValue']);
</script>
<template>
    <SakalModal
        :show="show"
        :title="title"
        :subtitle="subtitle"
        :icon="icon"
        :icon-color="iconColor"
        :max-width="maxWidth"
        :type="type"
        @close="$emit('close')">
        
        <!-- Header slot for custom header content -->
        <template #header>
            <slot name="header"></slot>
        </template>
        
        <!-- Main content slot -->
        <template #default>
            <slot name="content"></slot>
        </template>
        
        <!-- Footer slot for custom actions -->
        <template #footer>
            <slot name="footer">
                <!-- Default footer with cancel/confirm buttons -->
                <div class="default-footer">
                    <v-btn 
                        variant="outlined" 
                        @click="$emit('cancel')"
                        :disabled="processing">
                        Cancel
                    </v-btn>
                    <v-btn 
                        :color="confirmColor" 
                        @click="$emit('confirm')"
                        :loading="processing"
                        :disabled="!canConfirm">
                        {{ confirmText }}
                    </v-btn>
                </div>
            </slot>
        </template>
    </SakalModal>
</template>

<script setup>
import SakalModal from '../UI/Base/Modal.vue'

defineProps({
    show: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: 'Modal Title'
    },
    subtitle: {
        type: String,
        default: null
    },
    icon: {
        type: String,
        default: null
    },
    iconColor: {
        type: String,
        default: 'primary'
    },
    maxWidth: {
        type: [String, Number],
        default: 600
    },
    type: {
        type: String,
        default: 'default'
    },
    confirmText: {
        type: String,
        default: 'Confirm'
    },
    confirmColor: {
        type: String,
        default: 'primary'
    },
    canConfirm: {
        type: Boolean,
        default: true
    },
    processing: {
        type: Boolean,
        default: false
    }
})

defineEmits(['close', 'cancel', 'confirm'])
</script>

<style scoped>
.default-footer {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    align-items: center;
}
</style>
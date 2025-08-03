<template>
    <SakalModal
        :show="show"
        :max-width="maxWidthClass"
        :closeable="!processing"
        @close="$emit('close')">
        
        <!-- Modal Header -->
        <div v-if="title || subtitle || icon" class="modal-header">
            <div class="header-content">
                <div v-if="icon" class="header-icon" :class="`text-${iconColor}`">
                    <v-icon :icon="icon" size="32"></v-icon>
                </div>
                <div class="header-text">
                    <h3 v-if="title" class="modal-title">{{ title }}</h3>
                    <p v-if="subtitle" class="modal-subtitle">{{ subtitle }}</p>
                </div>
            </div>
            <!-- Header slot for custom header content -->
            <slot name="header"></slot>
        </div>
        
        <!-- Modal Body -->
        <div class="modal-body">
            <!-- Main content slot -->
            <slot name="content"></slot>
        </div>
        
        <!-- Modal Footer -->
        <div class="modal-footer">
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
        </div>
    </SakalModal>
</template>

<script setup>
import { computed } from 'vue'
import SakalModal from '../../../UI/Base/Modal.vue'

const props = defineProps({
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

// Convert maxWidth prop to base modal format
const maxWidthClass = computed(() => {
    if (typeof props.maxWidth === 'number') {
        if (props.maxWidth <= 400) return 'sm'
        if (props.maxWidth <= 600) return 'md'
        if (props.maxWidth <= 800) return 'lg'
        if (props.maxWidth <= 1000) return 'xl'
        return '2xl'
    }
    return props.maxWidth
})
</script>

<style scoped>
.modal-header {
    padding: 24px 24px 0 24px;
    border-bottom: 1px solid #e0e0e0;
    margin-bottom: 0;
}

.header-content {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 20px;
}

.header-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background-color: rgba(var(--v-theme-primary), 0.1);
}

.header-text {
    flex: 1;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 8px 0;
    line-height: 1.2;
}

.modal-subtitle {
    font-size: 0.95rem;
    color: #6b7280;
    margin: 0;
    line-height: 1.4;
}

.modal-body {
    padding: 24px;
    max-height: 60vh;
    overflow-y: auto;
}

.modal-footer {
    padding: 0 24px 24px 24px;
    border-top: 1px solid #e0e0e0;
    background-color: #fafafa;
}

.default-footer {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    align-items: center;
    padding-top: 20px;
}
</style>
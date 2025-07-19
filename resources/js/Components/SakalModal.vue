<template>
    <v-dialog 
        :model-value="show" 
        @update:model-value="handleClose"
        :max-width="maxWidth"
        :persistent="persistent"
        :scrollable="scrollable"
        class="sakal-modal">
        
        <v-card class="modal-card" rounded="16">
            <!-- Modal Header -->
            <v-card-title class="modal-header" :class="headerClass">
                <div class="header-content">
                    <div class="header-info">
                        <v-icon v-if="icon" :icon="icon" :color="iconColor" size="24" class="me-3"></v-icon>
                        <div>
                            <h3 class="modal-title">{{ title }}</h3>
                            <p v-if="subtitle" class="modal-subtitle">{{ subtitle }}</p>
                        </div>
                    </div>
                    <v-btn 
                        v-if="closable"
                        icon="mdi-close" 
                        variant="text" 
                        size="small" 
                        @click="handleClose"
                        class="close-btn">
                    </v-btn>
                </div>
            </v-card-title>

            <!-- Modal Content -->
            <v-card-text class="modal-content" :class="contentClass">
                <slot></slot>
            </v-card-text>

            <!-- Modal Footer -->
            <v-card-actions v-if="$slots.actions || showDefaultActions" class="modal-footer" :class="footerClass">
                <slot name="actions">
                    <v-spacer></v-spacer>
                    <v-btn 
                        v-if="showCancel"
                        variant="outlined" 
                        @click="handleCancel"
                        :disabled="loading">
                        {{ cancelText }}
                    </v-btn>
                    <v-btn 
                        v-if="showConfirm"
                        :color="confirmColor" 
                        variant="elevated" 
                        @click="handleConfirm"
                        :loading="loading">
                        {{ confirmText }}
                    </v-btn>
                </slot>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        required: true
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
    persistent: {
        type: Boolean,
        default: false
    },
    scrollable: {
        type: Boolean,
        default: true
    },
    closable: {
        type: Boolean,
        default: true
    },
    loading: {
        type: Boolean,
        default: false
    },
    type: {
        type: String,
        default: 'default', // default, success, warning, error, info
        validator: value => ['default', 'success', 'warning', 'error', 'info'].includes(value)
    },
    showDefaultActions: {
        type: Boolean,
        default: false
    },
    showCancel: {
        type: Boolean,
        default: true
    },
    showConfirm: {
        type: Boolean,
        default: true
    },
    cancelText: {
        type: String,
        default: 'Cancel'
    },
    confirmText: {
        type: String,
        default: 'Confirm'
    },
    confirmColor: {
        type: String,
        default: 'primary'
    }
})

const emit = defineEmits(['close', 'cancel', 'confirm'])

// Computed classes based on modal type
const headerClass = computed(() => {
    const classes = {
        'success': 'header-success',
        'warning': 'header-warning', 
        'error': 'header-error',
        'info': 'header-info',
        'default': 'header-default'
    }
    return classes[props.type] || classes.default
})

const contentClass = computed(() => {
    return props.type !== 'default' ? `content-${props.type}` : ''
})

const footerClass = computed(() => {
    return props.type !== 'default' ? `footer-${props.type}` : ''
})

// Event handlers
const handleClose = () => {
    if (!props.persistent) {
        emit('close')
    }
}

const handleCancel = () => {
    emit('cancel')
}

const handleConfirm = () => {
    emit('confirm')
}
</script>

<style scoped>
.sakal-modal :deep(.v-overlay__content) {
    margin: 24px;
}

.modal-card {
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Header Styles */
.modal-header {
    padding: 24px 32px;
    border-bottom: 1px solid #e2e8f0;
    background: #f8f9fa;
}

.header-content {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    width: 100%;
}

.header-info {
    display: flex;
    align-items: flex-start;
    flex: 1;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
    line-height: 1.2;
}

.modal-subtitle {
    font-size: 0.875rem;
    color: #718096;
    margin: 4px 0 0 0;
    line-height: 1.4;
}

.close-btn {
    margin-top: -4px;
}

/* Header Type Variants */
.header-success {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
    border-bottom-color: rgba(255, 255, 255, 0.2);
}

.header-success .modal-title,
.header-success .modal-subtitle {
    color: white;
}

.header-warning {
    background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
    color: white;
    border-bottom-color: rgba(255, 255, 255, 0.2);
}

.header-warning .modal-title,
.header-warning .modal-subtitle {
    color: white;
}

.header-error {
    background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
    color: white;
    border-bottom-color: rgba(255, 255, 255, 0.2);
}

.header-error .modal-title,
.header-error .modal-subtitle {
    color: white;
}

.header-info {
    background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
    color: white;
    border-bottom-color: rgba(255, 255, 255, 0.2);
}

.header-info .modal-title,
.header-info .modal-subtitle {
    color: white;
}

/* Content Styles */
.modal-content {
    padding: 32px;
    min-height: 120px;
}

.content-success {
    background: rgba(72, 187, 120, 0.02);
}

.content-warning {
    background: rgba(237, 137, 54, 0.02);
}

.content-error {
    background: rgba(245, 101, 101, 0.02);
}

.content-info {
    background: rgba(66, 153, 225, 0.02);
}

/* Footer Styles */
.modal-footer {
    padding: 20px 32px;
    border-top: 1px solid #e2e8f0;
    background: #f8f9fa;
}

.footer-success {
    background: rgba(72, 187, 120, 0.05);
    border-top-color: rgba(72, 187, 120, 0.2);
}

.footer-warning {
    background: rgba(237, 137, 54, 0.05);
    border-top-color: rgba(237, 137, 54, 0.2);
}

.footer-error {
    background: rgba(245, 101, 101, 0.05);
    border-top-color: rgba(245, 101, 101, 0.2);
}

.footer-info {
    background: rgba(66, 153, 225, 0.05);
    border-top-color: rgba(66, 153, 225, 0.2);
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal-header {
        padding: 20px 24px;
    }
    
    .modal-content {
        padding: 24px;
    }
    
    .modal-footer {
        padding: 16px 24px;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .close-btn {
        align-self: flex-end;
        margin-top: 0;
        margin-bottom: 12px;
    }
}

/* Animation */
.modal-card {
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Enhanced button styling */
.modal-footer .v-btn {
    border-radius: 8px !important;
    font-weight: 600;
    text-transform: none;
    min-width: 100px;
}
</style>
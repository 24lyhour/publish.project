<template>
    <SakalModal
        :show="show"
        :title="title"
        :subtitle="subtitle"
        :icon="icon"
        :icon-color="iconColor"
        :max-width="modalWidth"
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
                    <Button 
                        variant="outline" 
                        @click="$emit('cancel')"
                        :disabled="processing">
                        Cancel
                    </Button>
                    <Button 
                        @click="$emit('confirm')"
                        :disabled="!canConfirm || processing"
                        :class="{ 'opacity-50': processing }">
                        {{ processing ? 'Processing...' : confirmText }}
                    </Button>
                </div>
            </slot>
        </template>
    </SakalModal>
</template>

<script setup>
import { computed } from 'vue'
import SakalModal from '../UI/Base/Modal.vue'
import { Button } from '@/Components/ui/button'

defineEmits(['close', 'cancel', 'confirm'])

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
    size: {
        type: String,
        default: 'sm',
        validator: value => ['sm', 'lg', 'xl', '2xl'].includes(value)
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

const modalWidth = computed(() => {
    const sizeMap = {
        sm: 600,
        lg: 800,
        xl: 1000,
        '2xl': 1200
    }
    return sizeMap[props.size] || props.maxWidth
})
</script>

<style scoped>
.default-footer {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    align-items: center;
}
</style>
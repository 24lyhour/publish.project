<template>
    <SakalModal
        :show="show"
        :title="title"
        :subtitle="subtitle"
        :icon="icon"
        :icon-color="iconColor"
        :max-width="modalWidth"
        :persistent="processing"
        :closable="!processing"
        :loading="processing"
        :type="type"
        @close="handleClose"
        @cancel="handleCancel"
        @confirm="handleSubmit">
        
        <template #default>
            <v-form @submit.prevent="handleSubmit" ref="formRef">
                <slot 
                    :form="form" 
                    :errors="errors" 
                    :processing="processing"
                    :submit="handleSubmit"
                    :reset="resetForm"
                    :close="handleClose">
                </slot>
            </v-form>
        </template>

        <template #actions>
            <slot name="actions" 
                  :form="form" 
                  :processing="processing" 
                  :submit="handleSubmit" 
                  :close="handleClose">
                <div class="flex-1"></div>
                <Button 
                    variant="outline" 
                    @click="handleCancel"
                    :disabled="processing"
                    size="lg">
                    {{ cancelText }}
                </Button>
                <Button 
                    @click="handleSubmit"
                    :disabled="processing"
                    size="lg"
                    class="sakal-submit-btn">
                    {{ processing ? 'Processing...' : submitText }}
                </Button>
            </slot>
        </template>
    </SakalModal>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import SakalModal from '../Shared/Modals/FormSakal/FlexibleModal.vue'
import { Button } from '@/Components/ui/button'

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
        default: 700
    },
    size: {
        type: String,
        default: 'lg',
        validator: value => ['sm', 'lg', 'xl', '2xl'].includes(value)
    },
    type: {
        type: String,
        default: 'default'
    },
    // Form props
    method: {
        type: String,
        default: 'post',
        validator: value => ['get', 'post', 'put', 'patch', 'delete'].includes(value)
    },
    action: {
        type: String,
        default: null
    },
    formData: {
        type: Object,
        default: () => ({})
    },
    cancelText: {
        type: String,
        default: 'Cancel'
    },
    submitText: {
        type: String,
        default: 'Save'
    },
    submitIcon: {
        type: String,
        default: null
    },
    submitColor: {
        type: String,
        default: 'primary'
    },
    resetOnSuccess: {
        type: Boolean,
        default: true
    },
    closeOnSuccess: {
        type: Boolean,
        default: true
    },
    autoSubmit: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits(['close', 'cancel', 'success', 'error', 'submit'])

// Form setup
const formRef = ref(null)
const form = useForm(props.formData)

// Watch for formData changes
watch(() => props.formData, (newData) => {
    Object.keys(newData).forEach(key => {
        form[key] = newData[key]
    })
}, { deep: true, immediate: true })

// Initialize form data on mount
onMounted(() => {
    if (Object.keys(props.formData).length > 0) {
        Object.keys(props.formData).forEach(key => {
            form[key] = props.formData[key]
        })
    }
})

// Computed properties
const processing = computed(() => form.processing)
const errors = computed(() => form.errors)
const modalWidth = computed(() => {
    const sizeMap = {
        sm: 600,
        lg: 800,
        xl: 1000,
        '2xl': 1200
    }
    return sizeMap[props.size] || props.maxWidth
})

// Methods
const resetForm = () => {
    form.reset()
    form.clearErrors()
}

// Event handlers
const handleClose = () => {
    if (!processing.value) {
        emit('close')
    }
}

const handleCancel = () => {
    if (!processing.value) {
        emit('cancel')
    }
}

const handleSubmit = async () => {
    if (processing.value) return

    // Validate form if validation is available
    if (formRef.value?.validate) {
        const { valid } = await formRef.value.validate()
        if (!valid) return
    }

    // Emit submit event for manual handling
    emit('submit', form)

    // Auto submit if enabled and action is provided
    if (props.autoSubmit && props.action) {
        const submitMethod = form[props.method.toLowerCase()]
        if (typeof submitMethod === 'function') {
            submitMethod(props.action, {
                preserveScroll: true,
                onSuccess: (response) => {
                    emit('success', response)
                    if (props.closeOnSuccess) {
                        handleClose()
                    }
                    if (props.resetOnSuccess) {
                        resetForm()
                    }
                },
                onError: (errors) => {
                    emit('error', errors)
                }
            })
        }
    }
}

// Expose form methods
defineExpose({
    form,
    submit: handleSubmit,
    reset: resetForm,
    close: handleClose
})
</script>

<style scoped>
.sakal-submit-btn {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    border-radius: 8px !important;
    text-transform: none !important;
    font-weight: 600;
}

.sakal-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
}
</style>
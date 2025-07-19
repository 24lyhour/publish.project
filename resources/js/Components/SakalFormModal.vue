<template>
    <SakalModal
        :show="show"
        :title="title"
        :subtitle="subtitle"
        :icon="icon"
        :icon-color="iconColor"
        :max-width="maxWidth"
        :persistent="processing"
        :closable="!processing"
        :loading="processing"
        :type="type"
        @close="handleClose"
        @cancel="handleCancel"
        @confirm="handleSubmit">
        
        <template #default>
            <v-form @submit.prevent="handleSubmit" ref="formRef">
                <slot :form="form" :errors="errors" :processing="processing"></slot>
            </v-form>
        </template>

        <template #actions>
            <v-spacer></v-spacer>
            <v-btn 
                variant="outlined" 
                @click="handleCancel"
                :disabled="processing"
                size="large">
                {{ cancelText }}
            </v-btn>
            <v-btn 
                :color="submitColor" 
                variant="elevated" 
                @click="handleSubmit"
                :loading="processing"
                size="large"
                class="submit-btn">
                <v-icon v-if="submitIcon" start>{{ submitIcon }}</v-icon>
                {{ submitText }}
            </v-btn>
        </template>
    </SakalModal>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import SakalModal from './SakalModal.vue'

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
        required: true
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
    }
})

const emit = defineEmits(['close', 'cancel', 'success', 'error'])

// Form setup
const formRef = ref(null)
const form = useForm(props.formData)

// Watch for formData changes
watch(() => props.formData, (newData) => {
    Object.keys(newData).forEach(key => {
        form[key] = newData[key]
    })
}, { deep: true, immediate: true })

// Computed properties
const processing = computed(() => form.processing)
const errors = computed(() => form.errors)

// Event handlers
const handleClose = () => {
    if (!processing.value) {
        form.reset()
        form.clearErrors()
        emit('close')
    }
}

const handleCancel = () => {
    if (!processing.value) {
        form.reset()
        form.clearErrors()
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

    // Submit form
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
                    form.reset()
                }
            },
            onError: (errors) => {
                emit('error', errors)
            }
        })
    }
}

// Expose form methods
defineExpose({
    form,
    submit: handleSubmit,
    reset: () => {
        form.reset()
        form.clearErrors()
    },
    close: handleClose
})
</script>

<style scoped>
.submit-btn {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    border-radius: 8px !important;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
}
</style>
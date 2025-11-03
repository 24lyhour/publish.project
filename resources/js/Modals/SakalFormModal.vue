<template>
  <Dialog :open="show" @update:open="handleClose">
    <DialogContent 
      :class="cn(
        'fixed right-0 top-0 h-full w-full max-w-md p-0 gap-0 overflow-hidden border-l bg-white shadow-lg transform transition-transform duration-300 ease-in-out translate-x-0',
        sizeClass
      )"
      @escape-key-down="handleClose"
      @pointer-down-outside="!processing ? handleClose : undefined">
      
      <!-- Header -->
      <DialogHeader class="px-6 py-4 bg-gradient-to-r from-slate-50 to-slate-100 border-b">
        <div class="flex items-center gap-3">
          <div v-if="icon" :class="cn('p-2 rounded-lg', iconColorClass)">
            <component :is="iconComponent" class="w-5 h-5 text-white" />
          </div>
          <div class="flex-1">
            <DialogTitle class="text-lg font-semibold text-gray-900">
              {{ title }}
            </DialogTitle>
            <DialogDescription v-if="subtitle" class="text-sm text-gray-500 mt-1">
              {{ subtitle }}
            </DialogDescription>
          </div>
        </div>
      </DialogHeader>

      <!-- Content -->
      <div class="px-6 py-4 max-h-[calc(100vh-200px)] overflow-y-auto">
        <form @submit.prevent="handleSubmit" ref="formRef">
          <slot 
            :form="form" 
            :errors="errors" 
            :processing="processing"
            :submit="handleSubmit"
            :reset="resetForm"
            :close="handleClose">
          </slot>
        </form>
      </div>

      <!-- Footer -->
      <DialogFooter class="px-6 py-4 bg-gray-50 border-t">
        <slot name="actions" 
              :form="form" 
              :processing="processing" 
              :submit="handleSubmit" 
              :close="handleClose">
          <div class="flex justify-end gap-3">
            <Button 
              type="button"
              variant="outline" 
              @click="handleCancel"
              :disabled="processing"
              class="px-4 py-2">
              {{ cancelText }}
            </Button>
            <Button 
              type="button"
              @click="handleSubmit"
              :disabled="processing"
              class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white">
              <Loader2 v-if="processing" class="w-4 h-4 mr-2 animate-spin" />
              {{ processing ? 'Processing...' : submitText }}
            </Button>
          </div>
        </slot>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { cn } from '@/lib/utils'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/Components/ui/dialog'
import { Button } from '@/Components/ui/button'
import { Loader2, Package, Plus, Edit, Trash, Eye } from 'lucide-vue-next'

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
    default: 'blue'
  },
  size: {
    type: String,
    default: 'lg',
    validator: value => ['sm', 'lg', 'xl', '2xl'].includes(value)
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
    default: false
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

const sizeClass = computed(() => {
  const sizeMap = {
    sm: 'max-w-sm',
    lg: 'max-w-lg',
    xl: 'max-w-2xl',
    '2xl': 'max-w-4xl'
  }
  return sizeMap[props.size] || 'max-w-lg'
})

const iconColorClass = computed(() => {
  const colorMap = {
    blue: 'bg-blue-500',
    green: 'bg-green-500',
    red: 'bg-red-500',
    yellow: 'bg-yellow-500',
    purple: 'bg-purple-500',
    indigo: 'bg-indigo-500',
    primary: 'bg-blue-500'
  }
  return colorMap[props.iconColor] || 'bg-blue-500'
})

const iconComponent = computed(() => {
  const iconMap = {
    'mdi-package-variant-closed-plus': Plus,
    'mdi-package-variant-closed': Package,
    'mdi-pencil': Edit,
    'mdi-delete': Trash,
    'mdi-eye': Eye,
    'package-plus': Plus,
    'package': Package,
    'edit': Edit,
    'trash': Trash,
    'eye': Eye
  }
  return iconMap[props.icon] || Package
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
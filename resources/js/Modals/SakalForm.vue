<template>
  <!-- Backdrop - Very light with blur effect -->
  <div v-if="show" class="fixed inset-0 z-40 bg-white bg-opacity-10 backdrop-blur-sm transition-all duration-300"
    @click="handleClose">
  </div>

  <!-- Modal Panel -->
  <div v-if="show"
    class="fixed right-0 top-0 z-50 h-full w-full bg-white shadow-xl transform transition-transform duration-300 ease-in-out"
    :class="[
      show ? 'translate-x-0' : 'translate-x-full',
      sizeClass
    ]">

    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-4 border-b bg-gradient-to-r from-blue-50 to-blue-100">
      <div class="flex items-center gap-3">
        <div v-if="icon" :class="['p-2 rounded-lg', iconColorClass]">
          <component :is="iconComponent" class="w-5 h-5 text-white" />
        </div>
        <div>
          <h2 class="text-lg font-semibold text-gray-900">{{ title }}</h2>
          <p v-if="subtitle" class="text-sm text-gray-500">{{ subtitle }}</p>
        </div>
      </div>
      <button @click="handleClose" :disabled="processing"
        class="p-2 text-gray-400 hover:text-gray-600 transition-colors disabled:opacity-50">
        <X class="w-5 h-5" />
      </button>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto px-6 py-4" :class="contentHeightClass">
      <slot />
    </div>

    <!-- Footer -->
    <div class="px-6 py-4 border-t bg-gray-50">
      <slot name="footer">

      </slot>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { X, Loader2, Package, Plus, Edit, Trash, Eye, Settings, DollarSign } from 'lucide-vue-next'

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
    validator: value => ['sm', 'md', 'lg', 'xl', '2xl', 'full'].includes(value)
  },
  submitText: {
    type: String,
    default: 'Save'
  },
  cancelText: {
    type: String,
    default: 'Cancel'
  },
  submitColor: {
    type: String,
    default: 'blue'
  },
  processing: {
    type: Boolean,
    default: false
  },
  closable: {
    type: Boolean,
    default: true
  },
  overlayClosable: {
    type: Boolean,
    default: true
  }
})

const emit = defineEmits(['close', 'cancel', 'submit'])

// Computed properties
const sizeClass = computed(() => {
  const sizeMap = {
    sm: 'max-w-sm',
    md: 'max-w-md',
    lg: 'max-w-lg',
    xl: 'max-w-2xl',
    '2xl': 'max-w-4xl',
    full: 'max-w-full'
  }
  return sizeMap[props.size] || 'max-w-lg'
})

const contentHeightClass = computed(() => {
  return 'max-h-[calc(100vh-160px)]'
})

const iconColorClass = computed(() => {
  const colorMap = {
    blue: 'bg-blue-500',
    green: 'bg-green-500',
    red: 'bg-red-500',
    yellow: 'bg-yellow-500',
    purple: 'bg-purple-500',
    indigo: 'bg-indigo-500'
  }
  return colorMap[props.iconColor] || 'bg-blue-500'
})

const submitColorClass = computed(() => {
  const colorMap = {
    blue: 'bg-blue-600 hover:bg-blue-700',
    green: 'bg-green-600 hover:bg-green-700',
    red: 'bg-red-600 hover:bg-red-700',
    yellow: 'bg-yellow-600 hover:bg-yellow-700',
    purple: 'bg-purple-600 hover:bg-purple-700'
  }
  return colorMap[props.submitColor] || 'bg-blue-600 hover:bg-blue-700'
})

const iconComponent = computed(() => {
  const iconMap = {
    'package-plus': Plus,
    'package': Package,
    'edit': Edit,
    'trash': Trash,
    'eye': Eye,
    'settings': Settings,
    'dollar-sign': DollarSign,
    'plus': Plus
  }
  return iconMap[props.icon] || Package
})

// Methods
const handleClose = () => {
  if (!props.processing && props.closable && props.overlayClosable) {
    emit('close')
  }
}

const handleCancel = () => {
  if (!props.processing) {
    emit('cancel')
  }
}

const handleSubmit = () => {
  if (!props.processing) {
    emit('submit')
  }
}
</script>
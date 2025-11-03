<template>
  <Dialog :open="show" @update:open="handleClose">
    <DialogContent 
      :class="cn(
        'p-0 gap-0 overflow-hidden',
        sizeClass
      )"
      @escape-key-down="handleClose"
      @pointer-down-outside="!processing ? handleClose : undefined">
      
      <!-- Header -->
      <DialogHeader v-if="title || subtitle || icon || $slots.header" 
                   class="px-6 py-4 bg-gradient-to-r from-slate-50 to-slate-100 border-b">
        <div class="flex items-center gap-3">
          <div v-if="icon" :class="cn('p-2 rounded-lg', iconColorClass)">
            <component :is="iconComponent" class="w-5 h-5 text-white" />
          </div>
          <div class="flex-1">
            <DialogTitle v-if="title" class="text-lg font-semibold text-gray-900">
              {{ title }}
            </DialogTitle>
            <DialogDescription v-if="subtitle" class="text-sm text-gray-500 mt-1">
              {{ subtitle }}
            </DialogDescription>
          </div>
        </div>
        <!-- Header slot for custom header content -->
        <slot name="header"></slot>
      </DialogHeader>

      <!-- Content -->
      <div class="px-6 py-4 max-h-[calc(100vh-200px)] overflow-y-auto">
        <!-- Main content slot -->
        <slot name="content">
          <slot></slot>
        </slot>
      </div>

      <!-- Footer -->
      <DialogFooter v-if="$slots.footer || showDefaultFooter" 
                   class="px-6 py-4 bg-gray-50 border-t">
        <slot name="footer">
          <!-- Default footer with cancel/confirm buttons -->
          <div class="flex justify-end gap-3">
            <Button 
              type="button"
              variant="outline" 
              @click="$emit('cancel')"
              :disabled="processing"
              class="px-4 py-2">
              Cancel
            </Button>
            <Button 
              type="button"
              @click="$emit('confirm')"
              :disabled="!canConfirm || processing"
              :class="cn(
                'px-4 py-2',
                confirmColorClass,
                { 'opacity-50': processing }
              )">
              <Loader2 v-if="processing" class="w-4 h-4 mr-2 animate-spin" />
              {{ processing ? 'Processing...' : confirmText }}
            </Button>
          </div>
        </slot>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>

<script setup>
import { computed } from 'vue'
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
import { Loader2, Package, Plus, Edit, Trash, Eye, Info, AlertTriangle, CheckCircle } from 'lucide-vue-next'

const emit = defineEmits(['close', 'cancel', 'confirm'])

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: null
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
    default: 'sm',
    validator: value => ['sm', 'lg', 'xl', '2xl'].includes(value)
  },
  type: {
    type: String,
    default: 'default',
    validator: value => ['default', 'success', 'warning', 'error', 'info'].includes(value)
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
  },
  showDefaultFooter: {
    type: Boolean,
    default: true
  }
})

const sizeClass = computed(() => {
  const sizeMap = {
    sm: 'max-w-sm',
    lg: 'max-w-lg', 
    xl: 'max-w-xl',
    '2xl': 'max-w-2xl'
  }
  return sizeMap[props.size] || 'max-w-sm'
})

const iconColorClass = computed(() => {
  const typeColorMap = {
    success: 'bg-green-500',
    warning: 'bg-yellow-500',
    error: 'bg-red-500',
    info: 'bg-blue-500',
    default: 'bg-gray-500'
  }
  
  const colorMap = {
    blue: 'bg-blue-500',
    green: 'bg-green-500',
    red: 'bg-red-500',
    yellow: 'bg-yellow-500',
    purple: 'bg-purple-500',
    indigo: 'bg-indigo-500',
    primary: 'bg-blue-500'
  }
  
  return typeColorMap[props.type] || colorMap[props.iconColor] || 'bg-blue-500'
})

const confirmColorClass = computed(() => {
  const colorMap = {
    primary: 'bg-blue-600 hover:bg-blue-700 text-white',
    success: 'bg-green-600 hover:bg-green-700 text-white',
    warning: 'bg-yellow-600 hover:bg-yellow-700 text-white',
    error: 'bg-red-600 hover:bg-red-700 text-white',
    info: 'bg-blue-600 hover:bg-blue-700 text-white'
  }
  return colorMap[props.confirmColor] || 'bg-blue-600 hover:bg-blue-700 text-white'
})

const iconComponent = computed(() => {
  // Type-based icons
  const typeIconMap = {
    success: CheckCircle,
    warning: AlertTriangle,
    error: AlertTriangle,
    info: Info
  }
  
  if (props.type !== 'default' && typeIconMap[props.type]) {
    return typeIconMap[props.type]
  }
  
  // Custom icon mapping
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
    'eye': Eye,
    'info': Info,
    'warning': AlertTriangle,
    'error': AlertTriangle,
    'success': CheckCircle
  }
  
  return iconMap[props.icon] || Package
})

const handleClose = () => {
  if (!props.processing) {
    emit('close')
  }
}
</script>
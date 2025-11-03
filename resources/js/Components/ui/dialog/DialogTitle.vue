<script setup>
import { DialogTitle } from 'reka-ui'
import { cn } from '@/lib/utils'

const props = defineProps({
  class: {
    type: [String, Object, Array],
    required: false,
  },
  asChild: {
    type: Boolean,
    required: false,
  },
})
</script>

<template>
  <DialogTitle
    :class="cn('text-lg font-semibold leading-none tracking-tight', props.class)"
    v-bind="{ ...props, class: undefined }"
  >
    <slot />
  </DialogTitle>
</template>
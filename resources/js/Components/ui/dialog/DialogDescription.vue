<script setup>
import { DialogDescription } from 'reka-ui'
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
  <DialogDescription
    :class="cn('text-sm text-muted-foreground', props.class)"
    v-bind="{ ...props, class: undefined }"
  >
    <slot />
  </DialogDescription>
</template>
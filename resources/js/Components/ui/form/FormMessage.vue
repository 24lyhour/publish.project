<script setup>
import { cn } from '@/lib/utils'

const props = defineProps({
  class: {
    type: [String, Object, Array],
    required: false,
  },
})
</script>

<template>
  <p
    :class="cn('text-sm font-medium text-destructive', props.class)"
  >
    <slot />
  </p>
</template>
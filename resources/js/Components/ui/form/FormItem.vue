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
  <div :class="cn('space-y-2', props.class)">
    <slot />
  </div>
</template>
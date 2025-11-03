<script setup>
import { Label } from 'reka-ui'
import { cn } from '@/lib/utils'

const props = defineProps({
  class: {
    type: [String, Object, Array],
    required: false,
  },
  for: {
    type: String,
    required: false,
  },
})
</script>

<template>
  <Label
    :class="cn(
      'text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70',
      props.class,
    )"
    v-bind="{ ...props, class: undefined }"
  >
    <slot />
  </Label>
</template>
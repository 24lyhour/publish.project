<script setup>
import { Separator } from 'reka-ui'
import { cn } from '@/lib/utils'

const props = defineProps({
  class: {
    type: [String, Object, Array],
    required: false,
  },
  orientation: {
    type: String,
    required: false,
    default: 'horizontal',
  },
  decorative: {
    type: Boolean,
    required: false,
    default: true,
  },
})
</script>

<template>
  <Separator
    :class="cn(
      'shrink-0 bg-border',
      orientation === 'horizontal' ? 'h-[1px] w-full' : 'h-full w-[1px]',
      props.class,
    )"
    v-bind="{ ...props, class: undefined }"
  />
</template>
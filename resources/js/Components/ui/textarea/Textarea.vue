<script setup>
import { cn } from '@/lib/utils'

const props = defineProps({
  class: {
    type: [String, Object, Array],
    required: false,
  },
  modelValue: {
    type: String,
    required: false,
  },
  defaultValue: {
    type: String,
    required: false,
  },
})

const emits = defineEmits(['update:modelValue'])

const handleInput = (event) => {
  emits('update:modelValue', event.target.value)
}
</script>

<template>
  <textarea
    :class="cn(
      'flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
      props.class,
    )"
    :value="modelValue || defaultValue"
    v-bind="{ ...props, class: undefined, modelValue: undefined, defaultValue: undefined }"
    @input="handleInput"
  />
</template>
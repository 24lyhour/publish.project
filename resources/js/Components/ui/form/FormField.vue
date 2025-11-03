<script setup>
import { Slot } from 'reka-ui'

defineProps({
  name: {
    type: String,
    required: false,
  }
})
</script>

<template>
  <Slot />
</template>
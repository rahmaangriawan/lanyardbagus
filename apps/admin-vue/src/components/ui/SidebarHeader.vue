<script setup>
import { cn } from '../../lib/utils'

const props = defineProps({
  class: { type: String, default: '' }
})
</script>

<template>
  <div :class="cn('flex h-14 items-center border-b px-4', props.class)">
    <slot />
  </div>
</template>

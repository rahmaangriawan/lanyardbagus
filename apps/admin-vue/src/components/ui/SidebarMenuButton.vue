<script setup>
import { cn } from '../../lib/utils'

const props = defineProps({
  class: { type: String, default: '' },
  isActive: { type: Boolean, default: false },
  asChild: { type: Boolean, default: false }
})
</script>

<template>
  <component
    :is="asChild ? 'slot' : 'button'"
    :class="cn(
      'flex w-full items-center gap-2.5 rounded-md px-3 py-2 font-medium transition-all duration-150',
      'hover:text-foreground',
      isActive 
        ? 'text-foreground font-semibold' 
        : 'text-muted-foreground',
      props.class
    )"
  >
    <slot />
  </component>
</template>

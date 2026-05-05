<script setup>
import { computed } from 'vue'
import { cn } from '../../lib/utils'

const props = defineProps({
  variant: {
    type: String,
    default: 'default',
    validator(value) {
      return ['default', 'secondary', 'outline', 'success', 'warning'].includes(value)
    }
  },
  class: {
    type: String,
    default: ''
  }
})

const variantClasses = computed(() => {
  switch (props.variant) {
    case 'secondary':
      return 'bg-secondary text-secondary-foreground'
    case 'outline':
      return 'border border-input bg-background text-foreground'
    case 'success':
      return 'border border-green-500/20 bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300'
    case 'warning':
      return 'border border-yellow-500/20 bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-300'
    default:
      return 'bg-primary text-primary-foreground'
  }
})
</script>

<template>
  <div
    :class="cn('inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium transition-colors focus:outline-none focus:ring-1 focus:ring-ring', variantClasses, props.class)"
  >
    <slot />
  </div>
</template>

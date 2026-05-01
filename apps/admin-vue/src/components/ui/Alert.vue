<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'default',
    validator(value) {
      return ['default', 'destructive', 'success', 'warning'].includes(value)
    }
  }
})

const variantClasses = computed(() => {
  switch (props.variant) {
    case 'destructive':
      return 'border-destructive/50 text-destructive dark:border-destructive [&>svg]:text-destructive'
    case 'success':
      return 'border-green-500/50 text-green-600 dark:border-green-500 [&>svg]:text-green-600 bg-green-50/50 dark:bg-green-900/20'
    case 'warning':
      return 'border-yellow-500/50 text-yellow-600 dark:border-yellow-500 [&>svg]:text-yellow-600 bg-yellow-50/50 dark:bg-yellow-900/20'
    default:
      return 'bg-background text-foreground'
  }
})
</script>

<template>
  <div
    class="relative w-full rounded-lg border p-4 [&>svg~*]:pl-7 [&>svg+div]:translate-y-[-3px] [&>svg]:absolute [&>svg]:left-4 [&>svg]:top-4 [&>svg]:text-foreground"
    :class="variantClasses"
    role="alert"
  >
    <slot />
  </div>
</template>

<script setup>
import { ref, provide, inject, computed } from 'vue'
import { cn } from '../../lib/utils'

const props = defineProps({
  class: { type: String, default: '' },
  side: { type: String, default: 'left' },
  variant: { type: String, default: 'sidebar' },
  collapsible: { type: String, default: 'icon' }
})

const open = ref(true)
const isMobile = ref(false)

const toggle = () => {
  open.value = !open.value
}

provide('sidebar', {
  open,
  toggle,
  isMobile
})
</script>

<template>
  <div
    :class="cn(
      'fixed inset-y-0 z-30 flex h-svh transition-all duration-300 ease-in-out md:flex',
      props.side === 'left' ? 'left-0' : 'right-0',
      open ? 'w-64' : 'w-16',
      props.class
    )"
  >
    <div class="flex h-full w-full flex-col bg-sidebar border-r">
      <slot />
    </div>
  </div>
</template>

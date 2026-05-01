<script setup>
import { ref, computed } from 'vue'
import { Button, Card, Input } from './ui'
import { Image, X, Search, Check } from 'lucide-vue-next'
import axios from 'axios'

const API_URL = 'https://lanyardbagus.com'

const props = defineProps({
  modelValue: { type: [String, Number, Object], default: null },
  label: { type: String, default: 'Pilih Media' }
})

const emit = defineEmits(['update:modelValue'])

const showPicker = ref(false)
const media = ref([])
const loading = ref(false)
const searchQuery = ref('')

const selectedId = computed(() => {
  if (typeof props.modelValue === 'object') return props.modelValue?.id
  return props.modelValue
})

const filteredMedia = computed(() => {
  if (!searchQuery.value) return media.value
  return media.value.filter(m => 
    m.file_name?.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

async function openPicker() {
  showPicker.value = true
  await loadMedia()
}

async function loadMedia() {
  loading.value = true
  try {
    const res = await axios.get(`${API_URL}/api/media`)
    media.value = res.data || []
  } catch (err) {
    console.error('Failed to load media:', err)
  } finally {
    loading.value = false
  }
}

function selectMedia(item) {
  emit('update:modelValue', item)
  showPicker.value = false
}

function clearSelection() {
  emit('update:modelValue', null)
}

function closePicker() {
  showPicker.value = false
}
</script>

<template>
  <div>
    <!-- Selected Preview -->
    <div v-if="modelValue" class="relative inline-block">
      <img 
        :src="typeof modelValue === 'object' ? modelValue.url : modelValue" 
        class="h-20 w-20 object-cover rounded-lg border"
      >
      <Button 
        variant="ghost" 
        size="icon" 
        class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-destructive text-white hover:bg-destructive/90"
        @click="clearSelection"
      >
        <X class="w-3 h-3" />
      </Button>
    </div>
    
    <!-- Empty State -->
    <div 
      v-else 
      class="h-20 w-20 border-2 border-dashed border-border rounded-lg flex items-center justify-center cursor-pointer hover:border-foreground transition-colors"
      @click="openPicker"
    >
      <Image class="w-8 h-8 text-muted-foreground" />
    </div>
    
    <p class="text-xs text-muted-foreground mt-2">{{ label }}</p>

    <!-- Picker Modal -->
    <div v-if="showPicker" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" @click="closePicker">
      <div class="bg-background rounded-lg max-w-3xl w-full max-h-[80vh] overflow-hidden" @click.stop>
        <div class="flex items-center justify-between p-4 border-b">
          <h3 class="font-semibold">Pilih Media</h3>
          <Button variant="ghost" size="icon" @click="closePicker">
            <X class="w-4 h-4" />
          </Button>
        </div>
        
        <div class="p-4 space-y-4">
          <!-- Search -->
          <div class="flex items-center gap-2">
            <Search class="w-4 h-4 text-muted-foreground" />
            <Input 
              v-model="searchQuery" 
              placeholder="Cari media..."
              class="flex-1"
            />
          </div>
          
          <!-- Grid -->
          <div v-if="loading" class="p-8 text-center">
            <p class="text-muted-foreground">Memuat...</p>
          </div>
          <div v-else-if="filteredMedia.length === 0" class="p-8 text-center text-muted-foreground">
            Tidak ada media
          </div>
          <div v-else class="grid grid-cols-4 sm:grid-cols-6 gap-3 max-h-[50vh] overflow-y-auto">
            <div 
              v-for="item in filteredMedia" 
              :key="item.id"
              :class="[
                'relative aspect-square rounded-lg overflow-hidden border cursor-pointer transition-all',
                selectedId === item.id ? 'ring-2 ring-foreground' : 'hover:border-foreground'
              ]"
              @click="selectMedia(item)"
            >
              <img 
                :src="item.url || item.thumbnail_url" 
                :alt="item.file_name"
                class="w-full h-full object-cover"
              >
              <div v-if="selectedId === item.id" class="absolute inset-0 bg-foreground/20 flex items-center justify-center">
                <Check class="w-6 h-6 text-foreground" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

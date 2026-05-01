<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Button, Input, Card } from './ui'
import { Image, X, Search, Check, Trash2 } from 'lucide-vue-next'
import axios from 'axios'

const API_URL = 'http://localhost:8000'

const props = defineProps({
  show: { type: Boolean, default: false }
})

const emit = defineEmits(['select', 'close'])

const media = ref([])
const loading = ref(false)
const searchQuery = ref('')
const selectedMedia = ref(null)
const altText = ref('')
const uploading = ref(false)
const dragOver = ref(false)
const fileInput = ref(null)

const filteredMedia = computed(() => {
  if (!searchQuery.value) return media.value
  return media.value.filter(m => 
    m.file_name?.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

onMounted(async () => {
  window.addEventListener('dragover', handleDragOver)
  window.addEventListener('dragleave', handleDragLeave)
  window.addEventListener('drop', handleDrop)
  await loadMedia()
})

onUnmounted(() => {
  window.removeEventListener('dragover', handleDragOver)
  window.removeEventListener('dragleave', handleDragLeave)
  window.removeEventListener('drop', handleDrop)
})

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

function handleDragOver(e) {
  e.preventDefault()
  dragOver.value = true
}

function handleDragLeave(e) {
  e.preventDefault()
  if (e.clientX === 0 || e.clientY === 0) {
    dragOver.value = false
  }
}

function handleDrop(e) {
  e.preventDefault()
  dragOver.value = false
  const files = e.dataTransfer?.files
  if (files && files.length) uploadFiles(files)
}

function handleFileSelect(e) {
  const files = e.target.files
  if (files.length) uploadFiles(files)
}

async function uploadFiles(files) {
  uploading.value = true
  try {
    for (const file of files) {
      const formData = new FormData()
      formData.append('file', file)
      const res = await axios.post(`${API_URL}/api/admin/media`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      // Auto select after upload
      selectedMedia.value = res.data
    }
    await loadMedia()
  } catch (err) {
    alert('Gagal upload: ' + (err.response?.data?.message || err.message))
  } finally {
    uploading.value = false
  }
}

function formatAltText(filename) {
  if (!filename) return ''
  const nameWithoutExt = filename.replace(/\.[^/.]+$/, '')
  const spaced = nameWithoutExt.replace(/[-_]/g, ' ')
  return spaced.replace(/\b\w/g, char => char.toUpperCase())
}

function selectMedia(item) {
  selectedMedia.value = item
  altText.value = item.alt_text || formatAltText(item.file_name)
}

function confirmSelection() {
  if (!selectedMedia.value) return
  const selected = {
    ...selectedMedia.value,
    alt_text: altText.value
  }
  emit('select', selected)
  emit('close')
}

function closeModal() {
  emit('close')
}

async function deleteMedia(id, event) {
  event.stopPropagation()
  if (!confirm('Hapus media ini?')) return
  try {
    await axios.delete(`${API_URL}/api/admin/media/${id}`)
    media.value = media.value.filter(m => m.id !== id)
    if (selectedMedia.value?.id === id) {
      selectedMedia.value = null
      altText.value = ''
    }
  } catch (err) {
    alert('Gagal menghapus: ' + err.message)
  }
}

function formatSize(bytes) {
  if (!bytes) return '-'
  const kb = bytes / 1024
  return kb > 1024 ? (kb / 1024).toFixed(1) + ' MB' : Math.round(kb) + ' KB'
}
</script>

<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" @click="closeModal">
    <div class="bg-background rounded-lg w-full max-w-4xl h-[85vh] flex flex-col" @click.stop>
      <!-- Header -->
      <div class="flex items-center justify-between p-4 border-b">
        <h3 class="font-semibold text-lg">Pilih Featured Image</h3>
        <div class="flex items-center gap-2">
          <input 
            type="file" 
            ref="fileInput"
            class="hidden" 
            multiple 
            accept="image/*"
            @change="handleFileSelect"
          >
          <Button variant="outline" size="sm" class="cursor-pointer" @click="fileInput.click()">
            <Image class="w-4 h-4 mr-2" />
            Upload Baru
          </Button>
          <Button variant="ghost" size="icon" @click="closeModal">
            <X class="w-5 h-5" />
          </Button>
        </div>
      </div>

      <div class="flex flex-1 overflow-hidden">
        <!-- Left: Media Grid -->
        <div class="flex-1 flex flex-col border-r">
          <!-- Search & Upload Zone -->
          <div class="p-4 border-b space-y-3">
            <div class="flex items-center gap-2">
              <Search class="w-4 h-4 text-muted-foreground" />
              <Input 
                v-model="searchQuery" 
                placeholder="Cari media..."
                class="flex-1"
              />
            </div>
            
            <!-- Global Drag & Drop Overlay for Modal -->
            <div 
              v-if="dragOver" 
              class="fixed inset-0 z-[100] bg-background/80 backdrop-blur-sm border-4 border-dashed border-primary flex items-center justify-center pointer-events-none"
            >
              <div class="text-center">
                <Image class="w-20 h-20 mx-auto text-primary mb-4 animate-bounce" />
                <h2 class="text-2xl font-bold text-primary">Lepaskan gambar di sini</h2>
                <p class="text-muted-foreground mt-2">Gambar akan otomatis diupload ke library</p>
              </div>
            </div>
          </div>

          <!-- Grid -->
          <div class="flex-1 overflow-y-auto p-4">
            <div v-if="loading" class="p-8 text-center">
              <p class="text-muted-foreground">Memuat...</p>
            </div>
            <div v-else-if="filteredMedia.length === 0" class="p-8 text-center text-muted-foreground">
              Tidak ada media
            </div>
            <div v-else class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
              <div 
                v-for="item in filteredMedia" 
                :key="item.id"
                :class="[
                  'relative aspect-square rounded-lg overflow-hidden border cursor-pointer transition-all',
                  selectedMedia?.id === item.id ? 'ring-2 ring-foreground' : 'hover:border-foreground'
                ]"
                @click="selectMedia(item)"
              >
                <img 
                  :src="item.url || item.thumbnail_url" 
                  :alt="item.file_name"
                  class="w-full h-full object-cover"
                >
                <div v-if="selectedMedia?.id === item.id" class="absolute inset-0 bg-foreground/20 flex items-center justify-center">
                  <Check class="w-6 h-6 text-foreground" />
                </div>
                <div class="absolute top-2 right-2 opacity-0 hover:opacity-100 transition-opacity">
                  <Button 
                    variant="ghost" 
                    size="icon" 
                    class="h-6 w-6 bg-background/80 hover:bg-destructive hover:text-white"
                    @click.stop="deleteMedia(item.id, $event)"
                  >
                    <Trash2 class="w-3 h-3" />
                  </Button>
                </div>
                <div class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-[10px] px-2 py-1 truncate">
                  {{ formatSize(item.size) }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: Preview & Alt Text -->
        <div class="w-80 flex flex-col bg-muted/30">
          <div class="flex-1 overflow-y-auto p-4 space-y-4">
            <div v-if="selectedMedia" class="space-y-4">
              <!-- Preview -->
              <div>
                <h4 class="text-xs font-semibold text-muted-foreground uppercase mb-2">Preview</h4>
                <img 
                  :src="selectedMedia.url" 
                  :alt="selectedMedia.file_name"
                  class="w-full h-48 object-cover rounded-lg"
                >
              </div>

              <!-- File Info -->
              <div>
                <h4 class="text-xs font-semibold text-muted-foreground uppercase mb-2">Info File</h4>
                <Card class="p-3 space-y-2">
                  <div class="flex justify-between text-sm">
                    <span class="text-muted-foreground">Nama</span>
                    <span class="truncate max-w-[150px]">{{ selectedMedia.file_name }}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-muted-foreground">Ukuran</span>
                    <span>{{ formatSize(selectedMedia.size) }}</span>
                  </div>
                </Card>
              </div>

              <!-- Alt Text -->
              <div>
                <h4 class="text-xs font-semibold text-muted-foreground uppercase mb-2">Alt Text (SEO)</h4>
                <textarea 
                  v-model="altText" 
                  rows="3"
                  class="w-full px-3 py-2 rounded-md border border-input bg-background text-sm resize-none"
                  placeholder="Deskripsi gambar untuk SEO dan accessibility..."
                ></textarea>
                <p class="text-xs text-muted-foreground mt-1">
                  {{ altText.length }}/125 karakter
                </p>
              </div>
            </div>
            <div v-else class="text-center text-muted-foreground py-8">
              <Image class="w-12 h-12 mx-auto mb-3 opacity-50" />
              <p class="text-sm">Pilih gambar dari library</p>
            </div>
          </div>

          <!-- Footer -->
          <div class="p-4 border-t bg-background">
            <Button 
              class="w-full" 
              :disabled="!selectedMedia"
              @click="confirmSelection"
            >
              <Check class="w-4 h-4 mr-2" />
              Pilih Gambar Ini
            </Button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

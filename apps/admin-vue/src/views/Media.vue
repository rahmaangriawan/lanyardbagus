<script setup>
import { ref, onMounted, computed, onUnmounted, watch } from 'vue'
import { Card, Button, Input } from '../components/ui'
import { Plus, Image, Search, Trash2, Loader2, Copy, X } from 'lucide-vue-next'
import axios from 'axios'

const API_URL = 'https://lanyardbagus.com'

const media = ref([])
const loading = ref(true)
const uploading = ref(false)
const searchQuery = ref('')
const dragOver = ref(false)
const selectedMedia = ref(null)
const copySuccess = ref(false)
const fileInput = ref(null)
const mediaPage = ref(1)
const mediaHasMore = ref(false)
const loadingMore = ref(false)
let searchTimer = null

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
  if (searchTimer) clearTimeout(searchTimer)
})

watch(searchQuery, () => {
  if (searchTimer) clearTimeout(searchTimer)
  searchTimer = setTimeout(() => loadMedia(), 300)
})

async function loadMedia({ append = false } = {}) {
  if (append) {
    loadingMore.value = true
  } else {
    loading.value = true
    mediaPage.value = 1
  }

  try {
    const res = await axios.get(`${API_URL}/api/media`, {
      params: { per_page: 24, page: mediaPage.value, q: searchQuery.value || undefined }
    })
    const items = res.data?.data || res.data || []
    media.value = append ? [...media.value, ...items] : items
    mediaHasMore.value = Boolean(res.data?.meta && res.data.meta.current_page < res.data.meta.last_page)
  } catch (err) {
    console.error('Failed to load media:', err)
  } finally {
    loading.value = false
    loadingMore.value = false
  }
}

async function loadMoreMedia() {
  if (!mediaHasMore.value || loadingMore.value) return
  mediaPage.value += 1
  await loadMedia({ append: true })
}

function handleDragOver(e) {
  e.preventDefault()
  dragOver.value = true
}

function handleDragLeave(e) {
  e.preventDefault()
  // Ensure we only hide overlay if mouse leaves the window entirely
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
      await axios.post(`${API_URL}/api/admin/media`, formData, {
        withCredentials: true,
        headers: { 'Content-Type': 'multipart/form-data' }
      })
    }
    await loadMedia()
  } catch (err) {
    alert('Gagal upload: ' + (err.response?.data?.message || err.message))
  } finally {
    uploading.value = false
  }
}

async function deleteMedia(id) {
  if (!confirm('Hapus media ini?')) return
  try {
    await axios.delete(`${API_URL}/api/admin/media/${id}`, { withCredentials: true })
    media.value = media.value.filter(m => m.id !== id)
    if (selectedMedia.value?.id === id) selectedMedia.value = null
  } catch (err) {
    alert('Gagal menghapus: ' + err.message)
  }
}

function selectMedia(item) {
  selectedMedia.value = item
}

function closePreview() {
  selectedMedia.value = null
}

async function copyUrl() {
  if (!selectedMedia.value?.url) return
  try {
    await navigator.clipboard.writeText(selectedMedia.value.url)
    copySuccess.value = true
    setTimeout(() => copySuccess.value = false, 2000)
  } catch (err) {
    console.error('Failed to copy:', err)
  }
}

function formatSize(bytes) {
  if (!bytes) return '-'
  const kb = bytes / 1024
  return kb > 1024 ? (kb / 1024).toFixed(1) + ' MB' : Math.round(kb) + ' KB'
}
</script>

<template>
  <div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4">
      <div class="flex items-center gap-3 flex-1 max-w-sm">
        <Search class="w-4 h-4 text-muted-foreground" />
        <Input 
          v-model="searchQuery" 
          placeholder="Cari media..."
          class="h-9"
        />
      </div>
      <div class="flex items-center gap-3">
        <input 
          type="file" 
          ref="fileInput"
          class="hidden" 
          multiple 
          accept="image/*"
          @change="handleFileSelect"
        >
        <Button variant="outline" class="cursor-pointer" @click="fileInput.click()">
          <Plus class="w-4 h-4 mr-2" />
          Upload
        </Button>
      </div>
    </div>

    <!-- Global Drag & Drop Overlay -->
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

    <!-- Uploading Overlay -->
    <div 
      v-if="uploading" 
      class="fixed inset-0 z-[100] bg-background/80 backdrop-blur-sm flex flex-col items-center justify-center pointer-events-none"
    >
      <div class="bg-card p-8 rounded-xl border shadow-lg text-center flex flex-col items-center">
        <Loader2 class="w-16 h-16 text-primary mb-4 animate-spin" />
        <h2 class="text-xl font-bold text-foreground">Sedang Mengupload...</h2>
        <p class="text-muted-foreground mt-2">Mohon tunggu sebentar, gambar sedang diproses.</p>
      </div>
    </div>

    <!-- Media Grid -->
    <Card class="p-4">
      <div v-if="loading" class="p-8 text-center">
        <Loader2 class="w-6 h-6 animate-spin mx-auto text-muted-foreground" />
      </div>
      <div v-else-if="filteredMedia.length === 0" class="p-8 text-center text-muted-foreground">
        Tidak ada media
      </div>
      <div v-else class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 gap-3">
        <div 
          v-for="item in filteredMedia" 
          :key="item.id"
          :class="[
            'group relative aspect-square rounded-lg overflow-hidden border cursor-pointer transition-all',
            selectedMedia?.id === item.id ? 'ring-2 ring-foreground' : 'hover:border-foreground'
          ]"
          @click="selectMedia(item)"
        >
          <img 
            :src="item.url || item.thumbnail_url" 
            :alt="item.file_name"
            class="w-full h-full object-cover"
          >
          <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
            <Button 
              variant="ghost" 
              size="icon" 
              class="h-8 w-8 text-white hover:bg-white/20"
              @click.stop="deleteMedia(item.id)"
            >
              <Trash2 class="w-4 h-4" />
            </Button>
          </div>
          <div class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-[10px] px-2 py-1 truncate">
            {{ formatSize(item.size) }}
          </div>
        </div>
      </div>
      <div v-if="mediaHasMore" class="pt-4 text-center">
        <Button variant="outline" size="sm" :disabled="loadingMore" @click="loadMoreMedia">
          {{ loadingMore ? 'Memuat...' : 'Muat Lagi' }}
        </Button>
      </div>
    </Card>

    <!-- Preview Modal -->
    <div v-if="selectedMedia" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" @click="closePreview">
      <div class="bg-background rounded-lg max-w-2xl w-full max-h-[90vh] overflow-hidden" @click.stop>
        <div class="flex items-center justify-between p-4 border-b">
          <h3 class="font-semibold truncate pr-4">{{ selectedMedia.file_name }}</h3>
          <div class="flex items-center gap-2">
            <Button variant="ghost" size="sm" @click="copyUrl">
              <Copy class="w-4 h-4 mr-2" />
              {{ copySuccess ? 'Tersalin!' : 'Salin URL' }}
            </Button>
            <Button variant="ghost" size="icon" @click="closePreview">
              <X class="w-4 h-4" />
            </Button>
          </div>
        </div>
        <div class="p-4">
          <img 
            :src="selectedMedia.url" 
            :alt="selectedMedia.file_name"
            class="max-h-[50vh] mx-auto rounded-lg"
          >
          <div class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="text-muted-foreground">Nama file</span>
              <span class="font-mono">{{ selectedMedia.file_name }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-muted-foreground">Ukuran</span>
              <span>{{ formatSize(selectedMedia.size) }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-muted-foreground">URL</span>
              <span class="font-mono text-xs max-w-xs truncate">{{ selectedMedia.url }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

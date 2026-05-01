<script setup>
import { ref, onMounted, computed } from 'vue'
import { Card, Button, Input } from '../components/ui'
import { Plus, Flag, Pencil, Trash2, Loader2, X, Image, Save, Search, Upload } from 'lucide-vue-next'
import axios from 'axios'

const API_URL = 'http://localhost:8000'

const banners = ref([])
const loading = ref(true)
const showForm = ref(false)
const editing = ref(null)
const saving = ref(false)

// Media picker state
const showPicker = ref(false)
const pickerTarget = ref('') // 'image_url' or 'card_image_url'
const mediaList = ref([])
const mediaLoading = ref(false)
const mediaSearch = ref('')
const pickerDragOver = ref(false)
const pickerUploading = ref(false)
const pickerFileInput = ref(null)

const filteredMedia = computed(() => {
  if (!mediaSearch.value) return mediaList.value
  return mediaList.value.filter(m => m.file_name?.toLowerCase().includes(mediaSearch.value.toLowerCase()))
})

const form = ref({
  title: '',
  description: '',
  badge: '',
  image_url: '',
  card_image_url: '',
  link_url: '',
  button_text: '',
  order: 0,
  is_active: true,
})

function resetForm() {
  form.value = { title: '', description: '', badge: '', image_url: '', card_image_url: '', link_url: '', button_text: '', order: 0, is_active: true }
  editing.value = null
  showForm.value = false
}

function openCreate() {
  resetForm()
  form.value.order = banners.value.length
  showForm.value = true
}

function openEdit(banner) {
  form.value = { ...banner }
  editing.value = banner.id
  showForm.value = true
}

async function loadBanners() {
  loading.value = true
  try {
    const res = await axios.get(`${API_URL}/api/banners`)
    banners.value = res.data || []
  } catch (err) {
    console.error('Failed to load:', err)
  } finally {
    loading.value = false
  }
}

async function openMediaPicker(target) {
  pickerTarget.value = target
  showPicker.value = true
  mediaSearch.value = ''
  if (mediaList.value.length === 0) {
    mediaLoading.value = true
    try {
      const res = await axios.get(`${API_URL}/api/media`)
      mediaList.value = res.data || []
    } catch (err) {
      console.error('Failed to load media:', err)
    } finally {
      mediaLoading.value = false
    }
  }
}

function selectMediaItem(item) {
  form.value[pickerTarget.value] = item.url
  showPicker.value = false
}

function onPickerDragOver(e) { e.preventDefault(); pickerDragOver.value = true }
function onPickerDragLeave(e) { e.preventDefault(); pickerDragOver.value = false }
async function onPickerDrop(e) {
  e.preventDefault()
  pickerDragOver.value = false
  const files = e.dataTransfer?.files
  if (files?.length) await uploadToMediaLibrary(files)
}
function onPickerFileSelect(e) {
  const files = e.target.files
  if (files?.length) uploadToMediaLibrary(files)
  e.target.value = ''
}
async function uploadToMediaLibrary(files) {
  pickerUploading.value = true
  try {
    for (const file of files) {
      const fd = new FormData()
      fd.append('file', file)
      await axios.post(`${API_URL}/api/admin/media`, fd, { withCredentials: true, headers: { 'Content-Type': 'multipart/form-data' } })
    }
    const res = await axios.get(`${API_URL}/api/media`)
    mediaList.value = res.data || []
  } catch (err) {
    alert('Upload gagal: ' + (err.response?.data?.message || err.message))
  } finally {
    pickerUploading.value = false
  }
}

async function saveHero() {
  if (!form.value.title || !form.value.image_url) {
    alert('Judul dan Gambar Background wajib diisi')
    return
  }
  saving.value = true
  try {
    if (editing.value) {
      await axios.put(`${API_URL}/api/admin/banners/${editing.value}`, form.value, { withCredentials: true })
    } else {
      await axios.post(`${API_URL}/api/admin/banners`, form.value, { withCredentials: true })
    }
    resetForm()
    await loadBanners()
  } catch (err) {
    alert('Gagal menyimpan: ' + (err.response?.data?.message || err.message))
  } finally {
    saving.value = false
  }
}

async function deleteBanner(id) {
  if (!confirm('Hapus hero ini?')) return
  try {
    await axios.delete(`${API_URL}/api/admin/banners/${id}`, { withCredentials: true })
    banners.value = banners.value.filter(b => b.id !== id)
  } catch (err) {
    alert('Gagal menghapus: ' + err.message)
  }
}

async function toggleActive(banner) {
  try {
    await axios.put(`${API_URL}/api/admin/banners/${banner.id}`, { ...banner, is_active: !banner.is_active }, { withCredentials: true })
    banner.is_active = !banner.is_active
  } catch (err) {
    alert('Gagal update: ' + err.message)
  }
}

onMounted(loadBanners)
</script>

<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-xl font-semibold">Hero</h2>
        <p class="text-sm text-muted-foreground mt-1">Kelola konten hero section di halaman utama</p>
      </div>
      <Button @click="openCreate">
        <Plus class="w-4 h-4 mr-2" />
        Tambah Hero
      </Button>
    </div>

    <!-- Form -->
    <Card v-if="showForm" class="border-primary/30">
      <div class="p-6 space-y-5">
        <div class="flex items-center justify-between">
          <h3 class="font-semibold text-lg">{{ editing ? 'Edit Hero' : 'Hero Baru' }}</h3>
          <button @click="resetForm" class="text-muted-foreground hover:text-foreground"><X class="w-5 h-5" /></button>
        </div>

        <!-- Two Image Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <!-- Background Image -->
          <div>
            <label class="block text-sm font-medium mb-2">Gambar Background <span class="text-destructive">*</span></label>
            <div v-if="form.image_url" class="relative mb-2">
              <img :src="form.image_url" alt="" class="w-full h-36 object-cover rounded-lg border">
              <button @click="form.image_url = ''" class="absolute top-1.5 right-1.5 w-6 h-6 bg-black/60 text-white rounded-full flex items-center justify-center hover:bg-black/80"><X class="w-3 h-3" /></button>
            </div>
            <button @click="openMediaPicker('image_url')" class="flex items-center justify-center gap-2 w-full border-2 border-dashed rounded-lg p-3 cursor-pointer hover:border-primary/50 transition-colors text-sm text-muted-foreground">
              <Image class="w-4 h-4" />
              {{ form.image_url ? 'Ganti dari Media' : 'Pilih dari Media' }}
            </button>
            <p class="text-xs text-muted-foreground mt-1.5">Gambar fullscreen di belakang hero</p>
          </div>

          <!-- Card Image (3D Tilt) -->
          <div>
            <label class="block text-sm font-medium mb-2">Gambar Kartu 3D</label>
            <div v-if="form.card_image_url" class="relative mb-2">
              <img :src="form.card_image_url" alt="" class="w-full h-36 object-cover rounded-lg border">
              <button @click="form.card_image_url = ''" class="absolute top-1.5 right-1.5 w-6 h-6 bg-black/60 text-white rounded-full flex items-center justify-center hover:bg-black/80"><X class="w-3 h-3" /></button>
            </div>
            <button @click="openMediaPicker('card_image_url')" class="flex items-center justify-center gap-2 w-full border-2 border-dashed rounded-lg p-3 cursor-pointer hover:border-primary/50 transition-colors text-sm text-muted-foreground">
              <Image class="w-4 h-4" />
              {{ form.card_image_url ? 'Ganti dari Media' : 'Pilih dari Media' }}
            </button>
            <p class="text-xs text-muted-foreground mt-1.5">Gambar di kartu interaktif sebelah kanan. Jika kosong, pakai background.</p>
          </div>
        </div>

        <!-- Title -->
        <div>
          <label class="block text-sm font-medium mb-2">Judul <span class="text-destructive">*</span></label>
          <input v-model="form.title" type="text" placeholder="Tali Berkualitas, Wujud Nyata Identitas Tim Anda." class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-background">
        </div>

        <!-- Description -->
        <div>
          <label class="block text-sm font-medium mb-2">Deskripsi</label>
          <textarea v-model="form.description" rows="3" placeholder="Deskripsi singkat yang muncul di bawah judul..." class="w-full px-3 py-2 border rounded-lg text-sm resize-none focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-background"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-2">Badge</label>
            <input v-model="form.badge" type="text" placeholder="TALI BERKUALITAS PREMIUM" class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-background">
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">Teks Tombol</label>
            <input v-model="form.button_text" type="text" placeholder="Lihat Cerita Kami" class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-background">
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-2">Link Tombol</label>
          <input v-model="form.link_url" type="text" placeholder="#cerita" class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-background">
        </div>

        <div class="flex items-center gap-3">
          <button @click="form.is_active = !form.is_active" :class="['relative w-10 h-5 rounded-full transition-colors', form.is_active ? 'bg-primary' : 'bg-muted']">
            <span :class="['absolute top-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform', form.is_active ? 'left-5' : 'left-0.5']"></span>
          </button>
          <span class="text-sm">{{ form.is_active ? 'Aktif' : 'Nonaktif' }}</span>
        </div>

        <div class="flex items-center gap-3 pt-2">
          <Button @click="saveHero" :disabled="saving">
            <Loader2 v-if="saving" class="w-4 h-4 mr-2 animate-spin" />
            <Save v-else class="w-4 h-4 mr-2" />
            {{ saving ? 'Menyimpan...' : 'Simpan' }}
          </Button>
          <Button variant="outline" @click="resetForm">Batal</Button>
        </div>
      </div>
    </Card>

    <!-- Hero List -->
    <Card>
      <div v-if="loading" class="p-8 text-center"><Loader2 class="w-6 h-6 animate-spin mx-auto text-primary" /></div>
      <div v-else-if="banners.length === 0" class="p-12 text-center">
        <Flag class="w-12 h-12 mx-auto text-muted-foreground mb-4" />
        <p class="text-muted-foreground mb-2">Belum ada hero</p>
        <p class="text-sm text-muted-foreground">Klik "Tambah Hero" untuk menambahkan konten hero section</p>
      </div>
      <table v-else class="w-full">
        <thead class="border-b bg-muted/50">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-medium text-muted-foreground">Background</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-muted-foreground">Kartu 3D</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-muted-foreground">Judul</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-muted-foreground">Status</th>
            <th class="px-6 py-3 text-right text-sm font-medium text-muted-foreground">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr v-for="banner in banners" :key="banner.id" class="hover:bg-muted/30">
            <td class="px-6 py-4"><img :src="banner.image_url" alt="" class="w-20 h-12 object-cover rounded-lg border"></td>
            <td class="px-6 py-4">
              <img v-if="banner.card_image_url" :src="banner.card_image_url" alt="" class="w-20 h-12 object-cover rounded-lg border">
              <span v-else class="text-xs text-muted-foreground">—</span>
            </td>
            <td class="px-6 py-4">
              <p class="font-medium text-sm">{{ banner.title }}</p>
              <p class="text-xs text-muted-foreground mt-0.5 line-clamp-1">{{ banner.description }}</p>
            </td>
            <td class="px-6 py-4">
              <button @click="toggleActive(banner)" :class="['px-2 py-1 text-xs rounded-full font-medium cursor-pointer transition-colors', banner.is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200']">
                {{ banner.is_active ? 'Active' : 'Inactive' }}
              </button>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-1">
                <Button variant="ghost" size="sm" @click="openEdit(banner)"><Pencil class="w-4 h-4" /></Button>
                <Button variant="ghost" size="sm" @click="deleteBanner(banner.id)"><Trash2 class="w-4 h-4 text-destructive" /></Button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </Card>

    <!-- Media Picker Modal -->
    <div v-if="showPicker" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" @click.self="showPicker = false">
      <div
        class="bg-background rounded-xl max-w-3xl w-full max-h-[80vh] flex flex-col overflow-hidden border shadow-2xl relative"
        @dragover="onPickerDragOver"
        @dragleave="onPickerDragLeave"
        @drop="onPickerDrop"
      >
        <!-- Drag overlay -->
        <div v-if="pickerDragOver" class="absolute inset-0 z-10 bg-primary/10 border-2 border-dashed border-primary rounded-xl flex items-center justify-center pointer-events-none">
          <div class="text-center">
            <Upload class="w-10 h-10 mx-auto text-primary mb-2 animate-bounce" />
            <p class="font-semibold text-primary">Lepaskan gambar di sini</p>
          </div>
        </div>

        <div class="flex items-center justify-between p-4 border-b">
          <h3 class="font-semibold">Pilih Media</h3>
          <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-muted rounded-lg px-3 py-1.5">
              <Search class="w-3.5 h-3.5 text-muted-foreground" />
              <input v-model="mediaSearch" placeholder="Cari..." class="bg-transparent text-sm outline-none w-32">
            </div>
            <input type="file" ref="pickerFileInput" class="hidden" multiple accept="image/*" @change="onPickerFileSelect">
            <Button variant="outline" size="sm" @click="pickerFileInput.click()" :disabled="pickerUploading">
              <Loader2 v-if="pickerUploading" class="w-3.5 h-3.5 mr-1.5 animate-spin" />
              <Upload v-else class="w-3.5 h-3.5 mr-1.5" />
              Upload
            </Button>
            <button @click="showPicker = false" class="text-muted-foreground hover:text-foreground"><X class="w-5 h-5" /></button>
          </div>
        </div>
        <div class="flex-1 overflow-auto p-4">
          <div v-if="mediaLoading || pickerUploading" class="p-8 text-center">
            <Loader2 class="w-6 h-6 animate-spin mx-auto text-primary" />
            <p v-if="pickerUploading" class="text-sm text-muted-foreground mt-2">Mengupload...</p>
          </div>
          <div v-else-if="filteredMedia.length === 0" class="p-8 text-center text-muted-foreground text-sm">Tidak ada media. Upload atau drag & drop gambar ke sini.</div>
          <div v-else class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-3">
            <div
              v-for="item in filteredMedia"
              :key="item.id"
              @click="selectMediaItem(item)"
              class="aspect-square rounded-lg overflow-hidden border-2 cursor-pointer transition-all hover:border-primary hover:ring-2 hover:ring-primary/20 border-transparent"
            >
              <img :src="item.url || item.thumbnail_url" :alt="item.file_name" class="w-full h-full object-cover">
            </div>
          </div>
        </div>
        <div class="px-4 py-2.5 border-t text-xs text-muted-foreground">Drag & drop gambar ke area ini atau klik Upload</div>
      </div>
    </div>
  </div>
</template>

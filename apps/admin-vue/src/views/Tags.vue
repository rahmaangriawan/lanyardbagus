<script setup>
import { ref, onMounted, computed } from 'vue'
import { Card, Button, Input, Label } from '../components/ui'
import { Plus, Search, Trash2, Loader2, Edit2, Check, X, Tag } from 'lucide-vue-next'
import axios from 'axios'

const API_URL = 'http://localhost:8000'

const tags = ref([])
const loading = ref(true)
const search = ref('')
const showModal = ref(false)
const editingId = ref(null)

const form = ref({
  name: '',
  slug: '',
  description: '',
  robot_meta: 'index' // 'index' or 'noindex'
})

const filteredTags = computed(() => {
  if (!search.value) return tags.value
  const term = search.value.toLowerCase()
  return tags.value.filter(t => 
    t.name.toLowerCase().includes(term) || 
    t.slug.toLowerCase().includes(term)
  )
})

onMounted(async () => {
  await loadTags()
})

async function loadTags() {
  loading.value = true
  try {
    const res = await axios.get(`${API_URL}/api/tags`)
    tags.value = res.data || []
  } catch (err) {
    console.error('Failed to load tags:', err)
  } finally {
    loading.value = false
  }
}

function generateSlug() {
  if (!form.value.name) return
  form.value.slug = form.value.name
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-|-$/g, '')
}

function openModal(tag = null) {
  if (tag) {
    editingId.value = tag.id
    form.value = { ...tag }
  } else {
    editingId.value = null
    form.value = { name: '', slug: '', description: '' }
  }
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  editingId.value = null
}

async function saveTag() {
  try {
    if (editingId.value) {
      await axios.put(`${API_URL}/api/admin/tags/${editingId.value}`, form.value, { withCredentials: true })
    } else {
      await axios.post(`${API_URL}/api/admin/tags`, form.value, { withCredentials: true })
    }
    await loadTags()
    closeModal()
  } catch (err) {
    alert('Gagal menyimpan: ' + (err.response?.data?.message || err.message))
  }
}

async function deleteTag(id) {
  if (!confirm('Yakin ingin menghapus tag ini?')) return
  try {
    await axios.delete(`${API_URL}/api/admin/tags/${id}`, { withCredentials: true })
    await loadTags()
  } catch (err) {
    alert('Gagal menghapus: ' + (err.response?.data?.message || err.message))
  }
}
</script>

<template>
  <div class="p-6 h-full overflow-y-auto scrollbar-thin">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold">Tags</h1>
        <p class="text-muted-foreground">Kelola tag untuk post</p>
      </div>
      <Button @click="openModal()">
        <Plus class="w-4 h-4 mr-2" />
        Tag Baru
      </Button>
    </div>

    <!-- Search -->
    <div class="flex items-center gap-4 mb-6">
      <div class="relative flex-1 max-w-sm">
        <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
        <Input 
          v-model="search"
          placeholder="Cari tag..."
          class="pl-10"
        />
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="p-12 text-center">
      <Loader2 class="w-6 h-6 animate-spin mx-auto" />
    </div>

    <!-- Tags Grid -->
    <div v-else class="flex flex-wrap gap-3">
      <Card 
        v-for="tag in filteredTags" 
        :key="tag.id"
        class="inline-flex items-center gap-2 px-4 py-2 hover:shadow-md transition-shadow cursor-pointer"
        @click="openModal(tag)"
      >
        <Tag class="w-4 h-4 text-muted-foreground" />
        <div class="flex flex-col">
          <span class="font-medium text-sm">{{ tag.name }}</span>
          <span class="text-xs text-muted-foreground">/{{ tag.slug }}</span>
          <span v-if="tag.robot_meta" class="text-[10px]" :class="tag.robot_meta === 'index' ? 'text-green-600' : 'text-orange-600'">{{ tag.robot_meta }}</span>
        </div>
        <div class="flex gap-1 ml-2" @click.stop>
          <Button variant="ghost" size="icon" class="h-6 w-6 text-destructive" @click="deleteTag(tag.id)">
            <Trash2 class="w-3 h-3" />
          </Button>
        </div>
      </Card>
    </div>

    <!-- Empty State -->
    <div v-if="!loading && filteredTags.length === 0" class="text-center py-12">
      <p class="text-muted-foreground">Belum ada tag</p>
      <Button variant="outline" class="mt-4" @click="openModal()">
        <Plus class="w-4 h-4 mr-2" />
        Buat Tag Pertama
      </Button>
    </div>

    <!-- Modal -->
    <div 
      v-if="showModal" 
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
      @click.self="closeModal"
    >
      <Card class="w-full max-w-md max-h-[90vh] overflow-hidden m-4 flex flex-col">
        <div class="p-6 flex-shrink-0">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">
              {{ editingId ? 'Edit Tag' : 'Tag Baru' }}
            </h2>
            <Button variant="ghost" size="icon" @click="closeModal">
              <X class="w-4 h-4" />
            </Button>
          </div>
        </div>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto px-6 pb-6 scrollbar-thin">
          <div class="space-y-4">
            <!-- Name -->
            <div class="space-y-2">
              <Label>Nama Tag</Label>
              <Input 
                v-model="form.name" 
                placeholder="Nama tag..."
                @blur="generateSlug"
              />
            </div>

            <!-- Slug -->
            <div class="space-y-2">
              <Label>Slug</Label>
              <Input 
                v-model="form.slug" 
                placeholder="nama-tag"
              />
            </div>

            <!-- Description -->
            <div class="space-y-2">
              <Label>Deskripsi (Opsional)</Label>
              <textarea 
                v-model="form.description" 
                rows="3"
                class="w-full px-3 py-2 rounded-md border border-input bg-background text-sm resize-none"
                placeholder="Deskripsi singkat tag..."
              ></textarea>
            </div>

            <!-- Robot Meta -->
            <div class="space-y-2 pt-2 border-t">
              <Label class="text-xs text-muted-foreground uppercase">Crawl Bot</Label>
              <div class="space-y-2">
                <label 
                  :class="[
                    'flex items-start gap-3 cursor-pointer p-2 rounded-md transition-colors',
                    form.robot_meta === 'index' ? 'bg-primary/10' : 'hover:bg-secondary'
                  ]"
                >
                  <input 
                    type="radio" 
                    v-model="form.robot_meta" 
                    value="index"
                    class="w-4 h-4 accent-primary mt-0.5"
                  >
                  <div>
                    <span :class="['text-sm font-medium block', form.robot_meta === 'index' ? 'text-primary' : '']">Index</span>
                    <span :class="['text-xs', form.robot_meta === 'index' ? 'text-primary/70' : 'text-muted-foreground']">Bot boleh crawl halaman ini</span>
                  </div>
                </label>
                <label 
                  :class="[
                    'flex items-start gap-3 cursor-pointer p-2 rounded-md transition-colors',
                    form.robot_meta === 'noindex' ? 'bg-primary/10' : 'hover:bg-secondary'
                  ]"
                >
                  <input 
                    type="radio" 
                    v-model="form.robot_meta" 
                    value="noindex"
                    class="w-4 h-4 accent-primary mt-0.5"
                  >
                  <div>
                    <span :class="['text-sm font-medium block', form.robot_meta === 'noindex' ? 'text-primary' : '']">No Index</span>
                    <span :class="['text-xs', form.robot_meta === 'noindex' ? 'text-primary/70' : 'text-muted-foreground']">Bot tidak boleh crawl halaman ini</span>
                  </div>
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Fixed Footer -->
        <div class="p-6 border-t bg-background flex-shrink-0 flex justify-end gap-2">
          <Button variant="outline" @click="closeModal">Batal</Button>
          <Button @click="saveTag">
            <Check class="w-4 h-4 mr-2" />
            Simpan
          </Button>
        </div>
      </Card>
    </div>
  </div>
</template>

<style scoped>
/* Custom Scrollbar Styles */
.scrollbar-thin {
  scrollbar-width: thin;
  scrollbar-color: hsl(var(--primary) / 0.3) transparent;
}

.scrollbar-thin::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
  background: transparent;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
  background: hsl(var(--primary) / 0.3);
  border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
  background: hsl(var(--primary) / 0.5);
}
</style>

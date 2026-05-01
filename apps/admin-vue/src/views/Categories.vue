<script setup>
import { ref, onMounted, computed } from 'vue'
import { Card, Button, Input, Label } from '../components/ui'
import { Plus, Search, Trash2, Loader2, Edit2, Check, X } from 'lucide-vue-next'
import axios from 'axios'

const API_URL = 'https://lanyardbagus.com'

const categories = ref([])
const loading = ref(true)
const search = ref('')
const showModal = ref(false)
const editingId = ref(null)

const form = ref({
  name: '',
  slug: '',
  description: '',
  meta_title: '',
  meta_description: '',
  robot_meta: 'index' // 'index' or 'noindex'
})

const filteredCategories = computed(() => {
  if (!search.value) return categories.value
  const term = search.value.toLowerCase()
  return categories.value.filter(c => 
    c.name.toLowerCase().includes(term) || 
    c.slug.toLowerCase().includes(term)
  )
})

onMounted(async () => {
  await loadCategories()
})

async function loadCategories() {
  loading.value = true
  try {
    const res = await axios.get(`${API_URL}/api/categories`)
    categories.value = res.data || []
  } catch (err) {
    console.error('Failed to load categories:', err)
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

function openModal(category = null) {
  if (category) {
    editingId.value = category.id
    form.value = { ...category }
  } else {
    editingId.value = null
    form.value = { name: '', slug: '', description: '', meta_title: '', meta_description: '' }
  }
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  editingId.value = null
}

async function saveCategory() {
  try {
    if (editingId.value) {
      await axios.put(`${API_URL}/api/admin/categories/${editingId.value}`, form.value, { withCredentials: true })
    } else {
      await axios.post(`${API_URL}/api/admin/categories`, form.value, { withCredentials: true })
    }
    await loadCategories()
    closeModal()
  } catch (err) {
    alert('Gagal menyimpan: ' + (err.response?.data?.message || err.message))
  }
}

async function deleteCategory(id) {
  if (!confirm('Yakin ingin menghapus kategori ini?')) return
  try {
    await axios.delete(`${API_URL}/api/admin/categories/${id}`, { withCredentials: true })
    await loadCategories()
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
        <h1 class="text-2xl font-bold">Kategori</h1>
        <p class="text-muted-foreground">Kelola kategori post dengan SEO meta</p>
      </div>
      <Button @click="openModal()">
        <Plus class="w-4 h-4 mr-2" />
        Kategori Baru
      </Button>
    </div>

    <!-- Search -->
    <div class="flex items-center gap-4 mb-6">
      <div class="relative flex-1 max-w-sm">
        <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
        <Input 
          v-model="search"
          placeholder="Cari kategori..."
          class="pl-10"
        />
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="p-12 text-center">
      <Loader2 class="w-6 h-6 animate-spin mx-auto" />
    </div>

    <!-- Categories Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <Card 
        v-for="cat in filteredCategories" 
        :key="cat.id"
        class="p-4 hover:shadow-md transition-shadow cursor-pointer"
        @click="openModal(cat)"
      >
        <div class="flex items-start justify-between">
          <div class="flex-1 min-w-0">
            <h3 class="font-semibold truncate">{{ cat.name }}</h3>
            <p class="text-sm text-muted-foreground">/{{ cat.slug }}</p>
            <p v-if="cat.description" class="text-xs text-muted-foreground mt-1 line-clamp-2">
              {{ cat.description }}
            </p>
            
            <!-- SEO Meta Preview -->
            <div v-if="cat.meta_title || cat.meta_description || cat.robot_meta" class="mt-2 pt-2 border-t">
              <p class="text-xs font-medium text-primary">SEO:</p>
              <p v-if="cat.meta_title" class="text-xs text-muted-foreground truncate">
                Title: {{ cat.meta_title }}
              </p>
              <p v-if="cat.meta_description" class="text-xs text-muted-foreground line-clamp-1">
                Desc: {{ cat.meta_description.substring(0, 50) }}...
              </p>
              <p v-if="cat.robot_meta" class="text-xs">
                Bot: <span :class="cat.robot_meta === 'index' ? 'text-green-600' : 'text-orange-600'">{{ cat.robot_meta }}</span>
              </p>
            </div>
          </div>
          
          <div class="flex gap-1 ml-2" @click.stop>
            <Button variant="ghost" size="icon" class="h-8 w-8" @click="openModal(cat)">
              <Edit2 class="w-4 h-4" />
            </Button>
            <Button variant="ghost" size="icon" class="h-8 w-8 text-destructive" @click="deleteCategory(cat.id)">
              <Trash2 class="w-4 h-4" />
            </Button>
          </div>
        </div>
        
        <div class="mt-3 flex items-center gap-2 text-xs text-muted-foreground">
          <span class="bg-secondary px-2 py-0.5 rounded">{{ cat.posts_count || 0 }} post</span>
        </div>
      </Card>
    </div>

    <!-- Empty State -->
    <div v-if="!loading && filteredCategories.length === 0" class="text-center py-12">
      <p class="text-muted-foreground">Belum ada kategori</p>
      <Button variant="outline" class="mt-4" @click="openModal()">
        <Plus class="w-4 h-4 mr-2" />
        Buat Kategori Pertama
      </Button>
    </div>

    <!-- Modal -->
    <div 
      v-if="showModal" 
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
      @click.self="closeModal"
    >
      <Card class="w-full max-w-lg max-h-[90vh] overflow-hidden m-4 flex flex-col">
        <div class="p-6 flex-shrink-0">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">
              {{ editingId ? 'Edit Kategori' : 'Kategori Baru' }}
            </h2>
            <Button variant="ghost" size="icon" @click="closeModal">
              <X class="w-4 h-4" />
            </Button>
          </div>
        </div>

        <!-- Scrollable Content with Custom Scrollbar -->
        <div class="flex-1 overflow-y-auto px-6 pb-6 scrollbar-thin scrollbar-thumb-primary/30 scrollbar-track-transparent hover:scrollbar-thumb-primary/50">
          <div class="space-y-4">
            <!-- Name -->
            <div class="space-y-2">
              <Label>Nama Kategori</Label>
              <Input 
                v-model="form.name" 
                placeholder="Nama kategori..."
                @blur="generateSlug"
              />
            </div>

            <!-- Slug -->
            <div class="space-y-2">
              <Label>Slug</Label>
              <Input 
                v-model="form.slug" 
                placeholder="nama-kategori"
              />
            </div>

            <!-- Description -->
            <div class="space-y-2">
              <Label>Deskripsi</Label>
              <textarea 
                v-model="form.description" 
                rows="3"
                class="w-full px-3 py-2 rounded-md border border-input bg-background text-sm resize-none"
                placeholder="Deskripsi singkat kategori..."
              ></textarea>
            </div>

            <!-- SEO Section -->
            <div class="pt-4 border-t">
              <h3 class="text-sm font-semibold mb-3">SEO Meta</h3>
              
              <div class="space-y-3">
                <div class="space-y-2">
                  <Label class="text-xs">Meta Title</Label>
                  <Input 
                    v-model="form.meta_title" 
                    placeholder="Meta title untuk SEO"
                    class="text-sm"
                  />
                  <p class="text-xs text-muted-foreground">{{ form.meta_title?.length || 0 }}/70 karakter</p>
                </div>

                <div class="space-y-2">
                  <Label class="text-xs">Meta Description</Label>
                  <textarea 
                    v-model="form.meta_description" 
                    rows="3"
                    class="w-full px-3 py-2 rounded-md border border-input bg-background text-sm resize-none"
                    placeholder="Meta description untuk SEO..."
                  ></textarea>
                  <p class="text-xs text-muted-foreground">{{ form.meta_description?.length || 0 }}/160 karakter</p>
                </div>

                <!-- Robot Meta -->
                <div class="space-y-2 pt-2">
                  <Label class="text-xs">Crawl Bot</Label>
                  <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                      <input 
                        type="radio" 
                        v-model="form.robot_meta" 
                        value="index"
                        class="w-4 h-4 text-primary"
                      >
                      <div>
                        <span class="text-sm font-medium">Index</span>
                        <span class="text-xs text-muted-foreground block">Bot boleh crawl</span>
                      </div>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                      <input 
                        type="radio" 
                        v-model="form.robot_meta" 
                        value="noindex"
                        class="w-4 h-4 text-primary"
                      >
                      <div>
                        <span class="text-sm font-medium">No Index</span>
                        <span class="text-xs text-muted-foreground block">Bot tidak crawl</span>
                      </div>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Fixed Footer -->
        <div class="p-6 border-t bg-background flex-shrink-0 flex justify-end gap-2">
          <Button variant="outline" @click="closeModal">Batal</Button>
          <Button @click="saveCategory">
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

/* Hide scrollbar when not hovering but keep functionality */
.scrollbar-thin {
  scrollbar-gutter: stable;
}
</style>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { Card, Button, Input } from '../components/ui'
import { Plus, Search, Trash2, Loader2, ChevronDown, Eye, Image as ImageIcon } from 'lucide-vue-next'
import axios from 'axios'

const API_URL = 'https://lanyardbagus.com'
const router = useRouter()

const pages = ref([])
const loading = ref(true)
const searchQuery = ref('')
const filterStatus = ref('all')

onMounted(async () => {
  try {
    const res = await axios.get(`${API_URL}/api/pages`)
    pages.value = res.data || []
  } catch (err) {
    console.error('Failed to load pages:', err)
  } finally {
    loading.value = false
  }
})

const filteredPages = computed(() => {
  let result = pages.value
  
  if (searchQuery.value) {
    result = result.filter(p => 
      p.title.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }
  
  if (filterStatus.value === 'published') {
    result = result.filter(p => p.status === 'published')
  } else if (filterStatus.value === 'draft') {
    result = result.filter(p => p.status === 'draft')
  }
  
  return result.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at))
})

function getStatusBadgeClass(status) {
  return status === 'published' 
    ? 'bg-foreground text-background' 
    : 'bg-muted text-muted-foreground'
}

function goToEdit(page) {
  window.location.href = `/dashboard/pages/edit/${page.id}`
}

function goToView(page, event) {
  if (event) event.stopPropagation()
  window.location.href = `/dashboard/pages/edit/${page.id}`
}

async function deletePage(id, event) {
  event.stopPropagation()
  if (!confirm('Hapus halaman ini?')) return
  try {
    await axios.delete(`${API_URL}/api/admin/pages/${id}`, { withCredentials: true })
    pages.value = pages.value.filter(p => p.id !== id)
  } catch (err) {
    alert('Gagal menghapus: ' + err.message)
  }
}
</script>

<template>
  <div class="space-y-4">
    <div class="flex items-center justify-between gap-4">
      <div class="flex items-center gap-4 flex-1 max-w-md">
        <Search class="w-5 h-5 text-muted-foreground" />
        <Input 
          v-model="searchQuery" 
          placeholder="Cari halaman..."
          class="flex-1"
        />
      </div>
      
      <div class="flex items-center gap-3">
        <div class="relative">
          <select 
            v-model="filterStatus" 
            class="appearance-none bg-background border border-input rounded-md px-3 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
          >
            <option value="all">Semua</option>
            <option value="published">Dipublikasikan</option>
            <option value="draft">Draf</option>
          </select>
          <ChevronDown class="w-4 h-4 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-muted-foreground" />
        </div>
        
        <a href="/dashboard/pages/new">
          <Button>
            <Plus class="w-4 h-4 mr-2" />
            Halaman Baru
          </Button>
        </a>
      </div>
    </div>

    <Card>
      <div v-if="loading" class="p-8 text-center">
        <Loader2 class="w-6 h-6 animate-spin mx-auto text-primary" />
      </div>
      <div v-else-if="filteredPages.length === 0" class="p-8 text-center text-muted-foreground">
        Tidak ada halaman
      </div>
      <table v-else class="w-full">
        <thead class="border-b bg-muted/50">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-medium text-muted-foreground">Judul</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-muted-foreground">Slug</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-muted-foreground">Status</th>
            <th class="px-6 py-3 text-right text-sm font-medium text-muted-foreground">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr
            v-for="page in filteredPages"
            :key="page.id"
            class="hover:bg-muted/50 transition-colors group"
          >
            <td class="px-0 py-0 font-medium">
              <a :href="`/dashboard/pages/edit/${page.id}`" class="flex items-center gap-3 px-6 py-4 w-full h-full">
                <div v-if="page.featured_image" class="w-10 h-10 rounded border bg-muted flex-shrink-0 overflow-hidden">
                  <img :src="page.featured_image.url" class="w-full h-full object-cover" />
                </div>
                <div v-else class="w-10 h-10 rounded border bg-muted flex items-center justify-center flex-shrink-0">
                  <ImageIcon class="w-4 h-4 text-muted-foreground" />
                </div>
                <span>{{ page.title }}</span>
              </a>
            </td>
            <td class="px-0 py-0 text-muted-foreground font-mono text-sm">
              <a :href="`/dashboard/pages/edit/${page.id}`" class="block px-6 py-4 w-full h-full">
                /{{ page.slug }}
              </a>
            </td>
            <td class="px-0 py-0">
              <a :href="`/dashboard/pages/edit/${page.id}`" class="block px-6 py-4 w-full h-full">
                <span :class="['px-2 py-1 text-xs rounded-full font-medium', getStatusBadgeClass(page.status)]">
                  {{ page.status }}
                </span>
              </a>
            </td>
            <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
              <Button variant="ghost" size="sm" @click.stop="goToView(page, $event)">
                <Eye class="w-4 h-4" />
              </Button>
              <Button
                variant="ghost"
                size="sm"
                @click.stop="deletePage(page.id, $event)"
                class="hover:bg-destructive/10"
              >
                <Trash2 class="w-4 h-4 text-destructive" />
              </Button>
            </td>
          </tr>
        </tbody>
      </table>
    </Card>
  </div>
</template>

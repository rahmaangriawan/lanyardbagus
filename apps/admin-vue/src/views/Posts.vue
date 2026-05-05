<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { Button, Card, Input } from '../components/ui'
import { Plus, Search, Trash2, Loader2, ChevronDown, Eye, Copy, Check } from 'lucide-vue-next'
import axios from 'axios'

const API_URL = 'https://lanyardbagus.com'
const router = useRouter()

const posts = ref([])
const loading = ref(true)
const searchQuery = ref('')
const filterStatus = ref('all') // all, published, draft, newest, oldest

onMounted(async () => {
  try {
    // Pakai endpoint admin agar bisa melihat semua post (termasuk DRAFT)
    const res = await axios.get(`${API_URL}/api/admin/posts`, { withCredentials: true })
    posts.value = res.data || []
  } catch (err) {
    console.error('Failed to load posts:', err)
  } finally {
    loading.value = false
  }
})

const copiedSlug = ref(null)

const filteredPosts = computed(() => {
  let result = posts.value
  
  // Filter by search
  if (searchQuery.value) {
    result = result.filter(p => 
      p.title.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }
  
  // Filter by status (case-insensitive karena DB simpan uppercase: PUBLISHED, DRAFT)
  if (filterStatus.value === 'published') {
    result = result.filter(p => p.status?.toUpperCase() === 'PUBLISHED')
  } else if (filterStatus.value === 'draft') {
    result = result.filter(p => p.status?.toUpperCase() === 'DRAFT')
  }
  
  // Sort
  if (filterStatus.value === 'oldest') {
    result = [...result].sort((a, b) => new Date(a.created_at) - new Date(b.created_at))
  } else {
    // newest (default)
    result = [...result].sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
  }
  
  return result
})

function getPublicUrl(slug) {
  return `${API_URL}/blog/${slug}`
}

async function copySlug(slug, postId) {
  try {
    const fullUrl = getPublicUrl(slug)
    await navigator.clipboard.writeText(fullUrl)
    copiedSlug.value = postId
    setTimeout(() => copiedSlug.value = null, 2000)
  } catch (err) {
    console.error('Failed to copy:', err)
  }
}

const filterOptions = [
  { value: 'newest', label: 'Terbaru' },
  { value: 'oldest', label: 'Terlama' },
  { value: 'published', label: 'Dipublikasikan' },
  { value: 'draft', label: 'Draf' },
]

const currentFilterLabel = computed(() => {
  return filterOptions.find(opt => opt.value === filterStatus.value)?.label || 'Filter'
})

function getStatusBadgeClass(status) {
  return status?.toUpperCase() === 'PUBLISHED'
    ? 'bg-foreground text-background' 
    : 'bg-muted text-muted-foreground'
}

function goToEdit(post) {
  window.location.href = `/dashboard/posts/edit/${post.id}`
}

function goToView(post, event) {
  if (event) event.stopPropagation()
  window.location.href = `/dashboard/posts/edit/${post.id}`
}

async function deletePost(id, event) {
  event.stopPropagation()
  if (!confirm('Hapus post ini?')) return
  try {
    // Pakai endpoint admin untuk delete
    await axios.delete(`${API_URL}/api/admin/posts/${id}`, { withCredentials: true })
    posts.value = posts.value.filter(p => p.id !== id)
  } catch (err) {
    alert('Gagal menghapus: ' + err.message)
  }
}
</script>

<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between gap-4">
      <div class="flex items-center gap-4 flex-1 max-w-md">
        <Search class="w-5 h-5 text-muted-foreground" />
        <Input 
          v-model="searchQuery" 
          placeholder="Cari post..."
          class="flex-1"
        />
      </div>
      
      <div class="flex items-center gap-3">
        <!-- Filter Dropdown -->
        <div class="relative">
          <select 
            v-model="filterStatus" 
            class="appearance-none bg-background border border-input rounded-md px-3 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
          >
            <option value="newest">Terbaru</option>
            <option value="oldest">Terlama</option>
            <option value="published">Dipublikasikan</option>
            <option value="draft">Draf</option>
          </select>
          <ChevronDown class="w-4 h-4 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-muted-foreground" />
        </div>
        
        <a href="/dashboard/posts/new">
          <Button>
            <Plus class="w-4 h-4 mr-2" />
            Post Baru
          </Button>
        </a>
      </div>
    </div>

    <Card>
      <div v-if="loading" class="p-8 text-center">
        <Loader2 class="w-6 h-6 animate-spin mx-auto text-primary" />
      </div>
      <div v-else-if="filteredPosts.length === 0" class="p-8 text-center text-muted-foreground">
        Tidak ada post
      </div>
      <table v-else class="w-full">
        <thead class="border-b bg-muted/50">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-medium text-muted-foreground">Judul</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-muted-foreground">Kategori</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-muted-foreground">Status</th>
            <th class="px-6 py-3 text-right text-sm font-medium text-muted-foreground">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr
            v-for="post in filteredPosts"
            :key="post.id"
            class="hover:bg-muted/50 transition-colors group"
          >
            <td class="px-0 py-0">
              <a :href="`/dashboard/posts/edit/${post.id}`" class="block px-6 py-4 w-full h-full">
                <div class="font-medium">{{ post.title }}</div>
                <div class="flex items-center gap-1.5 mt-1">
                  <a
                    :href="getPublicUrl(post.slug)"
                    target="_blank"
                    rel="noopener noreferrer"
                    @click.stop
                    class="text-xs text-muted-foreground hover:text-primary hover:underline"
                    title="Buka di frontend"
                  >
                    /{{ post.slug }}
                  </a>
                  <button
                    @click.prevent.stop="copySlug(post.slug, post.id)"
                    class="p-0.5 rounded hover:bg-muted transition-colors"
                    title="Salin link"
                  >
                    <Check v-if="copiedSlug === post.id" class="w-3 h-3 text-green-500" />
                    <Copy v-else class="w-3 h-3 text-muted-foreground" />
                  </button>
                </div>
              </a>
            </td>
            <td class="px-0 py-0 text-muted-foreground">
              <a :href="`/dashboard/posts/edit/${post.id}`" class="block px-6 py-4 w-full h-full">
                {{ post.category?.name || '-' }}
              </a>
            </td>
            <td class="px-0 py-0">
              <a :href="`/dashboard/posts/edit/${post.id}`" class="block px-6 py-4 w-full h-full">
                <span :class="['px-2 py-1 text-xs rounded-full font-medium', getStatusBadgeClass(post.status)]">
                  {{ post.status }}
                </span>
              </a>
            </td>
            <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
              <Button variant="ghost" size="sm" @click.stop="goToView(post, $event)">
                <Eye class="w-4 h-4" />
              </Button>
              <Button
                variant="ghost"
                size="sm"
                @click.stop="deletePost(post.id, $event)"
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


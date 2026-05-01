<script setup>
import { ref, onMounted } from 'vue'
import { Button, Card } from '../components/ui'
import { 
  FileText, 
  FileStack, 
  Image, 
  Users, 
  Plus,
  ArrowRight,
  Loader2
} from 'lucide-vue-next'
import axios from 'axios'

const API_URL = 'https://lanyardbagus.com'

const stats = ref({
  posts: 0,
  pages: 0,
  media: 0,
  users: 1
})

const recentPosts = ref([])
const loading = ref(true)

const statItems = [
  { key: 'posts', label: 'Total Posts', icon: FileText, color: 'bg-foreground' },
  { key: 'pages', label: 'Total Pages', icon: FileStack, color: 'bg-muted-foreground' },
  { key: 'media', label: 'Media Files', icon: Image, color: 'bg-foreground' },
  { key: 'users', label: 'Users', icon: Users, color: 'bg-muted-foreground' },
]

const quickActions = [
  { label: 'Post Baru', icon: Plus, to: '/posts/new', variant: 'default' },
  { label: 'Page Baru', icon: Plus, to: '/pages/new', variant: 'outline' },
  { label: 'Kelola Media', icon: Image, to: '/media', variant: 'outline' },
  { label: 'Pengaturan', icon: FileStack, to: '/settings', variant: 'outline' },
]

onMounted(async () => {
  try {
    const [postsRes, pagesRes, mediaRes] = await Promise.all([
      axios.get(`${API_URL}/api/admin/posts`, { withCredentials: true }).catch(() => ({ data: [] })),
      axios.get(`${API_URL}/api/pages`, { withCredentials: true }).catch(() => ({ data: [] })),
      axios.get(`${API_URL}/api/media`, { withCredentials: true }).catch(() => ({ data: [] })),
    ])
    
    stats.value.posts = postsRes.data.length || 0
    stats.value.pages = pagesRes.data.length || 0
    stats.value.media = mediaRes.data.length || 0
    
    recentPosts.value = (postsRes.data || []).slice(0, 3)
  } catch (err) {
    console.error('Failed to load stats:', err)
  } finally {
    loading.value = false
  }
})

function formatDate(date) {
  return new Date(date).toLocaleDateString('id-ID', { 
    day: 'numeric', 
    month: 'short', 
    year: 'numeric' 
  })
}

function getStatusBadgeClass(status) {
  return status?.toUpperCase() === 'PUBLISHED'
    ? 'bg-foreground text-background' 
    : 'bg-muted text-muted-foreground'
}
</script>

<template>
  <div v-if="loading" class="flex items-center justify-center h-64">
    <Loader2 class="w-8 h-8 animate-spin text-primary" />
  </div>
  
  <div v-else class="space-y-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <Card v-for="item in statItems" :key="item.key" class="p-6 flex items-center gap-4 border">
        <div :class="['w-12 h-12 rounded-xl flex items-center justify-center', item.color]">
          <component :is="item.icon" class="w-6 h-6 text-background" />
        </div>
        <div>
          <p class="text-2xl font-bold text-card-foreground">{{ stats[item.key] }}</p>
          <p class="text-sm text-muted-foreground">{{ item.label }}</p>
        </div>
      </Card>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
      <!-- Recent Posts -->
      <div class="md:col-span-2 space-y-4">
        <div class="flex items-center justify-between">
          <h3 class="font-semibold text-card-foreground">Posts Terbaru</h3>
          <router-link to="/posts" class="text-sm text-primary hover:underline flex items-center gap-1">
            Lihat Semua
            <ArrowRight class="w-4 h-4" />
          </router-link>
        </div>
        
        <Card>
          <div v-if="recentPosts.length === 0" class="p-8 text-center text-muted-foreground">
            Belum ada post
          </div>
          <div v-else class="divide-y">
            <div 
              v-for="post in recentPosts" 
              :key="post.id"
              class="p-4 flex items-center justify-between hover:bg-accent/50 transition"
            >
              <div>
                <p class="font-medium text-card-foreground">{{ post.title }}</p>
                <p class="text-sm text-muted-foreground">{{ formatDate(post.created_at) }}</p>
              </div>
              <span 
                :class="['px-2 py-1 text-xs rounded-full font-medium', getStatusBadgeClass(post.status)]"
              >
                {{ post.status }}
              </span>
            </div>
          </div>
        </Card>
      </div>
      
      <!-- Quick Actions -->
      <div class="space-y-4">
        <h3 class="font-semibold text-card-foreground">Aksi Cepat</h3>
        <Card class="p-4 space-y-2">
          <router-link 
            v-for="action in quickActions" 
            :key="action.to"
            :to="action.to"
          >
            <Button 
              :variant="action.variant" 
              class="w-full justify-start"
            >
              <component :is="action.icon" class="w-4 h-4 mr-2" />
              {{ action.label }}
            </Button>
          </router-link>
        </Card>
      </div>
    </div>
  </div>
</template>

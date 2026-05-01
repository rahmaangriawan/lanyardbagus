<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { Card, Button, Input, Label } from '../components/ui'
import WysiwygEditor from '../components/WysiwygEditor.vue'
import { ArrowLeft, Save, Loader2, Eye, Copy, Check, X } from 'lucide-vue-next'
import axios from 'axios'

const API_URL = 'https://lanyardbagus.com'
const route = useRoute()
const router = useRouter()

const isEdit = computed(() => !!route.params.id)
const pageId = computed(() => route.params.id)

const page = ref({
  title: '',
  slug: '',
  content: '',
  status: 'draft',
  meta_title: '',
  meta_description: '',
  robot_meta: 'index' // 'index' or 'noindex'
})

const loading = ref(false)
const saving = ref(false)
const showMediaPicker = ref(false)
const copySuccess = ref(false)

// Resizable sidebar widths
const leftSidebarWidth = ref(280)
const rightSidebarWidth = ref(320)
const isResizingLeft = ref(false)
const isResizingRight = ref(false)

let startXLeft = 0
let startWidthLeft = 0

function startResizeLeft(e) {
  isResizingLeft.value = true
  startXLeft = e.clientX
  startWidthLeft = leftSidebarWidth.value
  document.addEventListener('mousemove', onResizeLeft)
  document.addEventListener('mouseup', stopResizeLeft)
}

function onResizeLeft(e) {
  if (!isResizingLeft.value) return
  const newWidth = startWidthLeft + (e.clientX - startXLeft)
  if (newWidth >= 200 && newWidth <= 500) {
    leftSidebarWidth.value = newWidth
  }
}

function stopResizeLeft() {
  isResizingLeft.value = false
  document.removeEventListener('mousemove', onResizeLeft)
  document.removeEventListener('mouseup', stopResizeLeft)
}

let startXRight = 0
let startWidthRight = 0

function startResizeRight(e) {
  isResizingRight.value = true
  startXRight = e.clientX
  startWidthRight = rightSidebarWidth.value
  document.addEventListener('mousemove', onResizeRight)
  document.addEventListener('mouseup', stopResizeRight)
}

function onResizeRight(e) {
  if (!isResizingRight.value) return
  const newWidth = startWidthRight + (startXRight - e.clientX)
  if (newWidth >= 250 && newWidth <= 500) {
    rightSidebarWidth.value = newWidth
  }
}

function stopResizeRight() {
  isResizingRight.value = false
  document.removeEventListener('mousemove', onResizeRight)
  document.removeEventListener('mouseup', stopResizeRight)
}

function copyUrl() {
  const url = `${window.location.origin}/pages/${page.value.slug}`
  navigator.clipboard.writeText(url)
  copySuccess.value = true
  setTimeout(() => copySuccess.value = false, 2000)
}

onMounted(async () => {
  if (isEdit.value) {
    await loadPage()
  }
})

async function loadPage() {
  loading.value = true
  try {
    const res = await axios.get(`${API_URL}/api/admin/pages/${pageId.value}`, { withCredentials: true })
    page.value = res.data
  } catch (err) {
    console.error('Failed to load page:', err)
    alert('Gagal memuat halaman')
  } finally {
    loading.value = false
  }
}

function generateSlug() {
  if (!page.value.title) return
  page.value.slug = page.value.title
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-|-$/g, '')
}

async function savePage() {
  saving.value = true
  try {
    if (isEdit.value) {
      await axios.put(`${API_URL}/api/admin/pages/${pageId.value}`, page.value, { withCredentials: true })
    } else {
      await axios.post(`${API_URL}/api/admin/pages`, page.value, { withCredentials: true })
    }
    router.push('/pages')
  } catch (err) {
    alert('Gagal menyimpan: ' + (err.response?.data?.message || err.message))
  } finally {
    saving.value = false
  }
}

function viewPage() {
  if (!page.value.slug) return
  const url = `${API_URL}/${page.value.slug}`
  window.open(url, '_blank')
}
</script>

<template>
  <div class="h-full flex flex-col">
    <!-- Header -->
    <div class="flex items-center justify-between px-4 py-3 border-b bg-background">
      <div class="flex items-center gap-4">
        <Button variant="ghost" size="icon" @click="$router.back()">
          <ArrowLeft class="w-5 h-5" />
        </Button>
        <h2 class="text-xl font-semibold">{{ isEdit ? 'Edit Halaman' : 'Halaman Baru' }}</h2>
      </div>
      <div class="flex items-center gap-3">
        <Button v-if="isEdit" variant="outline" size="sm" @click="viewPage">
          <Eye class="w-4 h-4 mr-2" />
          Lihat
        </Button>
        <Button @click="savePage" :disabled="saving">
          <Loader2 v-if="saving" class="w-4 h-4 mr-2 animate-spin" />
          <Save v-else class="w-4 h-4 mr-2" />
          {{ saving ? 'Menyimpan...' : 'Simpan' }}
        </Button>
      </div>
    </div>

    <div v-if="loading" class="flex-1 flex items-center justify-center">
      <Loader2 class="w-6 h-6 animate-spin" />
    </div>

    <div v-else class="flex overflow-hidden flex-1">
      <!-- Left Sidebar - Actions & SEO (Resizable) -->
      <div 
        class="border-r min-h-0 p-4 space-y-6 overflow-y-auto flex-shrink-0"
        :style="{ width: leftSidebarWidth + 'px' }"
      >
        <!-- Actions -->
        <div>
          <h3 class="text-xs font-semibold text-muted-foreground uppercase mb-3">AKSI</h3>
          <div class="flex gap-2">
            <Button v-if="isEdit" variant="outline" class="flex-1 justify-center" size="sm" @click="viewPage">
              <Eye class="w-4 h-4 mr-1" />
              Lihat
            </Button>
            <Button v-if="isEdit" variant="outline" class="flex-1 justify-center" size="sm" @click="copyUrl">
              <Copy v-if="!copySuccess" class="w-4 h-4 mr-1" />
              <Check v-else class="w-4 h-4 mr-1" />
              {{ copySuccess ? 'Tersalin!' : 'Salin URL' }}
            </Button>
          </div>
        </div>

        <!-- URL Permalink -->
        <div v-if="isEdit">
          <h3 class="text-xs font-semibold text-muted-foreground uppercase mb-2">URL PERMALINK</h3>
          <div class="flex items-center gap-1 text-xs text-muted-foreground">
            <span>/pages/</span>
            <Input v-model="page.slug" class="h-7 text-xs" @blur="generateSlug" />
          </div>
        </div>

        <!-- SEO -->
        <div>
          <h3 class="text-xs font-semibold text-muted-foreground uppercase mb-2">SEO</h3>
          <div class="space-y-3">
            <div class="space-y-1">
              <Label class="text-xs">SEO Title</Label>
              <Input v-model="page.meta_title" class="h-8 text-xs" placeholder="Meta title" />
              <p class="text-xs text-muted-foreground">{{ page.meta_title?.length || 0 }}/70</p>
            </div>
            <div class="space-y-1">
              <Label class="text-xs">Meta Description</Label>
              <textarea 
                v-model="page.meta_description" 
                rows="6"
                class="w-full px-2 py-2 rounded-md border border-input bg-background text-xs resize-none leading-relaxed"
                placeholder="Meta description untuk SEO"
              ></textarea>
              <p class="text-xs text-muted-foreground">{{ page.meta_description?.length || 0 }}/160 karakter</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Resize Handle Left -->
      <div 
        class="w-2 cursor-col-resize hover:bg-foreground/20 active:bg-foreground/40 transition-colors flex-shrink-0 relative group"
        @mousedown="startResizeLeft"
        title="Drag untuk resize sidebar"
      >
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-0.5 h-8 bg-muted-foreground/30 group-hover:bg-muted-foreground/50 rounded"></div>
      </div>

      <!-- Main Content Editor -->
      <div class="flex-1 p-6 overflow-y-auto min-h-0">
        <div class="max-w-4xl mx-auto space-y-6">
          <!-- Title Input -->
          <div>
            <Label class="text-xs text-muted-foreground uppercase">JUDUL</Label>
            <Input 
              v-model="page.title" 
              placeholder="Judul halaman..."
              class="text-lg font-medium mt-1"
              @blur="generateSlug"
            />
          </div>

          <!-- Slug -->
          <div>
            <Label class="text-xs text-muted-foreground uppercase">SLUG</Label>
            <Input 
              v-model="page.slug" 
              placeholder="url-slug-halaman"
              class="mt-1"
            />
          </div>

          <!-- Rich Editor -->
          <div>
            <Label class="text-xs text-muted-foreground uppercase mb-2 block">KONTEN</Label>
            <WysiwygEditor v-model="page.content" />
          </div>
        </div>
      </div>

      <!-- Resize Handle Right -->
      <div 
        class="w-2 cursor-col-resize hover:bg-foreground/20 active:bg-foreground/40 transition-colors flex-shrink-0 relative group"
        @mousedown="startResizeRight"
        title="Drag untuk resize sidebar"
      >
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-0.5 h-8 bg-muted-foreground/30 group-hover:bg-muted-foreground/50 rounded"></div>
      </div>

      <!-- Right Sidebar - Visibility (Resizable) -->
      <div 
        class="border-l min-h-0 p-4 space-y-6 overflow-y-auto bg-muted/30 flex-shrink-0"
        :style="{ width: rightSidebarWidth + 'px' }"
      >
        <!-- Visibility -->
        <div>
          <h3 class="text-xs font-semibold text-muted-foreground uppercase mb-2">VISIBILITY</h3>
          <Card class="p-2">
            <div class="space-y-1">
              <label 
                class="flex items-start gap-3 cursor-pointer p-2 rounded-md hover:bg-secondary transition-colors"
              >
                <input type="radio" v-model="page.status" value="DRAFT" class="mt-0.5 w-4 h-4 text-primary border-border focus:ring-primary">
                <div>
                  <span class="text-sm font-medium block">Draft</span>
                  <span class="text-xs text-muted-foreground">Hanya tampil di admin</span>
                </div>
              </label>
              <label 
                class="flex items-start gap-3 cursor-pointer p-2 rounded-md hover:bg-secondary transition-colors"
              >
                <input type="radio" v-model="page.status" value="PUBLISHED" class="mt-0.5 w-4 h-4 text-primary border-border focus:ring-primary">
                <div>
                  <span class="text-sm font-medium block">Published</span>
                  <span class="text-xs text-muted-foreground">Tampil di publik</span>
                </div>
              </label>
            </div>
          </Card>
        </div>

        <!-- Robot Meta -->
        <div>
          <h3 class="text-xs font-semibold text-muted-foreground uppercase mb-2">ROBOT META</h3>
          <Card class="p-2">
            <div class="space-y-1">
              <label 
                :class="[
                  'flex items-start gap-3 cursor-pointer p-2 rounded-md transition-colors',
                  page.robot_meta === 'index' ? 'bg-primary/10' : 'hover:bg-secondary'
                ]"
              >
                <input type="radio" v-model="page.robot_meta" value="index" class="w-4 h-4 accent-primary mt-0.5">
                <div>
                  <span :class="['text-sm font-medium block', page.robot_meta === 'index' ? 'text-primary' : '']">Index</span>
                  <span :class="['text-xs', page.robot_meta === 'index' ? 'text-primary/70' : 'text-muted-foreground']">Bot boleh crawl halaman ini</span>
                </div>
              </label>
              <label 
                :class="[
                  'flex items-start gap-3 cursor-pointer p-2 rounded-md transition-colors',
                  page.robot_meta === 'noindex' ? 'bg-primary/10' : 'hover:bg-secondary'
                ]"
              >
                <input type="radio" v-model="page.robot_meta" value="noindex" class="w-4 h-4 accent-primary mt-0.5">
                <div>
                  <span :class="['text-sm font-medium block', page.robot_meta === 'noindex' ? 'text-primary' : '']">No Index</span>
                  <span :class="['text-xs', page.robot_meta === 'noindex' ? 'text-primary/70' : 'text-muted-foreground']">Bot tidak boleh crawl</span>
                </div>
              </label>
            </div>
          </Card>
        </div>
      </div>
    </div>
  </div>
</template>

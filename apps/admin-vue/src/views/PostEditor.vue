<script setup>
import { ref, onMounted, computed, watch, onBeforeUnmount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { Card, Button, Input, Label, Alert, AlertTitle, AlertDescription } from '../components/ui'
import WysiwygEditor from '../components/WysiwygEditor.vue'
import MediaPickerModal from '../components/MediaPickerModal.vue'
import { ArrowLeft, Save, Loader2, Image as ImageIcon, Calendar, Clock, Copy, Check, X, CheckCircle2, AlertCircle, Info, Eye } from 'lucide-vue-next'
import axios from 'axios'

const API_URL = 'https://lanyardbagus.com'
const route = useRoute()
const router = useRouter()

const isEdit = computed(() => !!route.params.id)
const postId = computed(() => route.params.id)

const post = ref({
  title: '',
  slug: '',
  content: '',
  excerpt: '',
  status: 'draft',
  category_id: null,
  featured_image: null,
  published_at: null,
  meta_title: '',
  meta_description: '',
  tags_input: ''
})

const categories = ref([])
const tags = ref([])
const showCategoryModal = ref(false)
const showTagModal = ref(false)
const categorySearch = ref('')
const tagSearch = ref('')
const editingCategoryId = ref(null)
const editingTagId = ref(null)
const categorySaving = ref(false)
const tagSaving = ref(false)
const categoryForm = ref({
  name: '',
  slug: '',
  description: '',
  seo_title: '',
  meta_description: ''
})
const tagForm = ref({
  name: '',
  slug: ''
})
const loading = ref(false)
const saving = ref(false)
const showMediaPicker = ref(false)
const showEditorMediaPicker = ref(false)
const copySuccess = ref(false)

const isDirty = ref(false)
const saveStatus = ref(null)

const filteredCategories = computed(() => {
  if (!categorySearch.value) return categories.value
  const term = categorySearch.value.toLowerCase()
  return categories.value.filter(category => {
    return [category.name, category.slug, category.description]
      .filter(Boolean)
      .some(field => String(field).toLowerCase().includes(term))
  })
})

const filteredTags = computed(() => {
  if (!tagSearch.value) return tags.value
  const term = tagSearch.value.toLowerCase()
  return tags.value.filter(tag => {
    return [tag.name, tag.slug]
      .filter(Boolean)
      .some(field => String(field).toLowerCase().includes(term))
  })
})

// Watch for unsaved changes
watch(post, (newVal, oldVal) => {
  if (oldVal && oldVal.title !== undefined && saveStatus.value?.type !== 'success') {
    isDirty.value = true
  }
}, { deep: true })

// Browser tab close/refresh guard
function handleBeforeUnload(e) {
  if (isDirty.value) {
    e.preventDefault()
    e.returnValue = ''
  }
}

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

onMounted(async () => {
  window.addEventListener('beforeunload', handleBeforeUnload)
  
  await loadCategories()
  await loadTags()
  if (isEdit.value) {
    await loadPost()
    // Reset dirty state after initial load
    setTimeout(() => { isDirty.value = false }, 500)
  }
})

onBeforeUnmount(() => {
  window.removeEventListener('beforeunload', handleBeforeUnload)
})

// Vue Router navigation guard
onBeforeRouteLeave((to, from, next) => {
  if (isDirty.value) {
    const answer = window.confirm('Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman ini?')
    if (answer) {
      next()
    } else {
      next(false)
    }
  } else {
    next()
  }
})

async function loadCategories() {
  try {
    const res = await axios.get(`${API_URL}/api/categories`)
    categories.value = res.data || []
  } catch (err) {
    console.error('Failed to load categories:', err)
  }
}

async function loadTags() {
  try {
    const res = await axios.get(`${API_URL}/api/tags`)
    tags.value = res.data || []
  } catch (err) {
    console.error('Failed to load tags:', err)
  }
}

function formatDateTimeLocal(dateString) {
  if (!dateString) return null
  const date = new Date(dateString)
  if (isNaN(date.getTime())) return null
  
  const yyyy = date.getFullYear()
  const mm = String(date.getMonth() + 1).padStart(2, '0')
  const dd = String(date.getDate()).padStart(2, '0')
  const hh = String(date.getHours()).padStart(2, '0')
  const min = String(date.getMinutes()).padStart(2, '0')
  
  return `${yyyy}-${mm}-${dd}T${hh}:${min}`
}

async function loadPost() {
  loading.value = true
  try {
    const res = await axios.get(`${API_URL}/api/admin/posts/${postId.value}`, { withCredentials: true })
    post.value = {
      ...res.data,
      // Convert featuredImage to featured_image for frontend
      featured_image: res.data.featured_image || res.data.featuredImage || null,
      published_at: formatDateTimeLocal(res.data.published_at)
    }
    post.value.tags_input = (res.data?.tags || []).map(tag => tag.name).join(', ')
  } catch (err) {
    console.error('Failed to load post:', err)
    alert('Gagal memuat post')
  } finally {
    loading.value = false
  }
}

function generateSlug() {
  if (!post.value.title) return
  post.value.slug = post.value.title
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-|-$/g, '')
}

function normalizeTagName(name) {
  return String(name || '').trim()
}

function parseTagsInput() {
  return [...new Set(
    (post.value.tags_input || '')
      .split(',')
      .map(normalizeTagName)
      .filter(Boolean)
  )]
}

function findTagByName(name) {
  const normalized = normalizeTagName(name).toLowerCase()
  return tags.value.find(tag => tag.name?.toLowerCase() === normalized || tag.slug?.toLowerCase() === normalized)
}

async function resolveTagIdsFromInput() {
  const names = parseTagsInput()
  const ids = []

  for (const name of names) {
    const existing = findTagByName(name)
    if (existing) {
      ids.push(existing.id)
      continue
    }

    const res = await axios.post(`${API_URL}/api/admin/tags`, { name }, { withCredentials: true })
    const created = res.data
    tags.value.unshift(created)
    ids.push(created.id)
  }

  return ids
}

async function savePost() {
  saving.value = true
  saveStatus.value = null
  try {
    const payload = {
      ...post.value,
      tag_ids: await resolveTagIdsFromInput(),
      // Convert featured_image object to featured_image_id
      featured_image_id: post.value.featured_image?.id || null
    }
    delete payload.tags_input
    delete payload.featured_image

    let newPostId = postId.value
    if (isEdit.value) {
      await axios.put(`${API_URL}/api/admin/posts/${postId.value}`, payload)
    } else {
      const res = await axios.post(`${API_URL}/api/admin/posts`, payload)
      newPostId = res.data?.id || res.data?.post?.id
    }
    
    isDirty.value = false
    saveStatus.value = { type: 'success', message: 'Post berhasil disimpan!' }
    
    setTimeout(() => { saveStatus.value = null }, 3000)

    if (!isEdit.value && newPostId) {
      router.replace(`/posts/${newPostId}`)
    }
  } catch (err) {
    saveStatus.value = { type: 'error', message: 'Gagal menyimpan: ' + (err.response?.data?.message || err.message) }
  } finally {
    saving.value = false
  }
}

function onMediaSelected(media) {
  post.value.featured_image = media
}

function onEditorMediaSelected(media) {
  if (media?.url) {
    const imgHTML = `<img src="${media.url}" alt="${media.alt_text || ''}" class="max-w-full h-auto rounded-lg my-4">`
    post.value.content += imgHTML
  }
  showEditorMediaPicker.value = false
}

function removeFeaturedImage() {
  post.value.featured_image = null
}

function openCategoryModal(category = null) {
  if (category) {
    editingCategoryId.value = category.id
    categoryForm.value = {
      name: category.name || '',
      slug: category.slug || '',
      description: category.description || '',
      seo_title: category.seo_title || '',
      meta_description: category.meta_description || ''
    }
  } else {
    editingCategoryId.value = null
    categoryForm.value = {
      name: '',
      slug: '',
      description: '',
      seo_title: '',
      meta_description: ''
    }
  }
  showCategoryModal.value = true
}

function closeCategoryModal() {
  showCategoryModal.value = false
  editingCategoryId.value = null
}

function generateCategorySlug() {
  if (!categoryForm.value.name) return
  categoryForm.value.slug = categoryForm.value.name
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-|-$/g, '')
}

async function saveCategory() {
  categorySaving.value = true
  try {
    const payload = { ...categoryForm.value }
    if (editingCategoryId.value) {
      await axios.put(`${API_URL}/api/admin/categories/${editingCategoryId.value}`, payload, { withCredentials: true })
    } else {
      await axios.post(`${API_URL}/api/admin/categories`, payload, { withCredentials: true })
    }
    await loadCategories()
    closeCategoryModal()
  } catch (err) {
    alert('Gagal menyimpan kategori: ' + (err.response?.data?.message || err.message))
  } finally {
    categorySaving.value = false
  }
}

async function deleteCategory(id) {
  if (!confirm('Hapus kategori ini?')) return
  try {
    await axios.delete(`${API_URL}/api/admin/categories/${id}`, { withCredentials: true })
    await loadCategories()
  } catch (err) {
    alert('Gagal menghapus kategori: ' + (err.response?.data?.message || err.message))
  }
}

function openTagModal(tag = null) {
  if (tag) {
    editingTagId.value = tag.id
    tagForm.value = {
      name: tag.name || '',
      slug: tag.slug || ''
    }
  } else {
    editingTagId.value = null
    tagForm.value = {
      name: '',
      slug: ''
    }
  }
  showTagModal.value = true
}

function closeTagModal() {
  showTagModal.value = false
  editingTagId.value = null
}

function generateTagSlug() {
  if (!tagForm.value.name) return
  tagForm.value.slug = tagForm.value.name
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-|-$/g, '')
}

async function saveTag() {
  tagSaving.value = true
  try {
    const payload = { ...tagForm.value }
    if (editingTagId.value) {
      await axios.put(`${API_URL}/api/admin/tags/${editingTagId.value}`, payload, { withCredentials: true })
    } else {
      await axios.post(`${API_URL}/api/admin/tags`, payload, { withCredentials: true })
    }
    await loadTags()
    closeTagModal()
  } catch (err) {
    alert('Gagal menyimpan tag: ' + (err.response?.data?.message || err.message))
  } finally {
    tagSaving.value = false
  }
}

async function deleteTag(id) {
  if (!confirm('Hapus tag ini?')) return
  try {
    await axios.delete(`${API_URL}/api/admin/tags/${id}`, { withCredentials: true })
    await loadTags()
  } catch (err) {
    alert('Gagal menghapus tag: ' + (err.response?.data?.message || err.message))
  }
}

async function copyUrl() {
  const url = `${window.location.origin}/blog/${post.value.slug}`
  try {
    await navigator.clipboard.writeText(url)
    copySuccess.value = true
    setTimeout(() => copySuccess.value = false, 2000)
  } catch (err) {
    console.error('Failed to copy:', err)
  }
}

function viewPost() {
  if (!post.value.slug) return
  const url = `${API_URL}/blog/${post.value.slug}`
  window.open(url, '_blank')
}
</script>

<template>
  <div class="min-h-screen bg-background relative">
    <!-- Alerts (Toast style) -->
    <div class="fixed top-16 right-6 z-50 w-full max-w-sm space-y-2">
      <Alert v-if="saveStatus?.type === 'success'" variant="success" class="shadow-lg backdrop-blur-sm bg-green-50/90 dark:bg-green-900/90">
        <CheckCircle2 class="w-4 h-4" />
        <AlertTitle>Berhasil</AlertTitle>
        <AlertDescription>{{ saveStatus.message }}</AlertDescription>
      </Alert>

      <Alert v-if="saveStatus?.type === 'error'" variant="destructive" class="shadow-lg backdrop-blur-sm bg-destructive/10">
        <AlertCircle class="w-4 h-4" />
        <AlertTitle>Gagal Menyimpan</AlertTitle>
        <AlertDescription>{{ saveStatus.message }}</AlertDescription>
      </Alert>
    </div>

    <!-- Top Header -->
    <div class="h-14 border-b flex items-center justify-between px-4 sticky top-0 bg-background z-20">
      <div class="flex items-center gap-4">
        <Button variant="ghost" size="icon" @click="$router.back()">
          <ArrowLeft class="w-5 h-5" />
        </Button>
        <h1 class="font-semibold">{{ isEdit ? 'Edit Post' : 'Post Baru' }}</h1>
      </div>
      <div class="flex items-center gap-3">
        <Button v-if="isEdit" variant="outline" size="sm" @click="viewPost">
          <Eye class="w-4 h-4 mr-2" />
          Lihat
        </Button>
        <Button size="sm" @click="savePost" :disabled="saving">
          <Loader2 v-if="saving" class="w-4 h-4 mr-2 animate-spin" />
          <Save v-else class="w-4 h-4 mr-2" />
          {{ saving ? 'Menyimpan...' : 'Simpan' }}
        </Button>
      </div>
    </div>

    <div v-if="loading" class="p-12 text-center">
      <Loader2 class="w-6 h-6 animate-spin mx-auto" />
    </div>

    <div v-else class="flex overflow-hidden">
      <!-- Left Sidebar -->
      <div 
        class="border-r min-h-[calc(100vh-56px)] p-4 space-y-6 overflow-y-auto flex-shrink-0"
        :style="{ width: leftSidebarWidth + 'px' }"
      >
        <!-- Actions -->
        <div>
          <h3 class="text-xs font-semibold text-muted-foreground uppercase mb-3">AKSI</h3>
          <div class="flex gap-2">
            <Button v-if="isEdit" variant="outline" class="flex-1 justify-center px-0" size="sm" @click="copyUrl">
              <Copy v-if="!copySuccess" class="w-4 h-4 mr-2" />
              <Check v-else class="w-4 h-4 mr-2" />
              {{ copySuccess ? 'Tersalin!' : 'Salin URL' }}
            </Button>
          </div>
        </div>

        <!-- URL Permalink -->
        <div v-if="isEdit">
          <h3 class="text-xs font-semibold text-muted-foreground uppercase mb-2">URL PERMALINK</h3>
          <div class="flex items-center gap-1 text-xs text-muted-foreground">
            <span>/blog/</span>
            <Input v-model="post.slug" class="h-7 text-xs" @blur="generateSlug" />
          </div>
        </div>

        <!-- Summary -->
        <div>
          <h3 class="text-xs font-semibold text-muted-foreground uppercase mb-2">RINGKASAN</h3>
          <textarea 
            v-model="post.excerpt" 
            rows="4"
            class="w-full px-3 py-2 rounded-md border border-input bg-background text-xs resize-none"
            placeholder="Ringkasan singkat post..."
          ></textarea>
          <p class="text-xs text-muted-foreground mt-1">{{ post.excerpt?.length || 0 }}/160 karakter</p>
        </div>

        <!-- SEO -->
        <div>
          <h3 class="text-xs font-semibold text-muted-foreground uppercase mb-2">SEO</h3>
          <div class="space-y-3">
            <div class="space-y-1">
              <Label class="text-xs">SEO Title</Label>
              <Input v-model="post.meta_title" class="h-8 text-xs" placeholder="Meta title" />
              <p class="text-xs text-muted-foreground">{{ post.meta_title?.length || 0 }}/70</p>
            </div>
            <div class="space-y-1">
              <Label class="text-xs">Meta Description</Label>
              <textarea 
                v-model="post.meta_description" 
                rows="6"
                class="w-full px-2 py-2 rounded-md border border-input bg-background text-xs resize-none leading-relaxed"
                placeholder="Meta description untuk SEO. Deskripsi ini akan muncul di hasil pencarian Google."
              ></textarea>
              <p class="text-xs text-muted-foreground">{{ post.meta_description?.length || 0 }}/160 karakter</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Resize Handle Left -->
      <div 
        class="w-1 cursor-col-resize hover:bg-foreground/20 active:bg-foreground/40 transition-colors flex-shrink-0"
        @mousedown="startResizeLeft"
      ></div>

      <!-- Main Content Editor -->
      <div class="flex-1 p-6 overflow-y-auto min-h-[calc(100vh-56px)]">
        <div class="max-w-4xl mx-auto space-y-6">
          <!-- Title Input -->
          <div>
            <Label class="text-xs text-muted-foreground uppercase">JUDUL</Label>
            <Input 
              v-model="post.title" 
              placeholder="Judul post..."
              class="text-lg font-medium mt-1"
              @blur="generateSlug"
            />
          </div>

          <!-- Rich Editor -->
          <div>
            <Label class="text-xs text-muted-foreground uppercase mb-2 block">KONTEN</Label>
            <WysiwygEditor v-model="post.content" />
          </div>
        </div>
      </div>

      <!-- Resize Handle Right -->
      <div 
        class="w-1 cursor-col-resize hover:bg-foreground/20 active:bg-foreground/40 transition-colors flex-shrink-0"
        @mousedown="startResizeRight"
      ></div>

      <!-- Right Sidebar -->
      <div 
        class="border-l min-h-[calc(100vh-56px)] p-4 space-y-6 overflow-y-auto bg-muted/30 flex-shrink-0"
        :style="{ width: rightSidebarWidth + 'px' }"
      >
        <!-- Category -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <h3 class="text-xs font-semibold text-muted-foreground uppercase">KATEGORI</h3>
            <Button variant="ghost" size="sm" class="h-auto py-0 text-xs text-primary" @click="openCategoryModal">+ Tambah</Button>
          </div>
          <Card class="p-3">
            <div class="space-y-1">
              <label 
                v-for="cat in categories" 
                :key="cat.id" 
                class="flex items-center gap-3 p-2 rounded-md cursor-pointer hover:bg-secondary transition-colors"
              >
                <input 
                  type="radio" 
                  v-model="post.category_id" 
                  :value="cat.id"
                  class="w-4 h-4 text-primary border-border focus:ring-primary"
                >
                <span class="text-sm font-medium">{{ cat.name }}</span>
              </label>
              <label 
                class="flex items-center gap-3 p-2 rounded-md cursor-pointer hover:bg-secondary transition-colors"
              >
                <input 
                  type="radio" 
                  v-model="post.category_id" 
                  :value="null"
                  class="w-4 h-4 text-primary border-border focus:ring-primary"
                >
                <span class="text-sm">Tanpa Kategori</span>
              </label>
            </div>
            <div v-if="categories.length === 0" class="text-sm text-muted-foreground p-2">
              Belum ada kategori
            </div>
          </Card>
        </div>

        <!-- Visibility -->
        <div>
          <h3 class="text-xs font-semibold text-muted-foreground uppercase mb-2">VISIBILITY</h3>
          <Card class="p-2">
            <div class="space-y-1">
              <label 
                class="flex items-start gap-3 cursor-pointer p-2 rounded-md hover:bg-secondary transition-colors"
              >
                <input type="radio" v-model="post.status" value="DRAFT" class="mt-0.5 w-4 h-4 text-primary border-border focus:ring-primary">
                <div>
                  <span class="text-sm font-medium block">Draft</span>
                  <span class="text-xs text-muted-foreground">Hanya tampil di admin</span>
                </div>
              </label>
              <label 
                class="flex items-start gap-3 cursor-pointer p-2 rounded-md hover:bg-secondary transition-colors"
              >
                <input type="radio" v-model="post.status" value="PUBLISHED" class="mt-0.5 w-4 h-4 text-primary border-border focus:ring-primary">
                <div>
                  <span class="text-sm font-medium block">Published</span>
                  <span class="text-xs text-muted-foreground">Tampil di publik</span>
                </div>
              </label>
            </div>
          </Card>
        </div>

        <!-- Featured Image -->
        <div>
          <h3 class="text-xs font-semibold text-muted-foreground uppercase mb-2">FEATURED IMAGE</h3>
          <Card class="p-3">
            <div v-if="post.featured_image" class="relative">
              <img 
                :src="post.featured_image.url" 
                class="w-full h-32 object-cover rounded-lg"
                :alt="post.featured_image.alt_text"
              >
              <Button 
                variant="ghost" 
                size="icon" 
                class="absolute top-2 right-2 h-6 w-6 bg-background/80 hover:bg-background"
                @click="removeFeaturedImage"
              >
                <X class="w-3 h-3" />
              </Button>
              <Input 
                v-model="post.featured_image.alt_text" 
                placeholder="Alt text..."
                class="mt-2 text-xs"
              />
            </div>
            <div 
              v-else 
              class="h-32 border-2 border-dashed border-border rounded-lg flex flex-col items-center justify-center cursor-pointer hover:border-foreground transition-colors"
              @click="showMediaPicker = true"
            >
              <ImageIcon class="w-8 h-8 text-muted-foreground mb-2" />
              <span class="text-xs text-muted-foreground">Pilih Gambar</span>
              <span class="text-[10px] text-muted-foreground">atau drag & drop gambar ke sini</span>
            </div>
          </Card>
        </div>

        <!-- Publish Date -->
        <div>
          <h3 class="text-xs font-semibold text-muted-foreground uppercase mb-2">TANGGAL PUBLISH</h3>
          <Card class="p-3">
            <div class="flex items-center gap-2">
              <Calendar class="w-4 h-4 text-muted-foreground" />
              <Input 
                type="datetime-local" 
                v-model="post.published_at"
                class="text-sm flex-1"
              />
            </div>
          </Card>
        </div>

        <!-- Tags -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <h3 class="text-xs font-semibold text-muted-foreground uppercase">TAGS</h3>
            <Button variant="ghost" size="sm" class="h-auto py-0 text-xs text-primary" @click="openTagModal">Kelola</Button>
          </div>
          <Card class="p-3">
            <Input 
              v-model="post.tags_input" 
              placeholder="Ketik nama tag, pisahkan dengan koma"
              class="text-sm"
            />
            <p class="text-xs text-muted-foreground mt-1">Pisahkan dengan koma</p>
          </Card>
        </div>
      </div>
    </div>

    <!-- Media Picker Modal for Featured Image -->
    <MediaPickerModal 
      v-if="showMediaPicker"
      @select="onMediaSelected"
      @close="showMediaPicker = false"
      :show="showMediaPicker"
    />

    <!-- Media Picker Modal for Editor -->
    <MediaPickerModal 
      v-if="showEditorMediaPicker"
      @select="onEditorMediaSelected"
      @close="showEditorMediaPicker = false"
      :show="showEditorMediaPicker"
    />

    <!-- Category Modal -->
    <div
      v-if="showCategoryModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
      @click.self="closeCategoryModal"
    >
      <Card class="w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col">
        <div class="p-5 border-b flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold">Kelola Kategori</h3>
            <p class="text-sm text-muted-foreground">Tambah, ubah, atau hapus kategori post.</p>
          </div>
          <Button variant="ghost" size="icon" @click="closeCategoryModal">
            <X class="w-4 h-4" />
          </Button>
        </div>

        <div class="grid md:grid-cols-[1fr_1.15fr] min-h-0 flex-1">
          <div class="border-r min-h-0 flex flex-col">
            <div class="p-4 border-b space-y-3">
              <Input v-model="categorySearch" placeholder="Cari kategori..." />
              <Button class="w-full" variant="outline" @click="openCategoryModal()">
                + Kategori Baru
              </Button>
            </div>
            <div class="flex-1 overflow-y-auto p-4 space-y-2">
              <div v-if="filteredCategories.length === 0" class="text-sm text-muted-foreground p-2">
                Belum ada kategori.
              </div>
              <Card
                v-for="category in filteredCategories"
                :key="category.id"
                class="p-3 cursor-pointer hover:shadow-md transition-shadow"
                @click="openCategoryModal(category)"
              >
                <div class="flex items-start justify-between gap-3">
                  <div class="min-w-0">
                    <div class="font-medium truncate">{{ category.name }}</div>
                    <div class="text-xs text-muted-foreground">/{{ category.slug }}</div>
                    <div v-if="category.description" class="text-xs text-muted-foreground mt-1 line-clamp-2">
                      {{ category.description }}
                    </div>
                  </div>
                  <div class="flex items-center gap-1 shrink-0" @click.stop>
                    <Button variant="ghost" size="icon" class="h-7 w-7" @click="openCategoryModal(category)">
                      <Info class="w-3.5 h-3.5" />
                    </Button>
                    <Button variant="ghost" size="icon" class="h-7 w-7 text-destructive" @click="deleteCategory(category.id)">
                      <X class="w-3.5 h-3.5" />
                    </Button>
                  </div>
                </div>
              </Card>
            </div>
          </div>

          <div class="min-h-0 flex flex-col">
            <div class="p-4 border-b">
              <h4 class="font-semibold">
                {{ editingCategoryId ? 'Edit Kategori' : 'Kategori Baru' }}
              </h4>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-4">
              <div class="space-y-2">
                <Label>Nama Kategori</Label>
                <Input v-model="categoryForm.name" placeholder="Nama kategori" @blur="generateCategorySlug" />
              </div>

              <div class="space-y-2">
                <Label>Slug</Label>
                <Input v-model="categoryForm.slug" placeholder="nama-kategori" />
              </div>

              <div class="space-y-2">
                <Label>Deskripsi</Label>
                <textarea
                  v-model="categoryForm.description"
                  rows="4"
                  class="w-full px-3 py-2 rounded-md border border-input bg-background text-sm resize-none"
                  placeholder="Deskripsi kategori..."
                ></textarea>
              </div>

              <div class="space-y-2">
                <Label>SEO Title</Label>
                <Input v-model="categoryForm.seo_title" placeholder="SEO title kategori" />
              </div>

              <div class="space-y-2">
                <Label>Meta Description</Label>
                <textarea
                  v-model="categoryForm.meta_description"
                  rows="4"
                  class="w-full px-3 py-2 rounded-md border border-input bg-background text-sm resize-none"
                  placeholder="Meta description kategori..."
                ></textarea>
              </div>
            </div>

            <div class="p-4 border-t flex items-center justify-end gap-2">
              <Button variant="outline" @click="closeCategoryModal">Batal</Button>
              <Button @click="saveCategory" :disabled="categorySaving">
                <Loader2 v-if="categorySaving" class="w-4 h-4 mr-2 animate-spin" />
                <Save v-else class="w-4 h-4 mr-2" />
                {{ categorySaving ? 'Menyimpan...' : 'Simpan Kategori' }}
              </Button>
            </div>
          </div>
        </div>
      </Card>
    </div>

    <!-- Tag Modal -->
    <div
      v-if="showTagModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
      @click.self="closeTagModal"
    >
      <Card class="w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
        <div class="p-5 border-b flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold">Kelola Tag</h3>
            <p class="text-sm text-muted-foreground">Tambah, edit, atau hapus tag post.</p>
          </div>
          <Button variant="ghost" size="icon" @click="closeTagModal">
            <X class="w-4 h-4" />
          </Button>
        </div>

        <div class="grid md:grid-cols-[1fr_0.95fr] min-h-0 flex-1">
          <div class="border-r min-h-0 flex flex-col">
            <div class="p-4 border-b space-y-3">
              <Input v-model="tagSearch" placeholder="Cari tag..." />
              <Button class="w-full" variant="outline" @click="openTagModal()">
                + Tag Baru
              </Button>
            </div>
            <div class="flex-1 overflow-y-auto p-4 space-y-2">
              <div v-if="filteredTags.length === 0" class="text-sm text-muted-foreground p-2">
                Belum ada tag.
              </div>
              <Card
                v-for="tag in filteredTags"
                :key="tag.id"
                class="p-3 cursor-pointer hover:shadow-md transition-shadow"
                @click="openTagModal(tag)"
              >
                <div class="flex items-center justify-between gap-3">
                  <div class="min-w-0">
                    <div class="font-medium truncate">{{ tag.name }}</div>
                    <div class="text-xs text-muted-foreground">/{{ tag.slug }}</div>
                  </div>
                  <div class="flex items-center gap-1 shrink-0" @click.stop>
                    <Button variant="ghost" size="icon" class="h-7 w-7" @click="openTagModal(tag)">
                      <Info class="w-3.5 h-3.5" />
                    </Button>
                    <Button variant="ghost" size="icon" class="h-7 w-7 text-destructive" @click="deleteTag(tag.id)">
                      <X class="w-3.5 h-3.5" />
                    </Button>
                  </div>
                </div>
              </Card>
            </div>
          </div>

          <div class="min-h-0 flex flex-col">
            <div class="p-4 border-b">
              <h4 class="font-semibold">
                {{ editingTagId ? 'Edit Tag' : 'Tag Baru' }}
              </h4>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-4">
              <div class="space-y-2">
                <Label>Nama Tag</Label>
                <Input v-model="tagForm.name" placeholder="Nama tag" @blur="generateTagSlug" />
              </div>

              <div class="space-y-2">
                <Label>Slug</Label>
                <Input v-model="tagForm.slug" placeholder="nama-tag" />
              </div>
            </div>

            <div class="p-4 border-t flex items-center justify-end gap-2">
              <Button variant="outline" @click="closeTagModal">Batal</Button>
              <Button @click="saveTag" :disabled="tagSaving">
                <Loader2 v-if="tagSaving" class="w-4 h-4 mr-2 animate-spin" />
                <Save v-else class="w-4 h-4 mr-2" />
                {{ tagSaving ? 'Menyimpan...' : 'Simpan Tag' }}
              </Button>
            </div>
          </div>
        </div>
      </Card>
    </div>
  </div>
</template>

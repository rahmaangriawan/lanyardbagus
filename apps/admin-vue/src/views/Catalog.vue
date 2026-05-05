<template>
  <div class="space-y-8">
    <!-- Toasts -->
    <div class="fixed top-4 right-4 z-50 space-y-2">
      <div v-for="t in toasts" :key="t.id" class="min-w-[260px] px-4 py-3 rounded-lg border shadow-sm text-sm"
           :class="{
             'bg-green-50 border-green-200 text-green-800': t.type==='success',
             'bg-red-50 border-red-200 text-red-800': t.type==='error',
             'bg-blue-50 border-blue-200 text-blue-800': t.type==='info',
           }">
        <div class="flex items-start gap-2">
          <span v-if="t.type==='success'" class="text-green-500 mt-0.5">✓</span>
          <span v-if="t.type==='error'" class="text-red-500 mt-0.5">✕</span>
          <span v-if="t.type==='info'" class="text-blue-500 mt-0.5">ℹ</span>
          <span class="leading-snug">{{ t.message }}</span>
        </div>
      </div>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold">Katalog Produk</h1>
        <p class="text-sm text-muted-foreground">Kelola kategori dan produk untuk halaman /katalog</p>
      </div>
      <div class="flex items-center gap-2">
        <button class="px-3 py-2 text-sm bg-white border rounded-lg" @click="fetchAll">Refresh</button>
      </div>
    </div>

    <!-- Categories -->
    <div v-if="section === 'categories'" class="bg-white border rounded-xl p-5">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold">Kategori</h2>
        <button @click="showCatForm = !showCatForm" class="px-3 py-2 text-sm bg-black text-white rounded-lg">{{ showCatForm ? 'Tutup' : 'Tambah Kategori' }}</button>
      </div>

      <div v-if="showCatForm" class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
        <input v-model="catForm.name" class="border rounded-lg px-3 py-2 text-sm" :class="{ 'border-red-500': catErrors.name }" placeholder="Nama kategori (mis. Lanyard)">
        <input v-model="catForm.slug" class="border rounded-lg px-3 py-2 text-sm" placeholder="Slug (mis. lanyard)">
        <input v-model="catForm.image" class="border rounded-lg px-3 py-2 text-sm md:col-span-2" placeholder="URL gambar (opsional)">
        <input v-model="catForm.description" class="border rounded-lg px-3 py-2 text-sm md:col-span-3" placeholder="Deskripsi (opsional)">
        <div class="flex items-center gap-3 md:col-span-3">
          <label class="text-sm flex items-center gap-2"><input type="checkbox" v-model="catForm.is_active"> Aktif</label>
          <input v-model.number="catForm.sort_order" type="number" class="border rounded-lg px-3 py-2 text-sm w-32" placeholder="Urutan">
          <button @click="submitCategory" class="px-3 py-2 text-sm bg-blue-600 text-white rounded-lg">Simpan</button>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-muted/50 border-b">
            <tr>
              <th class="text-left p-3">Nama</th>
              <th class="text-left p-3">Slug</th>
              <th class="text-left p-3">Aktif</th>
              <th class="text-right p-3">Urutan</th>
              <th class="text-center p-3">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in categories" :key="c.id" class="border-b">
              <td class="p-3 font-medium">{{ c.name }}</td>
              <td class="p-3">{{ c.slug }}</td>
              <td class="p-3">{{ c.is_active ? 'Ya' : 'Tidak' }}</td>
              <td class="p-3 text-right">{{ c.sort_order }}</td>
              <td class="p-3 text-center space-x-2">
                <button class="p-1.5 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors" title="Edit" @click="editCategory(c)">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM16.862 4.487L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                </button>
                <button class="p-1.5 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Hapus" @click="deleteCategory(c)">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                </button>
              </td>
            </tr>
            <tr v-if="categories.length === 0">
              <td colspan="5" class="p-6 text-center text-muted-foreground">Belum ada kategori</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Products -->
    <div v-if="section === 'products'" class="bg-white border rounded-xl p-5">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <h2 class="text-lg font-semibold">Produk</h2>
        <div class="flex items-center gap-2">
          <select v-model="filterCategory" @change="fetchProducts" class="px-3 py-2 border rounded-lg text-sm">
            <option :value="null">Semua Kategori</option>
            <option v-for="c in categories" :value="c.id" :key="c.id">{{ c.name }}</option>
          </select>
          <button @click="showProdForm = !showProdForm" class="px-3 py-2 text-sm bg-black text-white rounded-lg">{{ showProdForm ? 'Tutup' : 'Tambah Produk' }}</button>
        </div>
      </div>

      <div v-if="showProdForm" class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
        <select v-model="prodForm.category_id" class="border rounded-lg px-3 py-2 text-sm" :class="{ 'border-red-500': prodErrors.category_id }">
          <option :value="null" disabled>Pilih kategori</option>
          <option v-for="c in categories" :value="c.id" :key="c.id">{{ c.name }}</option>
        </select>
        <input v-model="prodForm.name" class="border rounded-lg px-3 py-2 text-sm" :class="{ 'border-red-500': prodErrors.name }" placeholder="Nama produk">
        <input v-model="prodForm.slug" class="border rounded-lg px-3 py-2 text-sm" placeholder="Slug (opsional)">
        <input v-model.number="prodForm.price" type="number" class="border rounded-lg px-3 py-2 text-sm" :class="{ 'border-red-500': prodErrors.price }" placeholder="Harga per pcs">
        <input v-model.number="prodForm.min_order" type="number" class="border rounded-lg px-3 py-2 text-sm" :class="{ 'border-red-500': prodErrors.min_order }" placeholder="Min order (default 50)">
        <label class="text-sm flex items-center gap-2"><input type="checkbox" v-model="prodForm.is_active"> Aktif</label>
        <input v-model="prodForm.imagesCsv" class="border rounded-lg px-3 py-2 text-sm md:col-span-2" placeholder="Gambar (URL dipisah koma)">
        <div v-if="firstImageUrl(prodForm.imagesCsv)" class="md:col-span-1 flex items-center gap-2">
          <img :src="firstImageUrl(prodForm.imagesCsv)" alt="Preview" class="w-16 h-16 object-cover rounded border" />
          <span class="text-xs text-muted-foreground">Preview</span>
        </div>
        <input v-model="prodForm.specsJson" class="border rounded-lg px-3 py-2 text-sm md:col-span-3" :class="{ 'border-red-500': prodErrors.specsJson }" placeholder='Spesifikasi (JSON), contoh: {"Lebar":"2 cm","Cetak":"Sublim"}'>
        <div class="flex items-center gap-3 md:col-span-3">
          <input v-model.number="prodForm.sort_order" type="number" class="border rounded-lg px-3 py-2 text-sm w-32" placeholder="Urutan">
          <button @click="submitProduct" class="px-3 py-2 text-sm bg-blue-600 text-white rounded-lg">Simpan</button>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-muted/50 border-b">
            <tr>
              <th class="text-left p-3">Gambar</th>
              <th class="text-left p-3">Produk</th>
              <th class="text-left p-3">Kategori</th>
              <th class="text-right p-3">Harga</th>
              <th class="text-left p-3">Aktif</th>
              <th class="text-right p-3">Min</th>
              <th class="text-right p-3">Urutan</th>
              <th class="text-center p-3">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="p in products" :key="p.id" class="border-b">
              <td class="p-3">
                <img v-if="Array.isArray(p.images) && p.images.length" :src="p.images[0]" class="w-12 h-12 object-cover rounded border" />
              </td>
              <td class="p-3 font-medium">{{ p.name }}</td>
              <td class="p-3">{{ p.category?.name }}</td>
              <td class="p-3 text-right">{{ formatRupiah(p.price) }}</td>
              <td class="p-3">{{ p.is_active ? 'Ya' : 'Tidak' }}</td>
              <td class="p-3 text-right">{{ p.min_order }}</td>
              <td class="p-3 text-right">{{ p.sort_order }}</td>
              <td class="p-3 text-center space-x-2">
                <button class="p-1.5 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors" title="Edit" @click="startEditProduct(p)">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM16.862 4.487L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                </button>
                <button class="p-1.5 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-md transition-colors" title="Copy Order Link" @click="copyOrderLink(p)">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612a2.25 2.25 0 01-.659 1.591l-5.411 5.411a2.25 2.25 0 01-1.591.659H6.75a2.25 2.25 0 00-2.25 2.25v3c0 1.012.33 1.945.889 2.7m13.112-13.11c.555.76.864 1.685.864 2.632 0 1.218-.47 2.36-1.317 3.216l-5.412 5.412a4.5 4.5 0 01-3.192 1.318H8.25a2.25 2.25 0 00-2.25 2.25v3c0 1.012.33 1.945.889 2.7m0 0a21.75 21.75 0 0115.334 0M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </button>
                <button class="p-1.5 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Hapus" @click="deleteProduct(p)">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                </button>
              </td>
            </tr>
            <tr v-if="products.length === 0">
              <td colspan="7" class="p-6 text-center text-muted-foreground">Belum ada produk</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Edit modal simple -->
      <div v-if="editProd" class="fixed inset-0 bg-black/20 flex items-center justify-center z-50">
        <div class="bg-white border rounded-xl p-5 w-full max-w-2xl">
          <h3 class="text-lg font-semibold mb-4">Edit Produk</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <select v-model="editProd.category_id" class="border rounded-lg px-3 py-2 text-sm">
              <option v-for="c in categories" :value="c.id" :key="c.id">{{ c.name }}</option>
            </select>
            <input v-model="editProd.name" class="border rounded-lg px-3 py-2 text-sm" />
            <input v-model="editProd.slug" class="border rounded-lg px-3 py-2 text-sm" />
            <input v-model.number="editProd.price" type="number" class="border rounded-lg px-3 py-2 text-sm" />
            <input v-model.number="editProd.min_order" type="number" class="border rounded-lg px-3 py-2 text-sm" />
            <label class="text-sm flex items-center gap-2"><input type="checkbox" v-model="editProd.is_active"> Aktif</label>
            <input v-model="editProd.imagesCsv" class="border rounded-lg px-3 py-2 text-sm md:col-span-2" />
            <input v-model="editProd.specsJson" class="border rounded-lg px-3 py-2 text-sm md:col-span-3" />
          </div>
          <div class="mt-4 flex justify-end gap-2">
            <button class="px-3 py-2 text-sm border rounded-lg" @click="editProd=null">Batal</button>
            <button class="px-3 py-2 text-sm bg-blue-600 text-white rounded-lg" @click="submitEditProduct">Simpan</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'

const API_URL = 'https://lanyardbagus.com'
const auth = useAuthStore()

const categories = ref([])
const products = ref([])
const filterCategory = ref(null)
const showCatForm = ref(false)
const showProdForm = ref(false)
const toasts = ref([])

const catForm = ref({ name: '', slug: '', description: '', image: '', is_active: true, sort_order: 0 })
const prodForm = ref({ category_id: null, name: '', slug: '', price: 0, min_order: 50, is_active: true, sort_order: 0, imagesCsv: '', specsJson: '' })

const editProd = ref(null)
const editingCategoryId = ref(null)
const catErrors = ref({})
const prodErrors = ref({})

const props = defineProps({
  section: { type: String, default: 'products' }
})

function formatRupiah(num) {
  return 'Rp ' + Number(num || 0).toLocaleString('id-ID')
}

function showToast(message, type = 'success') {
  const id = Date.now() + Math.random()
  toasts.value.push({ id, message, type })
  setTimeout(() => {
    toasts.value = toasts.value.filter(t => t.id !== id)
  }, 3000)
}

function getCookie(name) {
  const value = `; ${document.cookie}`
  const parts = value.split(`; ${name}=`)
  if (parts.length === 2) return parts.pop().split(';').shift()
  return null
}

function xsrfHeaders() {
  const token = getCookie('XSRF-TOKEN')
  return token ? { 'X-XSRF-TOKEN': decodeURIComponent(token), 'X-Requested-With': 'XMLHttpRequest' } : { 'X-Requested-With': 'XMLHttpRequest' }
}

async function fetchCategories() {
  const res = await fetch(`${API_URL}/api/admin/product-categories`, {
    credentials: 'include',
    headers: { ...xsrfHeaders() }
  })
  categories.value = await res.json()
}

async function fetchProducts() {
  const params = new URLSearchParams()
  if (filterCategory.value) params.append('category_id', filterCategory.value)
  const res = await fetch(`${API_URL}/api/admin/products?${params}`, {
    credentials: 'include',
    headers: { ...xsrfHeaders() }
  })
  products.value = await res.json()
}

async function fetchAll() {
  await fetchCategories()
  await fetchProducts()
}

async function submitCategory() {
  // Frontend validation
  catErrors.value = {}
  if (!catForm.value.name?.trim()) {
    catErrors.value.name = 'required'
    showToast('Nama kategori wajib diisi', 'error')
    return
  }
  const payload = { ...catForm.value }
  const url = editingCategoryId.value
    ? `${API_URL}/api/admin/product-categories/${editingCategoryId.value}`
    : `${API_URL}/api/admin/product-categories`
  const method = editingCategoryId.value ? 'PUT' : 'POST'
  const res = await fetch(url, {
    method,
    credentials: 'include',
    headers: { 'Content-Type': 'application/json', ...xsrfHeaders() },
    body: JSON.stringify(payload)
  })
  if (res.ok) {
    showCatForm.value = false
    editingCategoryId.value = null
    catForm.value = { name: '', slug: '', description: '', image: '', is_active: true, sort_order: 0 }
    fetchCategories()
    showToast('Kategori berhasil disimpan', 'success')
  } else {
    let msg = 'Gagal menyimpan kategori'
    try { const j = await res.json(); msg = j.message || j.error || msg } catch(e) {}
    showToast(msg, 'error')
  }
}

function editCategory(c) {
  showCatForm.value = true
  editingCategoryId.value = c.id
  catForm.value = { name: c.name, slug: c.slug, description: c.description, image: c.image, is_active: c.is_active, sort_order: c.sort_order }
}

async function deleteCategory(c) {
  if (!confirm(`Hapus kategori ${c.name}?`)) return
  const res = await fetch(`${API_URL}/api/admin/product-categories/${c.id}`, {
    method: 'DELETE',
    credentials: 'include',
    headers: { ...xsrfHeaders() }
  })
  if (res.ok) {
    fetchAll()
    showToast('Kategori dihapus', 'success')
  } else {
    showToast('Gagal hapus kategori', 'error')
  }
}

function normalizeProductPayload(f) {
  const payload = { ...f }
  if (payload.imagesCsv) {
    payload.images = payload.imagesCsv.split(',').map(s => s.trim()).filter(Boolean)
  }
  delete payload.imagesCsv
  if (payload.specsJson) {
    try { payload.specs = JSON.parse(payload.specsJson) } catch(e) { payload.specs = {} }
  }
  delete payload.specsJson
  return payload
}

async function submitProduct() {
  // Frontend validation
  prodErrors.value = {}
  if (!prodForm.value.category_id) { prodErrors.value.category_id = 'required' }
  if (!prodForm.value.name?.trim()) { prodErrors.value.name = 'required' }
  if (prodForm.value.price === null || prodForm.value.price === undefined || prodForm.value.price < 0) { prodErrors.value.price = 'invalid' }
  if (!prodForm.value.min_order || prodForm.value.min_order < 1) { prodErrors.value.min_order = 'invalid' }
  // Validate specs JSON if provided
  if (prodForm.value.specsJson) {
    try { JSON.parse(prodForm.value.specsJson) } catch(e) { prodErrors.value.specsJson = 'invalid' }
  }
  if (Object.keys(prodErrors.value).length) {
    showToast('Periksa kembali input produk Anda', 'error')
    return
  }
  const payload = normalizeProductPayload(prodForm.value)
  const res = await fetch(`${API_URL}/api/admin/products`, {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json', ...xsrfHeaders() },
    body: JSON.stringify(payload)
  })
  if (res.ok) {
    showProdForm.value = false
    prodForm.value = { category_id: null, name: '', slug: '', price: 0, min_order: 50, is_active: true, sort_order: 0, imagesCsv: '', specsJson: '' }
    fetchProducts()
    showToast('Produk berhasil disimpan', 'success')
  } else {
    let msg = 'Gagal menyimpan produk'
    try { const j = await res.json(); msg = j.message || j.error || msg } catch(e) {}
    showToast(msg, 'error')
  }
}

function startEditProduct(p) {
  editProd.value = {
    ...p,
    imagesCsv: Array.isArray(p.images) ? p.images.join(',') : '',
    specsJson: p.specs ? JSON.stringify(p.specs) : ''
  }
}

async function submitEditProduct() {
  const p = editProd.value
  const payload = normalizeProductPayload(p)
  const res = await fetch(`${API_URL}/api/admin/products/${p.id}`, {
    method: 'PUT',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json', ...xsrfHeaders() },
    body: JSON.stringify(payload)
  })
  if (res.ok) {
    editProd.value = null
    fetchProducts()
    showToast('Produk berhasil diperbarui', 'success')
  } else {
    let msg = 'Gagal update produk'
    try { const j = await res.json(); msg = j.message || j.error || msg } catch(e) {}
    showToast(msg, 'error')
  }
}

async function deleteProduct(p) {
  if (!confirm(`Hapus produk ${p.name}?`)) return
  const res = await fetch(`${API_URL}/api/admin/products/${p.id}`, {
    method: 'DELETE',
    credentials: 'include',
    headers: { ...xsrfHeaders() }
  })
  if (res.ok) {
    fetchProducts()
    showToast('Produk dihapus', 'success')
  } else {
    showToast('Gagal hapus produk', 'error')
  }
}

async function copyOrderLink(p) {
  const url = `${API_URL}/order?product_id=${p.id}`
  try {
    await navigator.clipboard.writeText(url)
    showToast('Link order disalin', 'success')
  } catch (e) {
    showToast('Gagal menyalin link', 'error')
  }
}

onMounted(() => {
  fetchAll()
})
</script>

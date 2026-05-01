<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const API_URL = 'https://lanyardbagus.com'
const form = ref({
  name: '',
  username: '',
  email: '',
  phone_number: '',
  bio: '',
  avatar_url: '',
  password: '',
  password_confirm: '',
})

const originalData = ref({})
const saving = ref(false)
const success = ref('')
const error = ref('')
const validationErrors = ref({})
const avatarPreview = ref('')

onMounted(async () => {
  try {
    const res = await axios.get(`${API_URL}/api/users/me`, { withCredentials: true })
    const u = res.data.user
    form.value = {
      name: u.name || '',
      username: u.username || '',
      email: u.email || '',
      phone_number: u.phone_number || '',
      bio: u.bio || '',
      avatar_url: u.avatar_url || '',
      password: '',
      password_confirm: '',
    }
    originalData.value = { ...form.value }
    avatarPreview.value = u.avatar_url || ''
  } catch (e) {
    error.value = 'Gagal memuat data profil.'
  }
})

function slugify(text) {
  return text.toString().toLowerCase()
    .replace(/\s+/g, '-')           // Replace spaces with -
    .replace(/[^\w-]+/g, '')       // Remove all non-word chars
    .replace(/--+/g, '-')         // Replace multiple - with single -
    .replace(/^-+/, '')           // Trim - from start of text
    .replace(/-+$/, '');          // Trim - from end of text
}

function onAvatarChange(e) {
  const file = e.target.files[0]
  if (!file) return
  const reader = new FileReader()
  reader.onload = (ev) => {
    avatarPreview.value = ev.target.result
    form.value.avatar_url = ev.target.result
  }
  reader.readAsDataURL(file)
}

async function save() {
  error.value = ''
  success.value = ''
  validationErrors.value = {}

  if (form.value.password && form.value.password !== form.value.password_confirm) {
    error.value = 'Kata sandi baru dan konfirmasi tidak cocok.'
    return
  }

  saving.value = true
  try {
    const payload = {
      name: form.value.name,
      username: form.value.username,
      email: form.value.email,
      phone_number: form.value.phone_number,
      bio: form.value.bio,
      avatar_url: form.value.avatar_url,
    }
    if (form.value.password) payload.password = form.value.password

    const res = await axios.put(`${API_URL}/api/users/me`, payload, { withCredentials: true })
    const u = res.data.user
    form.value.password = ''
    form.value.password_confirm = ''
    originalData.value = { ...form.value }
    success.value = 'Profil berhasil disimpan!'
    setTimeout(() => (success.value = ''), 3000)
  } catch (e) {
    if (e.response?.status === 422) {
      validationErrors.value = e.response.data.errors
      error.value = 'Mohon periksa kembali inputan Anda.'
    } else {
      error.value = e.response?.data?.message || 'Gagal menyimpan profil.'
    }
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <div class="max-w-2xl mx-auto py-8 px-4">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
      <p class="text-gray-500 mt-1 text-sm">Kelola informasi profil yang akan tampil di halaman publik.</p>
    </div>

    <form @submit.prevent="save" class="space-y-6">
      <!-- Avatar -->
      <div class="flex items-center gap-6">
        <div class="relative group shrink-0">
          <div v-if="avatarPreview" class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-200">
            <img :src="avatarPreview" class="w-full h-full object-cover" alt="Avatar" />
          </div>
          <div v-else class="w-20 h-20 rounded-full bg-gray-100 border-2 border-gray-200 flex items-center justify-center text-gray-500 font-bold text-2xl">
            {{ (form.username || form.email || 'A').charAt(0).toUpperCase() }}
          </div>
          <label class="absolute inset-0 rounded-full flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
            <span class="text-white text-xs font-semibold">Ubah</span>
            <input type="file" accept="image/*" class="hidden" @change="onAvatarChange" />
          </label>
        </div>
        <div>
          <p class="text-sm font-semibold text-gray-700">Foto Profil</p>
          <p class="text-xs text-gray-400 mt-0.5">JPG, PNG. Maks 2MB. Klik foto untuk mengubah.</p>
          <div class="mt-2">
            <label class="text-xs font-medium text-blue-600 cursor-pointer hover:text-blue-700">
              Atau masukkan URL gambar:
            </label>
            <input
              v-model="form.avatar_url"
              type="url"
              @input="avatarPreview = form.avatar_url"
              placeholder="https://..."
              class="mt-1 w-full text-xs border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
        </div>
      </div>

      <!-- Name & Email -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
        <input v-model="form.name" type="text" required
          :class="[
            'w-full border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500',
            validationErrors.name ? 'border-red-300 bg-red-50' : 'border-gray-200'
          ]" />
        <p v-if="validationErrors.name" class="text-xs text-red-500 mt-1">{{ validationErrors.name[0] }}</p>
      </div>

      <!-- Username & Email -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">ID Penulis / Username <span class="text-red-500">*</span></label>
          <input v-model="form.username" type="text" required
            :class="[
              'w-full border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500',
              validationErrors.username ? 'border-red-300 bg-red-50' : 'border-gray-200'
            ]" />
          <p v-if="validationErrors.username" class="text-xs text-red-500 mt-1">{{ validationErrors.username[0] }}</p>
          <p v-else class="text-xs text-gray-400 mt-1">Digunakan di URL halaman penulis: /author/<strong>{{ slugify(form.username || '') }}</strong></p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input v-model="form.email" type="email"
            :class="[
              'w-full border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500',
              validationErrors.email ? 'border-red-300 bg-red-50' : 'border-gray-200'
            ]" />
          <p v-if="validationErrors.email" class="text-xs text-red-500 mt-1">{{ validationErrors.email[0] }}</p>
        </div>
      </div>

      <!-- Phone -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
        <input v-model="form.phone_number" type="tel"
          :class="[
            'w-full border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500',
            validationErrors.phone_number ? 'border-red-300 bg-red-50' : 'border-gray-200'
          ]" />
        <p v-if="validationErrors.phone_number" class="text-xs text-red-500 mt-1">{{ validationErrors.phone_number[0] }}</p>
      </div>

      <!-- Bio -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Bio Singkat</label>
        <textarea v-model="form.bio" rows="3" maxlength="1000"
          placeholder="Tuliskan sedikit tentang diri Anda. Bio ini akan tampil di halaman profil penulis dan di bawah setiap artikel Anda."
          :class="[
            'w-full border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none',
            validationErrors.bio ? 'border-red-300 bg-red-50' : 'border-gray-200'
          ]"></textarea>
        <p v-if="validationErrors.bio" class="text-xs text-red-500 mt-1">{{ validationErrors.bio[0] }}</p>
        <p v-else class="text-xs text-gray-400 mt-1 text-right">{{ form.bio?.length || 0 }} / 1000</p>
      </div>

      <hr class="border-gray-100" />

      <!-- Password -->
      <div>
        <h2 class="text-base font-semibold text-gray-800 mb-3">Ganti Kata Sandi</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Baru</label>
            <input v-model="form.password" type="password" placeholder="Biarkan kosong jika tidak ingin mengganti"
              class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi</label>
            <input v-model="form.password_confirm" type="password" placeholder="Ulangi kata sandi baru"
              class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </div>
        </div>
      </div>

      <!-- Alerts -->
      <div v-if="success" class="flex items-center gap-2 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        {{ success }}
      </div>
      <div v-if="error" class="flex items-center gap-2 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        {{ error }}
      </div>

      <!-- Submit -->
      <div class="flex justify-end">
        <button type="submit" :disabled="saving"
          class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white font-semibold rounded-lg text-sm transition-colors">
          <svg v-if="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
          </svg>
          {{ saving ? 'Menyimpan...' : 'Simpan Perubahan' }}
        </button>
      </div>
    </form>
  </div>
</template>

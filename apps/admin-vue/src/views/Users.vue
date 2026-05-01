<script setup>
import { ref, onMounted, computed } from 'vue'
import { Card, Button, Input, Label, Alert, AlertTitle, AlertDescription } from '../components/ui'
import { Plus, Search, Trash2, Loader2, Edit2, Check, X, Shield, User, Info } from 'lucide-vue-next'
import axios from 'axios'

const API_URL = 'http://localhost:8000'

const users = ref([])
const loading = ref(true)
const search = ref('')
const showModal = ref(false)
const editingId = ref(null)
const successMessage = ref('')
const errorMessage = ref('')

const form = ref({
  username: '',
  email: '',
  password: '',
  role: 'member' // 'admin' or 'member'
})

const filteredUsers = computed(() => {
  if (!search.value) return users.value
  const term = search.value.toLowerCase()
  return users.value.filter(u => 
    u.username?.toLowerCase().includes(term) || 
    u.email?.toLowerCase().includes(term) ||
    u.role?.toLowerCase().includes(term)
  )
})

onMounted(async () => {
  await loadUsers()
})

async function loadUsers() {
  loading.value = true
  try {
    const res = await axios.get(`${API_URL}/api/admin/users`, { withCredentials: true })
    users.value = (res.data || []).map(u => ({
      ...u,
      role: u.role?.toLowerCase() || 'member'
    }))
  } catch (err) {
    console.error('Failed to load users:', err)
  } finally {
    loading.value = false
  }
}

function openModal(user = null) {
  if (user) {
    editingId.value = user.id
    form.value = { 
      username: user.username,
      email: user.email,
      password: '',
      role: user.role || 'member'
    }
  } else {
    editingId.value = null
    form.value = { username: '', email: '', password: '', role: 'member' }
  }
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  editingId.value = null
}

async function saveUser() {
  try {
    const data = { ...form.value }
    if (!data.password && editingId.value) {
      delete data.password // Don't update password if empty
    }
    if (editingId.value) {
      await axios.patch(`${API_URL}/api/admin/users/${editingId.value}`, data, { withCredentials: true })
    } else {
      await axios.post(`${API_URL}/api/admin/users`, data, { withCredentials: true })
    }
    await loadUsers()
    closeModal()
    successMessage.value = 'Berhasil menyimpan pengguna!'
    setTimeout(() => { successMessage.value = '' }, 3000)
  } catch (err) {
    errorMessage.value = 'Gagal menyimpan: ' + (err.response?.data?.message || err.message)
    setTimeout(() => { errorMessage.value = '' }, 5000)
  }
}

async function deleteUser(id) {
  if (!confirm('Yakin ingin menghapus user ini?')) return
  try {
    await axios.delete(`${API_URL}/api/admin/users/${id}`, { withCredentials: true })
    await loadUsers()
    successMessage.value = 'Berhasil menghapus pengguna!'
    setTimeout(() => { successMessage.value = '' }, 3000)
  } catch (err) {
    errorMessage.value = 'Gagal menghapus: ' + (err.response?.data?.message || err.message)
    setTimeout(() => { errorMessage.value = '' }, 5000)
  }
}

function getRoleBadgeClass(role) {
  return role === 'admin' 
    ? 'bg-primary text-primary-foreground' 
    : 'bg-secondary text-secondary-foreground'
}
</script>

<template>
  <div class="p-6 h-full overflow-y-auto scrollbar-thin">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold">Kelola Pengguna</h1>
        <p class="text-muted-foreground">Tambah, edit, dan hapus user dengan role</p>
      </div>
      <Button @click="openModal()">
        <Plus class="w-4 h-4 mr-2" />
        User Baru
      </Button>
    </div>

    <!-- Search -->
    <div class="flex items-center gap-4 mb-6">
      <div class="relative flex-1 max-w-sm">
        <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
        <Input 
          v-model="search"
          placeholder="Cari user..."
          class="pl-10"
        />
      </div>
    </div>

    <!-- Notifications -->
    <Alert v-if="successMessage" variant="success" class="mb-6">
      <Check class="w-4 h-4" />
      <AlertTitle>Berhasil</AlertTitle>
      <AlertDescription>{{ successMessage }}</AlertDescription>
    </Alert>

    <Alert v-if="errorMessage" variant="destructive" class="mb-6">
      <Info class="w-4 h-4" />
      <AlertTitle>Terjadi Kesalahan</AlertTitle>
      <AlertDescription>{{ errorMessage }}</AlertDescription>
    </Alert>

    <!-- Loading -->
    <div v-if="loading" class="p-12 text-center">
      <Loader2 class="w-6 h-6 animate-spin mx-auto" />
    </div>

    <!-- Users Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <Card 
        v-for="user in filteredUsers" 
        :key="user.id"
        class="p-4 hover:shadow-md transition-shadow cursor-pointer"
        @click="openModal(user)"
      >
        <div class="flex items-start gap-3">
          <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
            <User class="w-5 h-5 text-primary" />
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-semibold truncate">{{ user.username || '-' }}</h3>
            <p class="text-sm text-muted-foreground truncate">{{ user.email }}</p>
            <div class="flex items-center gap-2 mt-2">
              <span 
                :class="['px-2 py-0.5 rounded text-xs font-medium', getRoleBadgeClass(user.role)]"
              >
                {{ user.role === 'admin' ? 'Admin' : 'Member' }}
              </span>
            </div>
          </div>
          <div class="flex gap-1" @click.stop>
            <Button variant="ghost" size="icon" class="h-8 w-8 text-destructive" @click="deleteUser(user.id)">
              <Trash2 class="w-4 h-4" />
            </Button>
          </div>
        </div>
      </Card>
    </div>

    <!-- Empty State -->
    <div v-if="!loading && filteredUsers.length === 0" class="text-center py-12">
      <p class="text-muted-foreground">Belum ada user</p>
      <Button variant="outline" class="mt-4" @click="openModal()">
        <Plus class="w-4 h-4 mr-2" />
        Buat User Pertama
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
              {{ editingId ? 'Edit User' : 'User Baru' }}
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
              <Label>Nama Lengkap</Label>
              <Input 
                v-model="form.username" 
                placeholder="Username..."
              />
            </div>

            <!-- Email -->
            <div class="space-y-2">
              <Label>Email</Label>
              <Input 
                v-model="form.email" 
                type="email"
                placeholder="email@example.com"
              />
            </div>

            <!-- Password -->
            <div class="space-y-2">
              <Label>{{ editingId ? 'Password Baru (Opsional)' : 'Password' }}</Label>
              <Input 
                v-model="form.password" 
                type="password"
                autocomplete="new-password"
                :placeholder="editingId ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter'"
              />
            </div>

            <!-- Role -->
            <div class="space-y-2">
              <Label>Role</Label>
              <div class="space-y-2">
                <label 
                  :class="[
                    'flex items-start gap-3 cursor-pointer p-3 rounded-md border transition-colors',
                    form.role === 'admin' ? 'border-primary bg-primary/10' : 'border-border hover:bg-secondary'
                  ]"
                >
                  <input 
                    type="radio" 
                    v-model="form.role" 
                    value="admin"
                    class="w-4 h-4 accent-primary mt-0.5"
                  >
                  <div>
                    <div class="flex items-center gap-2">
                      <Shield class="w-4 h-4 text-primary" />
                      <span :class="['text-sm font-medium', form.role === 'admin' ? 'text-primary' : '']">Admin</span>
                    </div>
                    <p :class="['text-xs mt-1', form.role === 'admin' ? 'text-primary/70' : 'text-muted-foreground']">
                      Akses penuh ke semua fitur
                    </p>
                  </div>
                </label>
                <label 
                  :class="[
                    'flex items-start gap-3 cursor-pointer p-3 rounded-md border transition-colors',
                    form.role === 'member' ? 'border-primary bg-primary/10' : 'border-border hover:bg-secondary'
                  ]"
                >
                  <input 
                    type="radio" 
                    v-model="form.role" 
                    value="member"
                    class="w-4 h-4 accent-primary mt-0.5"
                  >
                  <div>
                    <div class="flex items-center gap-2">
                      <User class="w-4 h-4 text-muted-foreground" />
                      <span :class="['text-sm font-medium', form.role === 'member' ? 'text-primary' : '']">Member</span>
                    </div>
                    <p :class="['text-xs mt-1', form.role === 'member' ? 'text-primary/70' : 'text-muted-foreground']">
                      Hanya bisa: Post, Halaman, dan Upload Media
                    </p>
                  </div>
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Fixed Footer -->
        <div class="p-6 border-t bg-background flex-shrink-0 flex justify-end gap-2">
          <Button variant="outline" @click="closeModal">Batal</Button>
          <Button @click="saveUser">
            <Check class="w-4 h-4 mr-2" />
            Simpan
          </Button>
        </div>
      </Card>
    </div>
  </div>
</template>

<style scoped>
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

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { Card, Button, Input, Label } from '../components/ui'
import MediaPicker from '../components/MediaPicker.vue'
import { useAuthStore } from '../stores/auth'
import { Globe, Shield, Cloud, Check, Loader2, ArrowLeftRight, Plus, Trash2 } from 'lucide-vue-next'
import axios from 'axios'

const router = useRouter()
const auth = useAuthStore()

const API_URL = 'https://lanyardbagus.com'

const activeTab = ref('general')

const settings = ref({
  site_title: '',
  homepage_seo_title: '',
  homepage_meta_description: '',
  whatsapp_number: '',
  logo_image: null,
  favicon_image: null
})

const verification = ref({
  google_search_console: '',
  google_analytics: '',
  facebook_domain_verification: '',
  bing_webmaster: ''
})

const r2 = ref({
  access_key_id: '',
  secret_access_key: '',
  bucket_name: '',
  endpoint: '',
  public_url: ''
})

const loading = ref(true)
const saving = ref(false)
const saved = ref(false)

const redirects = ref([])
const newRedirect = ref({ from_url: '', to_url: '', status_code: 301, is_active: true })
const redirectSaving = ref(false)

function normalizeR2Endpoint(value) {
  const trimmed = (value || '').trim()
  if (!trimmed) return ''
  if (/^https?:\/\//i.test(trimmed)) return trimmed.replace(/\/+$/, '')
  return `https://${trimmed.replace(/\/+$/, '')}.r2.cloudflarestorage.com`
}

function normalizeR2PublicUrl(value) {
  const trimmed = (value || '').trim()
  if (!trimmed) return ''
  if (/^https?:\/\//i.test(trimmed)) return trimmed.replace(/\/+$/, '')
  return `https://${trimmed.replace(/\/+$/, '')}`
}

// Helper to get CSRF cookie - must be called before any state-changing request
async function ensureCsrfCookie() {
  try {
    console.log('Fetching CSRF cookie...')
    await axios.get(`${API_URL}/sanctum/csrf-cookie`, { 
      withCredentials: true,
      headers: { 'Accept': 'application/json' }
    })
    console.log('CSRF cookie fetched successfully')
    // Small delay to ensure cookie is set
    await new Promise(r => setTimeout(r, 100))
    return true
  } catch (e) {
    console.error('CSRF cookie fetch failed:', e.message)
    return false
  }
}

// Check auth status by calling /api/auth/me
async function checkAuth() {
  try {
    const res = await axios.get(`${API_URL}/api/auth/me`, { withCredentials: true })
    console.log('Auth check success:', res.data)
    return true
  } catch (err) {
    console.log('Auth check failed:', err.response?.status, err.response?.data)
    if (err.response?.status === 401) {
      // Clear everything
      localStorage.clear()
      document.cookie.split(';').forEach(c => {
        document.cookie = c.replace(/^ +/, '').replace(/=.*/, '=;expires=' + new Date().toUTCString() + ';path=/')
      })
      return false
    }
    return true // Allow on other errors
  }
}

onMounted(async () => {
  loading.value = true
  await loadSettings()
  await loadRedirects()
  loading.value = false
})

async function saveSettings() {
  saving.value = true
  saved.value = false
  
  try {
    // Step 1: Get fresh CSRF cookie before saving (CRITICAL!)
    const csrfOk = await ensureCsrfCookie()
    if (!csrfOk) {
      throw new Error('Gagal mengambil CSRF token. Coba refresh halaman.')
    }
    
    // Step 2: Save site settings (SiteSettingController)
    console.log('Saving site settings...')
    await axios.put(`${API_URL}/api/admin/settings`, {
      site_title: settings.value.site_title,
      homepage_seo_title: settings.value.homepage_seo_title,
      homepage_meta_description: settings.value.homepage_meta_description,
      whatsapp_number: settings.value.whatsapp_number,
      google_site_verification: verification.value.google_search_console,
      bing_site_verification: verification.value.bing_webmaster,
      logo_image_id: settings.value.logo_image?.id || null,
      favicon_image_id: settings.value.favicon_image?.id || null,
    }, { withCredentials: true })
    console.log('Site settings saved')
    
    // Step 3: Save system settings (SettingController) - R2 and verification
    const systemSettings = [
      { key: 'google_search_console', value: verification.value.google_search_console, group: 'verification' },
      { key: 'google_analytics', value: verification.value.google_analytics, group: 'verification' },
      { key: 'facebook_domain_verification', value: verification.value.facebook_domain_verification, group: 'verification' },
      { key: 'bing_webmaster', value: verification.value.bing_webmaster, group: 'verification' },
      { key: 'r2_access_key_id', value: r2.value.access_key_id, group: 'r2' },
      { key: 'r2_bucket_name', value: r2.value.bucket_name, group: 'r2' },
      { key: 'r2_endpoint', value: normalizeR2Endpoint(r2.value.endpoint), group: 'r2' },
      { key: 'r2_public_url', value: normalizeR2PublicUrl(r2.value.public_url), group: 'r2' },
    ]
    
    if (r2.value.secret_access_key) {
      systemSettings.push({ key: 'r2_secret_access_key', value: r2.value.secret_access_key, group: 'r2' })
    }
    
    console.log('Saving system settings...')
    await axios.put(`${API_URL}/api/admin/system-settings`, { settings: systemSettings }, { withCredentials: true })
    console.log('System settings saved')
    
    saved.value = true
    r2.value.secret_access_key = ''
    setTimeout(() => saved.value = false, 3000)
    
    // Reload settings to confirm save
    await loadSettings()
    
  } catch (err) {
    console.error('Save error:', err)
    if (err.response?.status === 401) {
      alert('Sesi login habis. Mengarahkan ke halaman login...')
      // Redirect to login after a short delay
      setTimeout(() => window.location.href = '/login', 1500)
    } else if (err.response?.status === 419) {
      alert('CSRF token expired atau session tidak valid.\n\nLangkah perbaikan:\n1. Refresh halaman (F5)\n2. Login ulang\n3. Coba simpan lagi')
    } else {
      alert('Gagal menyimpan: ' + (err.response?.data?.message || err.message))
    }
  } finally {
    saving.value = false
  }
}

// Helper to reload settings after save
async function loadSettings() {
  try {
    const [siteRes, systemRes] = await Promise.all([
      axios.get(`${API_URL}/api/settings`),
      axios.get(`${API_URL}/api/admin/system-settings`, { withCredentials: true }).catch(() => ({ data: {} }))
    ])
    
    const siteData = siteRes.data || {}
    const sysData = systemRes.data || {}

    settings.value = {
      site_title: siteData.site_title || '',
      homepage_seo_title: siteData.homepage_seo_title || '',
      homepage_meta_description: siteData.homepage_meta_description || '',
      whatsapp_number: siteData.whatsapp_number || '',
      logo_image: siteData.logo_image || null,
      favicon_image: siteData.favicon_image || null,
    }

    verification.value = {
      google_search_console: siteData.google_site_verification || sysData.google_search_console || '',
      google_analytics: sysData.google_analytics || '',
      facebook_domain_verification: sysData.facebook_domain_verification || '',
      bing_webmaster: siteData.bing_site_verification || sysData.bing_webmaster || '',
    }

    r2.value = {
      access_key_id: sysData.r2_access_key_id || r2.value.access_key_id,
      secret_access_key: '',
      bucket_name: sysData.r2_bucket_name || r2.value.bucket_name,
      endpoint: sysData.r2_endpoint || r2.value.endpoint,
      public_url: sysData.r2_public_url || r2.value.public_url
    }
  } catch (err) {
    console.error('Failed to reload settings:', err)
  }
}

async function loadRedirects() {
  try {
    const res = await axios.get(`${API_URL}/api/admin/redirects`, { withCredentials: true })
    redirects.value = res.data
  } catch (err) {
    console.error('Failed to load redirects:', err)
  }
}

async function addRedirect() {
  if (!newRedirect.value.from_url || !newRedirect.value.to_url) return
  redirectSaving.value = true
  try {
    await axios.post(`${API_URL}/api/admin/redirects`, newRedirect.value, { withCredentials: true })
    newRedirect.value = { from_url: '', to_url: '', status_code: 301, is_active: true }
    await loadRedirects()
  } catch (err) {
    console.error('Failed to add redirect:', err)
  } finally {
    redirectSaving.value = false
  }
}

async function toggleRedirect(redirect) {
  try {
    await axios.put(`${API_URL}/api/admin/redirects/${redirect.id}`, {
      is_active: redirect.is_active
    }, { withCredentials: true })
  } catch (err) {
    console.error('Failed to toggle redirect:', err)
    redirect.is_active = !redirect.is_active
  }
}

async function deleteRedirect(redirect) {
  if (!confirm('Hapus redirect ini?')) return
  try {
    await axios.delete(`${API_URL}/api/admin/redirects/${redirect.id}`, { withCredentials: true })
    await loadRedirects()
  } catch (err) {
    console.error('Failed to delete redirect:', err)
  }
}
</script>

<template>
  <div class="max-w-4xl">
    <h2 class="text-xl font-semibold mb-6">Pengaturan</h2>
    
    <!-- Tabs -->
    <div class="flex gap-1 p-1 bg-secondary rounded-lg mb-6 w-fit">
      <button 
        @click="activeTab = 'general'"
        :class="['flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md transition-colors', 
          activeTab === 'general' ? 'bg-background shadow-sm' : 'text-muted-foreground hover:text-foreground']"
      >
        <Globe class="w-4 h-4" />
        Umum
      </button>
      <button 
        @click="activeTab = 'verification'"
        :class="['flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md transition-colors', 
          activeTab === 'verification' ? 'bg-background shadow-sm' : 'text-muted-foreground hover:text-foreground']"
      >
        <Shield class="w-4 h-4" />
        Verifikasi
      </button>
      <button 
        @click="activeTab = 'r2'"
        :class="['flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md transition-colors', 
          activeTab === 'r2' ? 'bg-background shadow-sm' : 'text-muted-foreground hover:text-foreground']"
      >
        <Cloud class="w-4 h-4" />
        R2 Cloudflare
      </button>
      <button 
        @click="activeTab = 'redirects'"
        :class="['flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md transition-colors', 
          activeTab === 'redirects' ? 'bg-background shadow-sm' : 'text-muted-foreground hover:text-foreground']"
      >
        <ArrowLeftRight class="w-4 h-4" />
        Redirects
      </button>
    </div>
    
    <Card v-if="loading" class="p-12 text-center">
      <Loader2 class="w-6 h-6 animate-spin mx-auto text-muted-foreground" />
    </Card>
    
    <Card v-else class="p-6">
      <!-- General Tab -->
      <div v-if="activeTab === 'general'" class="space-y-6">
        <div class="space-y-2">
          <Label>Site Title</Label>
          <Input v-model="settings.site_title" placeholder="Lanyard Bagus" />
        </div>
        
        <div class="space-y-2">
          <Label>Homepage SEO Title</Label>
          <Input v-model="settings.homepage_seo_title" placeholder="Jasa Cetak Lanyard Custom Premium" />
        </div>
        
        <div class="space-y-2">
          <Label>Meta Description</Label>
          <Input v-model="settings.homepage_meta_description" placeholder="Deskripsi untuk SEO..." />
        </div>
        
        <div class="space-y-2">
          <Label>WhatsApp Number</Label>
          <Input v-model="settings.whatsapp_number" placeholder="081234567890" />
        </div>
        
        <div class="space-y-3 pt-4 border-t">
          <Label>Brand Assets</Label>
          <div class="flex gap-6">
            <MediaPicker 
              v-model="settings.logo_image" 
              label="Logo Website" 
            />
            <MediaPicker 
              v-model="settings.favicon_image" 
              label="Favicon" 
            />
          </div>
        </div>
      </div>
      
      <!-- Verification Tab -->
      <div v-if="activeTab === 'verification'" class="space-y-6">
        <div class="space-y-2">
          <Label>Google Search Console</Label>
          <Input v-model="verification.google_search_console" placeholder="google-site-verification=..." />
          <p class="text-xs text-muted-foreground">Masukkan meta tag verification</p>
        </div>
        
        <div class="space-y-2">
          <Label>Google Analytics</Label>
          <Input v-model="verification.google_analytics" placeholder="G-XXXXXXXXXX atau UA-XXXXXXXX-X" />
        </div>
        
        <div class="space-y-2">
          <Label>Facebook Domain Verification</Label>
          <Input v-model="verification.facebook_domain_verification" placeholder="facebook-domain-verification=..." />
        </div>
        
        <div class="space-y-2">
          <Label>Bing Webmaster</Label>
          <Input v-model="verification.bing_webmaster" placeholder="msvalidate.01=..." />
        </div>
      </div>
      
      <!-- R2 Cloudflare Tab -->
      <div v-if="activeTab === 'r2'" class="space-y-6">
        <div class="p-4 bg-secondary/50 rounded-lg">
          <p class="text-sm text-muted-foreground">
            Pengaturan Cloudflare R2 untuk storage media. 
            <a href="https://developers.cloudflare.com/r2/" target="_blank" class="text-foreground underline">Lihat dokumentasi</a>
          </p>
        </div>
        
        <div class="space-y-2">
          <Label>Endpoint URL</Label>
          <Input v-model="r2.endpoint" placeholder="https://xxxx.r2.cloudflarestorage.com" />
          <p class="text-xs text-muted-foreground">Contoh: https://{account-id}.r2.cloudflarestorage.com</p>
        </div>
        
        <div class="space-y-2">
          <Label>Access Key ID</Label>
          <Input v-model="r2.access_key_id" placeholder="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" />
        </div>
        
        <div class="space-y-2">
          <Label>Secret Access Key</Label>
          <Input v-model="r2.secret_access_key" type="password" placeholder="••••••••••••••••••••••••" />
          <p class="text-xs text-muted-foreground">Kosongkan jika tidak ingin mengubah</p>
        </div>
        
        <div class="space-y-2">
          <Label>Bucket Name</Label>
          <Input v-model="r2.bucket_name" placeholder="my-bucket" />
        </div>
        
        <div class="space-y-2">
          <Label>Public URL</Label>
          <Input v-model="r2.public_url" placeholder="https://pub-xxx.r2.dev" />
          <p class="text-xs text-muted-foreground">URL publik untuk mengakses file (optional)</p>
        </div>
      </div>

      <!-- Redirects Tab -->
      <div v-if="activeTab === 'redirects'" class="space-y-6">
        <div class="p-4 bg-secondary/50 rounded-lg">
          <p class="text-sm text-muted-foreground">
            Kelola redirect 301/302. URL yang tidak ditemukan (404) akan dicek di sini sebelum menampilkan halaman error.
          </p>
        </div>

        <!-- Add new redirect form -->
        <div class="border rounded-lg p-4 space-y-4">
          <p class="text-sm font-medium">Tambah Redirect Baru</p>
          <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div class="space-y-1">
              <Label>URL Asal (From)</Label>
              <Input v-model="newRedirect.from_url" placeholder="/url-lama" />
            </div>
            <div class="space-y-1">
              <Label>URL Tujuan (To)</Label>
              <Input v-model="newRedirect.to_url" placeholder="/url-baru atau https://..." />
            </div>
          </div>
          <div class="flex items-center gap-4">
            <div class="space-y-1">
              <Label>Status Code</Label>
              <select v-model="newRedirect.status_code" class="flex h-9 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                <option :value="301">301 – Permanent</option>
                <option :value="302">302 – Temporary</option>
                <option :value="307">307 – Temporary (method preserved)</option>
                <option :value="308">308 – Permanent (method preserved)</option>
              </select>
            </div>
            <div class="flex items-center gap-2 mt-5">
              <input type="checkbox" id="new-active" v-model="newRedirect.is_active" class="rounded border-input" />
              <label for="new-active" class="text-sm">Aktif</label>
            </div>
          </div>
          <Button @click="addRedirect" :disabled="redirectSaving || !newRedirect.from_url || !newRedirect.to_url" variant="default" size="sm">
            <Loader2 v-if="redirectSaving" class="w-4 h-4 mr-2 animate-spin" />
            <Plus v-else class="w-4 h-4 mr-2" />
            Tambah Redirect
          </Button>
        </div>

        <!-- Redirect list -->
        <div v-if="redirects.length === 0" class="text-center py-8 text-muted-foreground text-sm">
          Belum ada redirect. Tambahkan redirect pertama di atas.
        </div>
        <div v-else class="border rounded-lg overflow-hidden">
          <table class="w-full text-sm">
            <thead class="bg-secondary/50">
              <tr>
                <th class="text-left px-4 py-2 font-medium">From</th>
                <th class="text-left px-4 py-2 font-medium">To</th>
                <th class="text-center px-4 py-2 font-medium">Code</th>
                <th class="text-center px-4 py-2 font-medium">Aktif</th>
                <th class="px-4 py-2"></th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <tr v-for="r in redirects" :key="r.id" class="hover:bg-secondary/20">
                <td class="px-4 py-2 font-mono text-xs truncate max-w-[180px]">{{ r.from_url }}</td>
                <td class="px-4 py-2 font-mono text-xs truncate max-w-[180px]">{{ r.to_url }}</td>
                <td class="px-4 py-2 text-center">
                  <span class="inline-block px-2 py-0.5 rounded text-xs font-medium" :class="r.status_code === 301 ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'">
                    {{ r.status_code }}
                  </span>
                </td>
                <td class="px-4 py-2 text-center">
                  <input type="checkbox" v-model="r.is_active" @change="toggleRedirect(r)" class="rounded border-input cursor-pointer" />
                </td>
                <td class="px-4 py-2 text-right">
                  <button @click="deleteRedirect(r)" class="text-muted-foreground hover:text-destructive transition-colors">
                    <Trash2 class="w-4 h-4" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <div class="flex items-center gap-4 pt-6 mt-6 border-t" v-if="activeTab !== 'redirects'">
        <Button @click="saveSettings" :disabled="saving" variant="default">
          <Loader2 v-if="saving" class="w-4 h-4 mr-2 animate-spin" />
          <Check v-else-if="saved" class="w-4 h-4 mr-2" />
          {{ saving ? 'Menyimpan...' : saved ? 'Tersimpan!' : 'Simpan Perubahan' }}
        </Button>
      </div>
    </Card>
  </div>
</template>

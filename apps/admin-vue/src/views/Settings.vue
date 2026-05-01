<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { Card, Button, Input, Label } from '../components/ui'
import MediaPicker from '../components/MediaPicker.vue'
import { useAuthStore } from '../stores/auth'
import { Globe, Shield, Cloud, Check, Loader2 } from 'lucide-vue-next'
import axios from 'axios'

const router = useRouter()
const auth = useAuthStore()

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000'

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
  // Check if recently logged in (within last 5 minutes)
  const lastLogin = localStorage.getItem('lastLoginTime')
  const recentlyLoggedIn = lastLogin && (Date.now() - parseInt(lastLogin) < 5 * 60 * 1000)
  
  // Step 1: Check if session is valid (with retry if not recent login)
  let isAuth = recentlyLoggedIn ? true : await checkAuth()
  
  // If first check fails, wait and retry once
  if (!isAuth) {
    console.log('First auth check failed, waiting and retrying...')
    await new Promise(r => setTimeout(r, 500))
    isAuth = await checkAuth()
  }
  
  if (!isAuth) {
    console.log('Not authenticated, redirecting to login...')
    auth.logout()
    router.push('/login')
    return
  }

  // Step 2: Load site settings first (public endpoint)
  try {
    const siteRes = await axios.get(`${API_URL}/api/settings`)
    settings.value = {
      site_title: siteRes.data.site_title || '',
      homepage_seo_title: siteRes.data.homepage_seo_title || '',
      homepage_meta_description: siteRes.data.homepage_meta_description || '',
      whatsapp_number: siteRes.data.whatsapp_number || '',
      logo_image: siteRes.data.logo_image || null,
      favicon_image: siteRes.data.favicon_image || null
    }
    
    // Load verification from google/bing site verification
    verification.value = {
      google_search_console: siteRes.data.google_site_verification || '',
      google_analytics: '',
      facebook_domain_verification: '',
      bing_webmaster: siteRes.data.bing_site_verification || ''
    }
  } catch (err) {
    console.error('Failed to load site settings:', err)
  }
  
  // Step 3: Get CSRF cookie before auth requests
  await ensureCsrfCookie()
  
  // Step 4: Load R2 and system settings (requires auth)
  try {
    const systemRes = await axios.get(`${API_URL}/api/admin/system-settings`, { withCredentials: true })
    const sysData = systemRes.data || {}
    
    // Merge verification data from system settings if available
    if (sysData.google_search_console) verification.value.google_search_console = sysData.google_search_console
    if (sysData.google_analytics) verification.value.google_analytics = sysData.google_analytics
    if (sysData.facebook_domain_verification) verification.value.facebook_domain_verification = sysData.facebook_domain_verification
    if (sysData.bing_webmaster) verification.value.bing_webmaster = sysData.bing_webmaster
    
    r2.value = {
      access_key_id: sysData.r2_access_key_id || '',
      secret_access_key: '',
      bucket_name: sysData.r2_bucket_name || '',
      endpoint: normalizeR2Endpoint(sysData.r2_endpoint || ''),
      public_url: normalizeR2PublicUrl(sysData.r2_public_url || '')
    }
  } catch (err) {
    if (err.response?.status === 401) {
      console.log('Session expired - redirecting to login')
      auth.logout()
      router.push('/login')
    } else {
      console.error('Failed to load system settings:', err)
    }
  }
  
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
    
    const sysData = systemRes.data || {}
    
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
      
      <div class="flex items-center gap-4 pt-6 mt-6 border-t">
        <Button @click="saveSettings" :disabled="saving" variant="default">
          <Loader2 v-if="saving" class="w-4 h-4 mr-2 animate-spin" />
          <Check v-else-if="saved" class="w-4 h-4 mr-2" />
          {{ saving ? 'Menyimpan...' : saved ? 'Tersimpan!' : 'Simpan Perubahan' }}
        </Button>
      </div>
    </Card>
  </div>
</template>

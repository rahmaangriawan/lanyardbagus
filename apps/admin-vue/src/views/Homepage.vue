<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { Card, Button, Input, Label } from '../components/ui'
import MediaPicker from '../components/MediaPicker.vue'
import { Save, Loader2, Plus, Trash2, RotateCcw, Globe, Sparkles, MessageSquare, Image as ImageIcon, 
Package, Layout, GripVertical } from 'lucide-vue-next'
import axios from 'axios'
import draggable from 'vuedraggable'

const props = defineProps({
  mode: {
    type: String,
    default: 'all' // all, header, footer, customize
  }
})

const API_URL = 'http://localhost:8000'
const loading = ref(true)
const saving = ref(false)
const saved = ref(false)
const activeTab = ref('header')

function defaultHomepage() {
  return {
    navigation_links: [
      { label: 'Beranda', href: '#beranda' },
      { label: 'Cerita Kami', href: '#cerita' },
      { label: 'Produk', href: '#produk' },
      { label: 'Harga', href: '#harga' },
      { label: 'FAQ', href: '#faq' },
    ],
    header_cta: {
      label: 'Pesan Sekarang',
      href: '#hubungi',
    },
    hero: {
      badge: 'TALI BERKUALITAS PREMIUM',
      title: 'Tali Berkualitas, Wujud Nyata Identitas Tim Anda.',
      description: 'Bayangkan seluruh tim Anda berjalan dengan penuh kebanggaan, mengenakan lanyard yang mewakili profesionalisme dan visi besar perusahaan.',
      button_text: 'Lihat Cerita Kami',
      button_url: '#cerita',
      secondary_button_text: 'Konsultasi Desain',
      secondary_button_url: '#hubungi',
      image_url: '/storage/media/hero-lanyard-5bc7f4.webp',
      card_image_url: '/storage/media/hero-lanyard-5bc7f4.webp',
      card_badge: 'Premium Quality',
    },
    problem: {
      badge: 'MENGAPA KAMI HADIR?',
      title: 'Membangun Identitas Seharusnya Tidak Membuat Anda Frustrasi',
      description: 'Kami tahu betapa melelahkannya mencari vendor yang benar-benar peduli pada detail acara Anda.',
      items: [
        {
          icon: 'calendar-x',
          title: 'Acara Sudah Dekat, Tali Belum Jadi',
          description: 'Vendor berjanji palsu dan membiarkan panitia cemas di hari-hari krusial.',
        },
        {
          icon: 'droplet',
          title: 'Warna Pudar yang Merusak Penampilan',
          description: 'Logo perusahaan indah, namun hasil cetaknya buram dan luntur dalam hitungan minggu.',
        },
        {
          icon: 'frown',
          title: 'Bahan Kaku dan Membuat Gatal',
          description: 'Lanyard berbahan kasar membuat tim Anda tidak nyaman saat bekerja seharian.',
        },
      ],
    },
    story: {
      badge: 'Cara Kami Bekerja',
      title: 'Perjalanan Menciptakan Kebanggaan',
      description: 'Bagi kami, ini bukan tentang seberapa banyak kami mencetak, tapi seberapa sempurna setiap helai tali mewakili nama besar Anda.',
      steps: [
        {
          number: '01',
          title: 'Material Pilihan yang Menghargai Kenyamanan Tim Anda',
          description: 'Serat kain yang halus di kulit dan nyaman dipakai seharian.',
          image_url: 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?auto=format&fit=crop&w=800&q=80',
        },
        {
          number: '02',
          title: 'Warna Tajam yang Meneriakkan Karakter Brand Anda',
          description: 'Detail warna diterjemahkan dengan presisi mutlak agar identitas tetap kuat.',
          image_url: 'https://images.unsplash.com/photo-1614316315266-9e9ebdb1ce45?auto=format&fit=crop&w=800&q=80',
        },
        {
          number: '03',
          title: 'Tiba Tepat Waktu, Jauh Sebelum Momen Besar Dimulai',
          description: 'Sistem produksi dirancang untuk selesai tepat waktu dan tanpa kompromi.',
          image_url: 'https://images.unsplash.com/photo-1587293852726-70cdb56c2866?auto=format&fit=crop&w=800&q=80',
        },
      ],
    },
    customize: {
      badge: 'Koleksi Kami',
      title: 'Pilih Kanvas untuk Identitas Anda',
      items: [
        {
          icon: 'printer',
          image_url: 'https://images.unsplash.com/photo-1628126235206-5260b9ea6441?auto=format&fit=crop&w=600&q=80',
          title: 'Printing Premium',
          description: 'Pilihan terpopuler. Mampu menampilkan gradasi warna dan desain kompleks tanpa batas dengan presisi luar biasa.',
          href: '#kontak',
        },
        {
          icon: 'badge-check',
          image_url: '',
          title: 'Paket Identitas (Lanyard + ID Card)',
          description: 'Harmonisasi sempurna. Tali dan kartu identitas yang dirancang senada untuk menciptakan kesatuan visual yang solid.',
          href: '#kontak',
        },
        {
          icon: 'layers',
          image_url: '',
          title: 'Lanyard 2 Sisi',
          description: 'Maksimalkan area desain Anda dengan cetakan bolak-balik. Sempurna untuk visibilitas brand ekstra dari segala sudut.',
          href: '#kontak',
        },
      ],
    },
    faq: {
      badge: 'Tanya Jawab',
      title: 'Pertanyaan Seputar Lanyard',
      items: [
        {
          question: 'Berapa minimum pemesanan lanyard custom?',
          answer: 'Kami menerima pemesanan dalam jumlah fleksibel. Untuk mendapatkan harga terbaik dan efisiensi produksi, kami merekomendasikan minimum order sebanyak 50 pcs. Namun, jangan ragu untuk berkonsultasi jika kebutuhan Anda di bawah jumlah tersebut.',
        },
        {
          question: 'Berapa lama waktu pengerjaan produksi?',
          answer: 'Standar waktu produksi kami berkisar antara 3 hingga 7 hari kerja tergantung dari jumlah pesanan dan antrean produksi. Kami juga menyediakan layanan prioritas (express) untuk kebutuhan mendesak dengan biaya tambahan.',
        },
        {
          question: 'Apakah saya bisa meminta dibuatkan desain?',
          answer: 'Tentu saja! Tim desain internal kami siap membantu memvisualisasikan ide Anda. Layanan desain dasar ini kami berikan secara gratis khusus untuk pelanggan yang telah melakukan pembayaran uang muka pesanan.',
        },
        {
          question: 'Bahan apa yang paling direkomendasikan?',
          answer: 'Untuk hasil cetak paling tajam dengan kenyamanan maksimal, kami sangat menyarankan bahan Tissue (Polyester halus) dipadukan dengan teknik Printing Premium. Bahan ini sangat lembut di leher dan mampu menyerap tinta sublimasi dengan sempurna.',
        },
      ],
    },
    cta: {
      title: 'Setiap Tim Hebat Memiliki Cerita.\nMari Cetak Cerita Anda Bersama Kami.',
      description: 'Jangan biarkan desain terbaik Anda rusak oleh vendor yang salah. Hubungi kami hari ini untuk konsultasi bahan, desain gratis, dan amankan jadwal produksi Anda.',
      primary_label: 'Pesan via WhatsApp',
      primary_href: 'whatsapp',
      secondary_label: 'Lihat Daftar Harga',
      secondary_href: '#produk',
    },
    footer: {
      navigation_title: 'Navigasi',
      navigation_links: [
        { label: 'Beranda', href: '/' },
        { label: 'Tentang Kami', href: '/tentang' },
        { label: 'Jenis Material', href: '/jenis' },
        { label: 'Daftar Harga', href: '/harga' },
      ],
      insights_title: 'Insights',
      insights_links: [
        { label: 'Semua Artikel', href: '/blog' },
      ],
      layanan_title: 'Layanan',
      layanan_links: [
        { label: 'Kontak Kami', href: '/kontak' },
        { label: 'Info Pengiriman', href: '/pengiriman' },
        { label: 'Kebijakan & Syarat', href: '/kebijakan' },
      ],
      social_links: [
        { icon: 'instagram', href: '#' },
        { icon: 'facebook', href: '#' },
        { icon: 'youtube', href: '#' },
      ],
      copyright_text: 'Dibuat dengan dedikasi untuk branding identitas.',
    },
  }
}

const homepage = ref(defaultHomepage())

const tabs = [
  { key: 'header', label: 'Header Menu', icon: Globe },
  { key: 'hero', label: 'Hero', icon: Sparkles },
  { key: 'problem', label: 'Problem Cards', icon: MessageSquare },
  { key: 'customize', label: 'Koleksi', icon: Package },
  { key: 'story', label: 'Story Steps', icon: ImageIcon },
  { key: 'faq', label: 'FAQ', icon: MessageSquare },
  { key: 'cta', label: 'CTA Section', icon: Sparkles },
  { key: 'footer', label: 'Footer', icon: Layout },
]

const filteredTabs = computed(() => {
  if (props.mode === 'header') return tabs.filter(t => t.key === 'header')
  if (props.mode === 'footer') return tabs.filter(t => t.key === 'footer')
  if (props.mode === 'customize') return tabs.filter(t => t.key !== 'header' && t.key !== 'footer')
  return tabs
})

const pageTitle = computed(() => {
  if (props.mode === 'header') return 'Header'
  if (props.mode === 'footer') return 'Footer'
  return 'Customize Beranda'
})

const pageDescription = computed(() => {
  if (props.mode === 'header') return 'Kelola menu navigasi dan tombol CTA di bagian atas website.'
  if (props.mode === 'footer') return 'Kelola semua konten, link, dan informasi di bagian bawah website.'
  return 'Kelola konten visual dan informasi utama yang tampil di halaman depan.'
})

function setInitialTab() {
  if (props.mode === 'header') activeTab.value = 'header'
  else if (props.mode === 'footer') activeTab.value = 'footer'
  else if (props.mode === 'customize') activeTab.value = 'hero'
  else activeTab.value = 'header'
}

watch(() => props.mode, setInitialTab)

function mergeData(source = {}) {
  const defaults = defaultHomepage()
  const merged = {
    ...defaults,
    ...source,
    header_cta: { ...defaults.header_cta, ...(source.header_cta || {}) },
    hero: { ...defaults.hero, ...(source.hero || {}) },
    problem: {
      ...defaults.problem,
      ...(source.problem || {}),
      items: Array.isArray(source.problem?.items) && source.problem.items.length ? source.problem.items : defaults.problem.items,
    },
    story: {
      ...defaults.story,
      ...(source.story || {}),
      steps: Array.isArray(source.story?.steps) && source.story.steps.length ? source.story.steps : defaults.story.steps,
    },
    customize: {
      ...defaults.customize,
      ...(source.customize || {}),
      items: Array.isArray(source.customize?.items) && source.customize.items.length ? source.customize.items : defaults.customize.items,
    },
    faq: {
      ...defaults.faq,
      ...(source.faq || {}),
      items: Array.isArray(source.faq?.items) && source.faq.items.length ? source.faq.items : defaults.faq.items,
    },
    cta: {
      ...defaults.cta,
      ...(source.cta || {}),
    },
    footer: {
      ...defaults.footer,
      ...(source.footer || {}),
      navigation_links: Array.isArray(source.footer?.navigation_links) && source.footer.navigation_links.length ? source.footer.navigation_links : defaults.footer.navigation_links,
      insights_links: Array.isArray(source.footer?.insights_links) && source.footer.insights_links.length ? source.footer.insights_links : defaults.footer.insights_links,
      layanan_links: Array.isArray(source.footer?.layanan_links) && source.footer.layanan_links.length ? source.footer.layanan_links : defaults.footer.layanan_links,
    },
    navigation_links: Array.isArray(source.navigation_links) && source.navigation_links.length ? source.navigation_links : defaults.navigation_links,
  }
  return merged
}

function clone(value) {
  return JSON.parse(JSON.stringify(value))
}

async function loadHomepage() {
  loading.value = true
  try {
    const res = await axios.get(`${API_URL}/api/admin/homepage`, { withCredentials: true })
    homepage.value = mergeData(res.data?.homepage_data || res.data || {})
  } catch (err) {
    console.error('Failed to load homepage:', err)
    homepage.value = defaultHomepage()
  } finally {
    loading.value = false
  }
}

async function saveHomepage() {
  saving.value = true
  saved.value = false
  try {
    await axios.put(`${API_URL}/api/admin/homepage`, { homepage_data: clone(homepage.value) }, { withCredentials: true })
    saved.value = true
    setTimeout(() => (saved.value = false), 2500)
  } catch (err) {
    alert('Gagal menyimpan: ' + (err.response?.data?.message || err.message))
  } finally {
    saving.value = false
  }
}

function resetHomepage() {
  if (confirm('Apakah Anda yakin ingin meriset bagian ini ke default?')) {
    homepage.value = defaultHomepage()
  }
}

function addNavLink() {
  homepage.value.navigation_links.push({ label: '', href: '' })
}

function removeNavLink(index) {
  homepage.value.navigation_links.splice(index, 1)
}

function addProblemItem() {
  homepage.value.problem.items.push({ icon: 'sparkles', title: '', description: '' })
}

function removeProblemItem(index) {
  homepage.value.problem.items.splice(index, 1)
}

function addStoryStep() {
  const number = String(homepage.value.story.steps.length + 1).padStart(2, '0')
  homepage.value.story.steps.push({
    number,
    title: '',
    description: '',
    image_url: '',
  })
}

function removeStoryStep(index) {
  homepage.value.story.steps.splice(index, 1)
}

function addCustomizeItem() {
  homepage.value.customize.items.push({
    icon: 'package',
    image_url: '',
    title: '',
    description: '',
    href: '#kontak',
  })
}

function removeCustomizeItem(index) {
  homepage.value.customize.items.splice(index, 1)
}

function addFaqItem() {
  homepage.value.faq.items.push({ question: '', answer: '' })
}

function removeFaqItem(index) {
  homepage.value.faq.items.splice(index, 1)
}

function addFooterLink(section) {
  homepage.value.footer[section].push({ label: '', href: '' })
}

function removeFooterLink(section, index) {
  homepage.value.footer[section].splice(index, 1)
}

function setMedia(targetPath, media) {
  const value = media?.url || ''
  const parts = targetPath.split('.')
  let cursor = homepage.value
  for (let i = 0; i < parts.length - 1; i += 1) {
    cursor = cursor[parts[i]]
  }
  cursor[parts[parts.length - 1]] = value
}

const heroBackgroundPreview = computed(() => homepage.value.hero.image_url ? { url: homepage.value.hero.image_url } : null)
const heroCardPreview = computed(() => homepage.value.hero.card_image_url ? { url: homepage.value.hero.card_image_url } : null)

onMounted(() => {
  setInitialTab()
  loadHomepage()
})
</script>

<template>
  <div class="space-y-6">
    <div class="flex items-start justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold">{{ pageTitle }}</h1>
        <p class="text-muted-foreground mt-1">{{ pageDescription }}</p>
      </div>

      <div class="flex items-center gap-2">
        <Button variant="outline" @click="resetHomepage">
          <RotateCcw class="w-4 h-4 mr-2" />
          Reset Default
        </Button>
        <Button @click="saveHomepage" :disabled="saving">
          <Loader2 v-if="saving" class="w-4 h-4 mr-2 animate-spin" />
          <Save v-else class="w-4 h-4 mr-2" />
          {{ saving ? 'Menyimpan...' : 'Simpan Perubahan' }}
        </Button>
      </div>
    </div>

    <div v-if="saved" class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
      Berhasil disimpan.
    </div>

    <!-- Only show tabs if there is more than one filtered tab -->
    <div v-if="filteredTabs.length > 1" class="flex flex-wrap gap-1 p-1 bg-secondary rounded-lg mb-6 w-fit">
      <button
        v-for="tab in filteredTabs"
        :key="tab.key"
        @click="activeTab = tab.key"
        :class="[
          'inline-flex items-center gap-2 rounded-md px-4 py-2 text-sm font-medium transition-all',
          activeTab === tab.key 
            ? 'bg-background text-foreground shadow-sm' 
            : 'text-muted-foreground hover:text-foreground'
        ]"
      >
        <component :is="tab.icon" class="w-4 h-4" />
        {{ tab.label }}
      </button>
    </div>

    <Card v-if="loading" class="p-12 text-center">
      <Loader2 class="w-6 h-6 animate-spin mx-auto text-muted-foreground" />
    </Card>

    <div v-else class="space-y-6">
      <Card v-if="activeTab === 'header'" class="p-6 space-y-6">
        <div>
          <h2 class="text-lg font-semibold">Header Menu</h2>
          <p class="text-sm text-muted-foreground">Atur menu navigasi dan tombol CTA di header publik.</p>
        </div>

        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <Label>Navigation Links</Label>
            <Button variant="outline" size="sm" @click="addNavLink">
              <Plus class="w-4 h-4 mr-2" />
              Tambah Link
            </Button>
          </div>

          <div class="space-y-3">
            <draggable 
              v-model="homepage.navigation_links" 
              item-key="index"
              handle=".handle"
              class="space-y-3"
            >
              <template #item="{ element, index }">
                <div class="grid gap-3 md:grid-cols-[auto_1fr_1.2fr_auto] items-center rounded-lg border p-3 bg-card">
                  <div class="handle cursor-grab active:cursor-grabbing p-1 text-muted-foreground/50 hover:text-foreground transition-colors">
                    <GripVertical class="w-4 h-4" />
                  </div>
                  <Input v-model="element.label" placeholder="Label menu" />
                  <Input v-model="element.href" placeholder="Tautan, contoh: #beranda atau /produk" />
                  <Button variant="ghost" size="icon" class="text-destructive" @click="removeNavLink(index)">
                    <Trash2 class="w-4 h-4" />
                  </Button>
                </div>
              </template>
            </draggable>
          </div>

          <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-2">
              <Label>CTA Text</Label>
              <Input v-model="homepage.header_cta.label" placeholder="Pesan Sekarang" />
            </div>
            <div class="space-y-2">
              <Label>CTA Link</Label>
              <Input v-model="homepage.header_cta.href" placeholder="#hubungi atau /kontak" />
            </div>
          </div>
        </div>
      </Card>

      <Card v-else-if="activeTab === 'hero'" class="p-6 space-y-6">
        <div>
          <h2 class="text-lg font-semibold">Hero Section</h2>
          <p class="text-sm text-muted-foreground">Semua konten utama di bagian atas halaman depan.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
          <div class="space-y-4">
            <div class="space-y-2">
              <Label>Badge</Label>
              <Input v-model="homepage.hero.badge" placeholder="Badge hero" />
            </div>

            <div class="space-y-2">
              <Label>Judul</Label>
              <Input v-model="homepage.hero.title" placeholder="Judul hero" />
            </div>

            <div class="space-y-2">
              <Label>Deskripsi</Label>
              <textarea v-model="homepage.hero.description" rows="4" class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm"></textarea>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
              <div class="space-y-2">
                <Label>Teks Tombol Utama</Label>
                <Input v-model="homepage.hero.button_text" placeholder="Lihat Cerita Kami" />
              </div>
              <div class="space-y-2">
                <Label>Link Tombol Utama</Label>
                <Input v-model="homepage.hero.button_url" placeholder="#cerita" />
              </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
              <div class="space-y-2">
                <Label>Teks Tombol Sekunder</Label>
                <Input v-model="homepage.hero.secondary_button_text" placeholder="Konsultasi Desain" />
              </div>
              <div class="space-y-2">
                <Label>Link Tombol Sekunder</Label>
                <Input v-model="homepage.hero.secondary_button_url" placeholder="#hubungi" />
              </div>
            </div>
          </div>

          <div class="space-y-4">
            <div class="grid gap-4 md:grid-cols-2">
              <div class="space-y-2">
                <Label>Gambar Background</Label>
                <MediaPicker
                  :model-value="heroBackgroundPreview"
                  label="Pilih background hero"
                  @update:modelValue="media => setMedia('hero.image_url', media)"
                />
              </div>
              <div class="space-y-2">
                <Label>Gambar Kartu 3D</Label>
                <MediaPicker
                  :model-value="heroCardPreview"
                  label="Pilih kartu hero"
                  @update:modelValue="media => setMedia('hero.card_image_url', media)"
                />
              </div>
            </div>

            <div class="space-y-2">
              <Label>Badge Kartu</Label>
              <Input v-model="homepage.hero.card_badge" placeholder="Premium Quality" />
            </div>
          </div>
        </div>
      </Card>

      <Card v-else-if="activeTab === 'problem'" class="p-6 space-y-6">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h2 class="text-lg font-semibold">Problem Cards</h2>
            <p class="text-sm text-muted-foreground">Bagian yang menjelaskan masalah user sebelum mereka memesan.</p>
          </div>
          <Button variant="outline" size="sm" @click="addProblemItem">
            <Plus class="w-4 h-4 mr-2" />
            Tambah Card
          </Button>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
          <div class="space-y-2">
            <Label>Badge</Label>
            <Input v-model="homepage.problem.badge" placeholder="Mengapa Kami Hadir?" />
          </div>
          <div class="space-y-2">
            <Label>Judul</Label>
            <Input v-model="homepage.problem.title" placeholder="Judul section" />
          </div>
        </div>
        <div class="space-y-2">
          <Label>Deskripsi</Label>
          <textarea v-model="homepage.problem.description" rows="3" class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm"></textarea>
        </div>

        <div class="space-y-4">
          <div
            v-for="(item, index) in homepage.problem.items"
            :key="index"
            class="rounded-lg border p-4 space-y-3"
          >
            <div class="flex items-center justify-between">
              <h3 class="font-medium">Card {{ index + 1 }}</h3>
              <Button variant="ghost" size="icon" class="text-destructive" @click="removeProblemItem(index)">
                <Trash2 class="w-4 h-4" />
              </Button>
            </div>
            <div class="grid gap-4 md:grid-cols-3">
              <div class="space-y-2">
                <Label>Icon</Label>
                <Input v-model="item.icon" placeholder="calendar-x" />
              </div>
              <div class="space-y-2 md:col-span-2">
                <Label>Judul Card</Label>
                <Input v-model="item.title" placeholder="Judul card" />
              </div>
            </div>
            <div class="space-y-2">
              <Label>Deskripsi Card</Label>
              <textarea v-model="item.description" rows="3" class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm"></textarea>
            </div>
          </div>
        </div>
      </Card>

      <Card v-else-if="activeTab === 'customize'" class="p-6 space-y-6">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h2 class="text-lg font-semibold">Koleksi Section</h2>
            <p class="text-sm text-muted-foreground">Bagian koleksi / produk yang tampil di homepage publik.</p>
          </div>
          <Button variant="outline" size="sm" @click="addCustomizeItem">
            <Plus class="w-4 h-4 mr-2" />
            Tambah Item
          </Button>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
          <div class="space-y-2">
            <Label>Badge</Label>
            <Input v-model="homepage.customize.badge" placeholder="Koleksi Kami" />
          </div>
          <div class="space-y-2">
            <Label>Judul</Label>
            <Input v-model="homepage.customize.title" placeholder="Pilih Kanvas untuk Identitas Anda" />
          </div>
        </div>

        <div class="space-y-4">
          <div
            v-for="(item, index) in homepage.customize.items"
            :key="index"
            class="rounded-lg border p-4 space-y-4"
          >
            <div class="flex items-center justify-between">
              <h3 class="font-medium">Item {{ index + 1 }}</h3>
              <Button variant="ghost" size="icon" class="text-destructive" @click="removeCustomizeItem(index)">
                <Trash2 class="w-4 h-4" />
              </Button>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
              <div class="space-y-2">
                <Label>Icon</Label>
                <Input v-model="item.icon" placeholder="printer / badge-check / layers" />
              </div>
              <div class="space-y-2">
                <Label>Link</Label>
                <Input v-model="item.href" placeholder="#kontak" />
              </div>
            </div>

            <div class="space-y-2">
              <Label>Gambar</Label>
              <MediaPicker
                :model-value="item.image_url ? { url: item.image_url } : null"
                label="Pilih gambar item"
                @update:modelValue="media => setMedia(`customize.items.${index}.image_url`, media)"
              />
            </div>

            <div class="space-y-2">
              <Label>Judul</Label>
              <Input v-model="item.title" placeholder="Judul item" />
            </div>

            <div class="space-y-2">
              <Label>Deskripsi</Label>
              <textarea v-model="item.description" rows="3" class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm"></textarea>
            </div>
          </div>
        </div>
      </Card>

      <Card v-else-if="activeTab === 'faq'" class="p-6 space-y-6">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h2 class="text-lg font-semibold">FAQ</h2>
            <p class="text-sm text-muted-foreground">Kelola pertanyaan yang tampil di bagian Tanya Jawab homepage.</p>
          </div>
          <Button variant="outline" size="sm" @click="addFaqItem">
            <Plus class="w-4 h-4 mr-2" />
            Tambah FAQ
          </Button>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
          <div class="space-y-2">
            <Label>Badge</Label>
            <Input v-model="homepage.faq.badge" placeholder="Tanya Jawab" />
          </div>
          <div class="space-y-2">
            <Label>Judul</Label>
            <Input v-model="homepage.faq.title" placeholder="Pertanyaan Seputar Lanyard" />
          </div>
        </div>

        <div class="space-y-4">
          <div
            v-for="(item, index) in homepage.faq.items"
            :key="index"
            class="rounded-lg border p-4 space-y-4"
          >
            <div class="flex items-center justify-between">
              <h3 class="font-medium">FAQ {{ index + 1 }}</h3>
              <Button variant="ghost" size="icon" class="text-destructive" @click="removeFaqItem(index)">
                <Trash2 class="w-4 h-4" />
              </Button>
            </div>

            <div class="space-y-2">
              <Label>Pertanyaan</Label>
              <Input v-model="item.question" placeholder="Pertanyaan FAQ" />
            </div>

            <div class="space-y-2">
              <Label>Jawaban</Label>
              <textarea v-model="item.answer" rows="4" class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm"></textarea>
            </div>
          </div>
        </div>
      </Card>

      <Card v-else-if="activeTab === 'cta'" class="p-6 space-y-6">
        <div>
          <h2 class="text-lg font-semibold">CTA Section</h2>
          <p class="text-sm text-muted-foreground">Bagian ajakan utama di bawah homepage publik.</p>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
          <div class="space-y-2 lg:col-span-2">
            <Label>Judul</Label>
            <textarea v-model="homepage.cta.title" rows="3" class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm"></textarea>
          </div>
          <div class="space-y-2 lg:col-span-2">
            <Label>Deskripsi</Label>
            <textarea v-model="homepage.cta.description" rows="4" class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm"></textarea>
          </div>
          <div class="space-y-2">
            <Label>Tombol Utama</Label>
            <Input v-model="homepage.cta.primary_label" placeholder="Pesan via WhatsApp" />
          </div>
          <div class="space-y-2">
            <Label>Link Utama</Label>
            <Input v-model="homepage.cta.primary_href" placeholder="whatsapp atau https://wa.me/..." />
          </div>
          <div class="space-y-2">
            <Label>Tombol Sekunder</Label>
            <Input v-model="homepage.cta.secondary_label" placeholder="Lihat Daftar Harga" />
          </div>
          <div class="space-y-2">
            <Label>Link Sekunder</Label>
            <Input v-model="homepage.cta.secondary_href" placeholder="#produk" />
          </div>
        </div>
      </Card>

      <Card v-else-if="activeTab === 'story'" class="p-6 space-y-6">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h2 class="text-lg font-semibold">Story Steps</h2>
            <p class="text-sm text-muted-foreground">Langkah-langkah cerita / perjalanan brand di homepage.</p>
          </div>
          <Button variant="outline" size="sm" @click="addStoryStep">
            <Plus class="w-4 h-4 mr-2" />
            Tambah Step
          </Button>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
          <div class="space-y-2">
            <Label>Badge</Label>
            <Input v-model="homepage.story.badge" placeholder="Cara Kami Bekerja" />
          </div>
          <div class="space-y-2">
            <Label>Judul</Label>
            <Input v-model="homepage.story.title" placeholder="Judul section" />
          </div>
        </div>
        <div class="space-y-2">
          <Label>Deskripsi</Label>
          <textarea v-model="homepage.story.description" rows="3" class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm"></textarea>
        </div>

        <div class="space-y-4">
          <div
            v-for="(step, index) in homepage.story.steps"
            :key="index"
            class="rounded-lg border p-4 space-y-4"
          >
            <div class="flex items-center justify-between">
              <h3 class="font-medium">Step {{ index + 1 }}</h3>
              <Button variant="ghost" size="icon" class="text-destructive" @click="removeStoryStep(index)">
                <Trash2 class="w-4 h-4" />
              </Button>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
              <div class="space-y-2">
                <Label>Nomor</Label>
                <Input v-model="step.number" placeholder="01" />
              </div>
              <div class="space-y-2">
                <Label>Judul</Label>
                <Input v-model="step.title" placeholder="Judul step" />
              </div>
            </div>
            <div class="space-y-2">
              <Label>Deskripsi</Label>
              <textarea v-model="step.description" rows="3" class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm"></textarea>
            </div>
            <div class="space-y-2">
              <Label>Gambar</Label>
              <MediaPicker
                :model-value="step.image_url ? { url: step.image_url } : null"
                label="Pilih gambar step"
                @update:modelValue="media => setMedia(`story.steps.${index}.image_url`, media)"
              />
            </div>
          </div>
        </div>
      </Card>

      <Card v-else-if="activeTab === 'footer'" class="p-6 space-y-8">
        <div>
          <h2 class="text-lg font-semibold">Footer Management</h2>
          <p class="text-sm text-muted-foreground">Kelola semua konten, link, dan informasi di bagian bawah website.</p>
        </div>

        <!-- Section Titles -->
        <div class="grid gap-4 md:grid-cols-3">
          <div class="space-y-2">
            <Label>Judul Kolom 1</Label>
            <Input v-model="homepage.footer.navigation_title" />
          </div>
          <div class="space-y-2">
            <Label>Judul Kolom 2</Label>
            <Input v-model="homepage.footer.insights_title" />
          </div>
          <div class="space-y-2">
            <Label>Judul Kolom 3</Label>
            <Input v-model="homepage.footer.layanan_title" />
          </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-2">
          <!-- Navigation Links -->
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <Label class="text-base">Links Kolom 1 (Navigasi)</Label>
              <Button variant="outline" size="sm" @click="addFooterLink('navigation_links')">
                <Plus class="w-4 h-4 mr-2" /> Tambah Link
              </Button>
            </div>
            <div class="space-y-3">
              <draggable 
                v-model="homepage.footer.navigation_links" 
                item-key="index"
                handle=".handle"
                class="space-y-3"
              >
                <template #item="{ element, index }">
                  <div class="grid gap-2 md:grid-cols-[auto_1fr_1fr_auto] items-center p-3 border rounded-lg bg-card">
                    <div class="handle cursor-grab active:cursor-grabbing p-1 text-muted-foreground/50 hover:text-foreground transition-colors">
                      <GripVertical class="w-4 h-4" />
                    </div>
                    <Input v-model="element.label" placeholder="Label" />
                    <Input v-model="element.href" placeholder="URL" />
                    <Button variant="ghost" size="icon" class="text-destructive" @click="removeFooterLink('navigation_links', index)">
                      <Trash2 class="w-4 h-4" />
                    </Button>
                  </div>
                </template>
              </draggable>
            </div>
          </div>

          <!-- Insights Links -->
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <Label class="text-base">Links Kolom 2 (Insights)</Label>
              <Button variant="outline" size="sm" @click="addFooterLink('insights_links')">
                <Plus class="w-4 h-4 mr-2" /> Tambah Link
              </Button>
            </div>
            <div class="space-y-3">
              <draggable 
                v-model="homepage.footer.insights_links" 
                item-key="index"
                handle=".handle"
                class="space-y-3"
              >
                <template #item="{ element, index }">
                  <div class="grid gap-2 md:grid-cols-[auto_1fr_1fr_auto] items-center p-3 border rounded-lg bg-card">
                    <div class="handle cursor-grab active:cursor-grabbing p-1 text-muted-foreground/50 hover:text-foreground transition-colors">
                      <GripVertical class="w-4 h-4" />
                    </div>
                    <Input v-model="element.label" placeholder="Label" />
                    <Input v-model="element.href" placeholder="URL" />
                    <Button variant="ghost" size="icon" class="text-destructive" @click="removeFooterLink('insights_links', index)">
                      <Trash2 class="w-4 h-4" />
                    </Button>
                  </div>
                </template>
              </draggable>
            </div>
          </div>

          <!-- Layanan Links -->
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <Label class="text-base">Links Kolom 3 (Layanan)</Label>
              <Button variant="outline" size="sm" @click="addFooterLink('layanan_links')">
                <Plus class="w-4 h-4 mr-2" /> Tambah Link
              </Button>
            </div>
            <div class="space-y-3">
              <draggable 
                v-model="homepage.footer.layanan_links" 
                item-key="index"
                handle=".handle"
                class="space-y-3"
              >
                <template #item="{ element, index }">
                  <div class="grid gap-2 md:grid-cols-[auto_1fr_1fr_auto] items-center p-3 border rounded-lg bg-card">
                    <div class="handle cursor-grab active:cursor-grabbing p-1 text-muted-foreground/50 hover:text-foreground transition-colors">
                      <GripVertical class="w-4 h-4" />
                    </div>
                    <Input v-model="element.label" placeholder="Label" />
                    <Input v-model="element.href" placeholder="URL" />
                    <Button variant="ghost" size="icon" class="text-destructive" @click="removeFooterLink('layanan_links', index)">
                      <Trash2 class="w-4 h-4" />
                    </Button>
                  </div>
                </template>
              </draggable>
            </div>
          </div>
        </div>

        <hr class="border-cb-hairline" />

        <div class="grid gap-6 md:grid-cols-2">
          <div class="space-y-2">
            <Label>Teks Copyright (di bagian bawah)</Label>
            <textarea v-model="homepage.footer.copyright_text" rows="2" class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm"></textarea>
          </div>
        </div>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { Card, Button } from '../components/ui'
import { Inbox, Trash2, Loader2, Search, ExternalLink, Image, MessageSquare } from 'lucide-vue-next'
import axios from 'axios'

const API_URL = 'https://lanyardbagus.com'

const leads = ref([])
const loading = ref(true)
const search = ref('')
const selectedLead = ref(null)

const filteredLeads = computed(() => {
  if (!search.value) return leads.value
  const term = search.value.toLowerCase()
  return leads.value.filter(l =>
    l.name?.toLowerCase().includes(term) ||
    l.whatsapp?.toLowerCase().includes(term) ||
    l.email?.toLowerCase().includes(term) ||
    l.message?.toLowerCase().includes(term)
  )
})

onMounted(async () => {
  await loadLeads()
})

async function loadLeads() {
  loading.value = true
  try {
    const res = await axios.get(`${API_URL}/api/admin/lanyard-leads`, { withCredentials: true })
    leads.value = res.data || []
  } catch (err) {
    console.error('Failed to load leads:', err)
  } finally {
    loading.value = false
  }
}

async function deleteLead(id) {
  if (!confirm('Hapus lead ini?')) return
  try {
    await axios.delete(`${API_URL}/api/admin/lanyard-leads/${id}`, { withCredentials: true })
    leads.value = leads.value.filter(l => l.id !== id)
    if (selectedLead.value?.id === id) selectedLead.value = null
  } catch (err) {
    alert('Gagal menghapus: ' + err.message)
  }
}

function formatDate(dateStr) {
  if (!dateStr) return '-'
  const d = new Date(dateStr)
  return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}

function openDetail(lead) {
  selectedLead.value = lead
}
</script>

<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h2 class="text-xl font-semibold">Leads</h2>
      <span class="text-sm text-muted-foreground">{{ leads.length }} total</span>
    </div>

    <!-- Search -->
    <div class="relative">
      <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
      <input
        v-model="search"
        type="text"
        placeholder="Cari berdasarkan nama, WhatsApp, email..."
        class="w-full pl-10 pr-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-background"
      />
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
      <!-- Table -->
      <Card class="flex-1 overflow-hidden">
        <div v-if="loading" class="p-8 text-center">
          <Loader2 class="w-6 h-6 animate-spin mx-auto text-primary" />
        </div>
        <div v-else-if="filteredLeads.length === 0" class="p-12 text-center">
          <Inbox class="w-12 h-12 mx-auto text-muted-foreground mb-4" />
          <p class="text-muted-foreground">Belum ada lead masuk</p>
        </div>
        <div v-else class="overflow-x-auto">
          <table class="w-full">
            <thead class="border-b bg-muted/50">
              <tr>
                <th class="px-4 py-3 text-left text-sm font-medium text-muted-foreground">Nama</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-muted-foreground">WhatsApp</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-muted-foreground hidden md:table-cell">Email</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-muted-foreground hidden lg:table-cell">Desain</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-muted-foreground">Tanggal</th>
                <th class="px-4 py-3 text-right text-sm font-medium text-muted-foreground">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <tr
                v-for="lead in filteredLeads"
                :key="lead.id"
                class="hover:bg-muted/30 cursor-pointer transition-colors"
                :class="{ 'bg-primary/5': selectedLead?.id === lead.id }"
                @click="openDetail(lead)"
              >
                <td class="px-4 py-3">
                  <div>
                    <p class="font-medium text-sm">{{ lead.name }}</p>
                    <p v-if="lead.size" class="text-xs text-muted-foreground">{{ lead.size }}</p>
                  </div>
                </td>
                <td class="px-4 py-3 text-sm">{{ lead.whatsapp }}</td>
                <td class="px-4 py-3 text-sm text-muted-foreground hidden md:table-cell">{{ lead.email || '-' }}</td>
                <td class="px-4 py-3 hidden lg:table-cell">
                  <span v-if="lead.design_url" class="inline-flex items-center gap-1 text-xs text-primary font-medium">
                    <Image class="w-3 h-3" /> Ada
                  </span>
                  <span v-else class="text-xs text-muted-foreground">-</span>
                </td>
                <td class="px-4 py-3 text-sm text-muted-foreground">{{ formatDate(lead.created_at) }}</td>
                <td class="px-4 py-3 text-right">
                  <Button variant="ghost" size="sm" @click.stop="deleteLead(lead.id)">
                    <Trash2 class="w-4 h-4 text-destructive" />
                  </Button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </Card>

      <!-- Detail Panel -->
      <transition name="slide">
        <Card v-if="selectedLead" class="lg:w-[380px] shrink-0 p-6 space-y-5 self-start sticky top-6">
          <div class="flex items-center justify-between">
            <h3 class="font-semibold">Detail Lead</h3>
            <button @click="selectedLead = null" class="text-muted-foreground hover:text-foreground transition-colors">
              <span class="text-xs">✕</span>
            </button>
          </div>

          <div class="space-y-4">
            <div>
              <p class="text-xs text-muted-foreground mb-1">Nama</p>
              <p class="text-sm font-medium">{{ selectedLead.name }}</p>
            </div>
            <div>
              <p class="text-xs text-muted-foreground mb-1">WhatsApp</p>
              <a :href="'https://wa.me/' + selectedLead.whatsapp.replace(/\D/g, '')" target="_blank" class="text-sm text-primary hover:underline inline-flex items-center gap-1">
                {{ selectedLead.whatsapp }}
                <ExternalLink class="w-3 h-3" />
              </a>
            </div>
            <div v-if="selectedLead.email">
              <p class="text-xs text-muted-foreground mb-1">Email</p>
              <p class="text-sm">{{ selectedLead.email }}</p>
            </div>
            <div v-if="selectedLead.size">
              <p class="text-xs text-muted-foreground mb-1">Ukuran</p>
              <p class="text-sm">{{ selectedLead.size }}</p>
            </div>
            <div v-if="selectedLead.message">
              <p class="text-xs text-muted-foreground mb-1">Pesan</p>
              <p class="text-sm bg-muted/50 p-3 rounded-lg leading-relaxed">{{ selectedLead.message }}</p>
            </div>
            <div v-if="selectedLead.design_url">
              <p class="text-xs text-muted-foreground mb-1">Referensi Desain</p>
              <a :href="selectedLead.design_url" target="_blank" class="block">
                <img :src="selectedLead.design_url" alt="Desain" class="w-full rounded-lg border object-cover max-h-48" />
              </a>
            </div>
            <div>
              <p class="text-xs text-muted-foreground mb-1">Diterima</p>
              <p class="text-sm">{{ formatDate(selectedLead.created_at) }}</p>
            </div>
          </div>

          <div class="flex gap-2 pt-2 border-t">
            <a :href="'https://wa.me/' + selectedLead.whatsapp.replace(/\D/g, '')" target="_blank" class="flex-1">
              <Button class="w-full" size="sm">
                <MessageSquare class="w-4 h-4 mr-2" />
                Balas WA
              </Button>
            </a>
            <Button variant="destructive" size="sm" @click="deleteLead(selectedLead.id)">
              <Trash2 class="w-4 h-4" />
            </Button>
          </div>
        </Card>
      </transition>
    </div>
  </div>
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 0.2s ease;
}
.slide-enter-from,
.slide-leave-to {
  opacity: 0;
  transform: translateX(12px);
}
</style>

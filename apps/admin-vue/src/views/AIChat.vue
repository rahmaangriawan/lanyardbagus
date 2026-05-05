<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { Button } from '../components/ui'
import { Card, CardHeader, CardTitle, CardContent } from '../components/ui/Card'
import { Input } from '../components/ui/Input'
import { Label } from '../components/ui/Label'
import { Textarea } from '../components/ui/Textarea'
import { Badge } from '../components/ui/Badge'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '../components/ui/Select'
import {
  MessageSquare,
  Settings,
  Users,
  Database,
  Save,
  Bot,
  CheckCircle,
  X,
  User
} from 'lucide-vue-next'

const API_URL = 'https://lanyardbagus.com'

const activeTab = ref('settings')
const loading = ref(false)
const saved = ref(false)

// AI Settings
const aiSettings = ref({
  enabled: true,
  provider: 'openai',
  model: 'gpt-4o-mini',
  api_key: '',
  system_prompt: '',
  welcome_message: '',
  widget_title: 'Asisten Lanyard Bagus'
})

// Chat Sessions
const sessions = ref([])
const selectedSession = ref(null)
const sessionMessages = ref([])

// Training Data
const trainingData = ref([])

// Provider models
const providerModels = {
  openai: [
    { value: 'gpt-4o', label: 'GPT-4o (Terbaik)' },
    { value: 'gpt-4o-mini', label: 'GPT-4o Mini (Hemat)' },
    { value: 'gpt-4-turbo', label: 'GPT-4 Turbo' },
    { value: 'gpt-3.5-turbo', label: 'GPT-3.5 Turbo (Paling Hemat)' }
  ],
  anthropic: [
    { value: 'claude-3-5-sonnet-latest', label: 'Claude 3.5 Sonnet (Terbaik)' },
    { value: 'claude-3-haiku-20240307', label: 'Claude 3 Haiku (Hemat)' }
  ],
  google: [
    { value: 'gemini-1.5-pro', label: 'Gemini 1.5 Pro' },
    { value: 'gemini-1.5-flash', label: 'Gemini 1.5 Flash (Hemat)' }
  ]
}

const defaultPrompt = `Kamu adalah asisten AI untuk Lanyard Bagus, perusahaan pembuat lanyard custom berkualitas di Indonesia.

Tugas kamu:
1. Membantu customer menemukan produk lanyard yang sesuai
2. Menjelaskan jenis-jenis lanyard (polyester, nylon, satin, Tali, dll)
3. Memberikan informasi harga dan minimal order
4. Menjawab pertanyaan proses pemesanan dan pengiriman

Jawaban harus:
- Ramah dan profesional
- Singkat dan to the point (maksimal 3-4 kalimat)
- Bahasa Indonesia yang sopan
- Jika tidak tahu, arahkan ke WhatsApp`

const defaultWelcome = "Halo! 👋 Saya asisten Lanyard Bagus. Ada yang bisa saya bantu tentang lanyard custom?"

async function loadAISettings() {
  try {
    const res = await axios.get(`${API_URL}/api/admin/chat/settings`, { withCredentials: true })
    aiSettings.value = { ...aiSettings.value, ...res.data }
    if (!aiSettings.value.system_prompt) aiSettings.value.system_prompt = defaultPrompt
    if (!aiSettings.value.welcome_message) aiSettings.value.welcome_message = defaultWelcome
  } catch (err) {
    console.error('Failed to load AI settings:', err)
    aiSettings.value.system_prompt = defaultPrompt
    aiSettings.value.welcome_message = defaultWelcome
  }
}

async function loadSessions() {
  try {
    const res = await axios.get(`${API_URL}/api/admin/chat/sessions`, { withCredentials: true })
    sessions.value = res.data
  } catch (err) {
    console.error('Failed to load sessions:', err)
  }
}

async function loadSessionMessages(sessionId) {
  try {
    selectedSession.value = sessionId
    const res = await axios.get(`${API_URL}/api/admin/chat/sessions/${sessionId}`, { withCredentials: true })
    sessionMessages.value = res.data
  } catch (err) {
    console.error('Failed to load session messages:', err)
  }
}

async function loadTrainingData() {
  try {
    const res = await axios.get(`${API_URL}/api/admin/chat/training-data`, { withCredentials: true })
    trainingData.value = res.data
  } catch (err) {
    console.error('Failed to load training data:', err)
  }
}

async function saveSettings() {
  loading.value = true
  try {
    await axios.put(`${API_URL}/api/admin/chat/settings`, aiSettings.value, { withCredentials: true })
    saved.value = true
    setTimeout(() => saved.value = false, 3000)
  } catch (err) {
    console.error('Failed to save settings:', err)
    alert('Gagal menyimpan pengaturan: ' + (err.response?.data?.message || err.message))
  }
  loading.value = false
}

async function toggleTraining(messageId, currentStatus) {
  try {
    await axios.patch(`${API_URL}/api/admin/chat/messages/${messageId}/training`, {
      is_training: !currentStatus
    }, { withCredentials: true })
    await loadTrainingData()
    await loadSessions()
  } catch (err) {
    console.error('Failed to toggle training status:', err)
  }
}

async function deleteMessage(messageId) {
  if (!confirm('Yakin ingin menghapus pesan ini?')) return
  try {
    await axios.delete(`${API_URL}/api/admin/chat/messages/${messageId}`, { withCredentials: true })
    await loadTrainingData()
    if (selectedSession.value) {
      await loadSessionMessages(selectedSession.value)
    }
  } catch (err) {
    console.error('Failed to delete message:', err)
  }
}

function formatDate(date) {
  return new Date(date).toLocaleString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  loadAISettings()
  loadSessions()
  loadTrainingData()
})
</script>

<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold flex items-center gap-3">
        <Bot class="w-8 h-8 text-primary" />
        AI Chat Assistant
      </h1>
    </div>

    <!-- Tabs -->
    <div class="flex gap-2 border-b">
      <button
        @click="activeTab = 'settings'"
        class="px-4 py-2 text-sm font-medium flex items-center gap-2 transition-colors"
        :class="activeTab === 'settings' ? 'text-primary border-b-2 border-primary' : 'text-muted-foreground hover:text-foreground'"
      >
        <Settings class="w-4 h-4" />
        Pengaturan AI
      </button>
      <button
        @click="activeTab = 'sessions'"
        class="px-4 py-2 text-sm font-medium flex items-center gap-2 transition-colors"
        :class="activeTab === 'sessions' ? 'text-primary border-b-2 border-primary' : 'text-muted-foreground hover:text-foreground'"
      >
        <Users class="w-4 h-4" />
        Chat Sessions
      </button>
      <button
        @click="activeTab = 'training'"
        class="px-4 py-2 text-sm font-medium flex items-center gap-2 transition-colors"
        :class="activeTab === 'training' ? 'text-primary border-b-2 border-primary' : 'text-muted-foreground hover:text-foreground'"
      >
        <Database class="w-4 h-4" />
        Training Data
      </button>
    </div>

    <!-- Settings Tab -->
    <div v-if="activeTab === 'settings'" class="space-y-6">
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center gap-2">
            <Bot class="w-5 h-5" />
            Konfigurasi AI
          </CardTitle>
        </CardHeader>
        <CardContent class="space-y-6">
          <!-- Enable/Disable -->
          <div class="flex items-center justify-between p-4 border rounded-lg">
            <div>
              <Label class="text-base font-medium">Aktifkan Chat AI</Label>
              <p class="text-sm text-muted-foreground">Widget chat akan muncul di website ketika aktif</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
              <input type="checkbox" v-model="aiSettings.enabled" class="sr-only peer">
              <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
            </label>
          </div>

          <!-- Provider -->
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
              <Label>AI Provider</Label>
              <Select v-model="aiSettings.provider">
                <SelectTrigger>
                  <SelectValue placeholder="Pilih provider" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="openai">OpenAI</SelectItem>
                  <SelectItem value="anthropic">Anthropic (Claude)</SelectItem>
                  <SelectItem value="google">Google (Gemini)</SelectItem>
                </SelectContent>
              </Select>
            </div>

            <div class="space-y-2">
              <Label>Model</Label>
              <Select v-model="aiSettings.model">
                <SelectTrigger>
                  <SelectValue placeholder="Pilih model" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem
                    v-for="model in providerModels[aiSettings.provider]"
                    :key="model.value"
                    :value="model.value"
                  >
                    {{ model.label }}
                  </SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>

          <!-- API Key -->
          <div class="space-y-2">
            <Label>API Key</Label>
            <Input
              v-model="aiSettings.api_key"
              type="password"
              placeholder="sk-... atau ant-..."
            />
            <p class="text-xs text-muted-foreground">
              API Key dari provider yang dipilih. Biarkan kosong jika tidak ingin mengubah.
            </p>
          </div>

          <!-- Widget Title -->
          <div class="space-y-2">
            <Label>Judul Widget</Label>
            <Input v-model="aiSettings.widget_title" placeholder="Asisten Lanyard Bagus" />
          </div>

          <!-- Welcome Message -->
          <div class="space-y-2">
            <Label>Pesan Sambutan</Label>
            <Textarea
              v-model="aiSettings.welcome_message"
              rows="2"
              placeholder="Halo! Saya asisten Lanyard Bagus..."
            />
          </div>

          <!-- System Prompt -->
          <div class="space-y-2">
            <Label>System Prompt (Instruksi AI)</Label>
            <Textarea
              v-model="aiSettings.system_prompt"
              rows="8"
              placeholder="Kamu adalah asisten AI untuk..."
            />
            <p class="text-xs text-muted-foreground">
              Instruksi ini akan diberikan ke AI setiap kali chat. Jelaskan tugas, batasan, dan gaya bicara yang diinginkan.
            </p>
          </div>

          <!-- Save Button -->
          <div class="flex items-center gap-3">
            <Button @click="saveSettings" :disabled="loading">
              <Save class="w-4 h-4 mr-2" />
              {{ loading ? 'Menyimpan...' : 'Simpan Pengaturan' }}
            </Button>
            <Badge v-if="saved" variant="success" class="bg-green-100 text-green-700">
              <CheckCircle class="w-3 h-3 mr-1" />
              Tersimpan!
            </Badge>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Sessions Tab -->
    <div v-if="activeTab === 'sessions'" class="grid grid-cols-3 gap-6">
      <!-- Sessions List -->
      <Card class="col-span-1">
        <CardHeader>
          <CardTitle class="text-base">Sessions</CardTitle>
        </CardHeader>
        <CardContent class="p-0">
          <div class="divide-y max-h-[600px] overflow-auto">
            <button
              v-for="session in sessions"
              :key="session.session_id"
              @click="loadSessionMessages(session.session_id)"
              class="w-full text-left p-4 hover:bg-muted transition-colors"
              :class="selectedSession === session.session_id ? 'bg-muted' : ''"
            >
              <div class="flex items-center justify-between">
                <span class="font-medium text-sm truncate">{{ session.user_name || 'Anonymous' }}</span>
                <Badge variant="outline" class="text-xs">{{ session.message_count }} msg</Badge>
              </div>
              <p class="text-xs text-muted-foreground mt-1">{{ formatDate(session.last_activity) }}</p>
            </button>
          </div>
          <div v-if="sessions.length === 0" class="p-8 text-center text-muted-foreground">
            Belum ada sesi chat
          </div>
        </CardContent>
      </Card>

      <!-- Session Messages -->
      <Card class="col-span-2">
        <CardHeader>
          <CardTitle class="text-base flex items-center gap-2">
            <MessageSquare class="w-4 h-4" />
            Messages
            <span v-if="selectedSession" class="text-xs font-normal text-muted-foreground">
              ({{ sessionMessages.length }} messages)
            </span>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div v-if="selectedSession" class="space-y-4 max-h-[500px] overflow-auto">
            <div
              v-for="msg in sessionMessages"
              :key="msg.id"
              class="p-4 rounded-lg"
              :class="msg.role === 'user' ? 'bg-primary/5 ml-8' : 'bg-muted mr-8'"
            >
              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                  :class="msg.role === 'user' ? 'bg-primary text-white' : 'bg-secondary'"
                >
                  <User v-if="msg.role === 'user'" class="w-4 h-4" />
                  <Bot v-else class="w-4 h-4" />
                </div>
                <div class="flex-1 space-y-2">
                  <p class="text-sm">{{ msg.message }}</p>
                  <p v-if="msg.response" class="text-sm text-muted-foreground border-t pt-2">
                    {{ msg.response }}
                  </p>
                  <div class="flex items-center gap-2 pt-2">
                    <Badge v-if="msg.is_training_data" variant="outline" class="text-xs bg-yellow-50 text-yellow-700 border-yellow-200">
                      Training Data
                    </Badge>
                    <button
                      @click="toggleTraining(msg.id, msg.is_training_data)"
                      class="text-xs text-muted-foreground hover:text-primary"
                    >
                      {{ msg.is_training_data ? 'Hapus dari training' : 'Jadikan training data' }}
                    </button>
                    <button
                      @click="deleteMessage(msg.id)"
                      class="text-xs text-red-500 hover:text-red-700 ml-auto"
                    >
                      Hapus
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="text-center text-muted-foreground py-12">
            Pilih sesi untuk melihat detail percakapan
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Training Data Tab -->
    <div v-if="activeTab === 'training'">
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center gap-2">
            <Database class="w-5 h-5" />
            Training Dataset ({{ trainingData.length }} items)
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-4">
            <div
              v-for="item in trainingData"
              :key="item.id"
              class="p-4 border rounded-lg"
            >
              <div class="space-y-3">
                <div class="flex items-start gap-3">
                  <Badge class="bg-primary/10 text-primary">User</Badge>
                  <p class="text-sm">{{ item.message }}</p>
                </div>
                <div class="flex items-start gap-3 pl-4 border-l-2">
                  <Badge class="bg-secondary">AI Response</Badge>
                  <p class="text-sm text-muted-foreground">{{ item.response }}</p>
                </div>
                <div class="flex items-center justify-between pt-2">
                  <span class="text-xs text-muted-foreground">
                    By {{ item.user_name || 'Anonymous' }} • {{ formatDate(item.created_at) }}
                  </span>
                  <button
                    @click="deleteMessage(item.id)"
                    class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1"
                  >
                    <X class="w-3 h-3" />
                    Hapus
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div v-if="trainingData.length === 0" class="text-center text-muted-foreground py-12">
            Belum ada data training. Tandai pesan dari sesi chat sebagai training data.
          </div>
        </CardContent>
      </Card>
    </div>
  </div>
</template>

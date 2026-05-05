<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold">Orders</h1>
        <p class="text-sm text-muted-foreground">Kelola pesanan dan pembayaran</p>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div class="bg-white border rounded-xl p-4">
        <p class="text-xs text-muted-foreground">Total Order</p>
        <p class="text-2xl font-bold">{{ stats.total_orders || 0 }}</p>
      </div>
      <div class="bg-white border rounded-xl p-4">
        <p class="text-xs text-muted-foreground">Menunggu Bayar</p>
        <p class="text-2xl font-bold text-yellow-600">{{ stats.pending_payment || 0 }}</p>
      </div>
      <div class="bg-white border rounded-xl p-4">
        <p class="text-xs text-muted-foreground">Sudah Bayar</p>
        <p class="text-2xl font-bold text-green-600">{{ stats.paid || 0 }}</p>
      </div>
      <div class="bg-white border rounded-xl p-4">
        <p class="text-xs text-muted-foreground">Total Revenue</p>
        <p class="text-2xl font-bold text-blue-600">{{ formatRupiah(stats.total_revenue || 0) }}</p>
      </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-3 items-center">
      <input 
        v-model="search" 
        type="text" 
        placeholder="Cari order ID, nama, atau nomor HP..." 
        class="px-4 py-2 border rounded-lg text-sm w-64"
        @input="debouncedFetch"
      />
      <select v-model="statusFilter" @change="fetchOrders" class="px-3 py-2 border rounded-lg text-sm">
        <option value="all">Semua Status</option>
        <option value="waiting_payment">Menunggu Bayar</option>
        <option value="paid">Sudah Bayar</option>
        <option value="processing">Diproses</option>
        <option value="shipped">Dikirim</option>
        <option value="completed">Selesai</option>
        <option value="cancelled">Dibatalkan</option>
      </select>
    </div>

    <!-- Orders Table -->
    <div class="bg-white border rounded-xl overflow-hidden">
      <div v-if="loading" class="p-8 text-center text-muted-foreground">Memuat...</div>
      <div v-else-if="orders.length === 0" class="p-8 text-center text-muted-foreground">
        Belum ada order
      </div>
      <table v-else class="w-full text-sm">
        <thead class="bg-muted/50 border-b">
          <tr>
            <th class="text-left px-4 py-3 font-medium">Order ID</th>
            <th class="text-left px-4 py-3 font-medium">Customer</th>
            <th class="text-left px-4 py-3 font-medium">Items</th>
            <th class="text-right px-4 py-3 font-medium">Amount</th>
            <th class="text-center px-4 py-3 font-medium">Status</th>
            <th class="text-center px-4 py-3 font-medium">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in orders" :key="order.id" class="border-b hover:bg-muted/30 transition-colors">
            <td class="px-4 py-3">
              <span class="font-mono text-xs font-medium">{{ order.order_id }}</span>
              <p class="text-xs text-muted-foreground mt-0.5">{{ formatDate(order.created_at) }}</p>
            </td>
            <td class="px-4 py-3">
              <p class="font-medium">{{ order.customer_name }}</p>
              <p class="text-xs text-muted-foreground">{{ order.customer_phone }}</p>
            </td>
            <td class="px-4 py-3">
              <div v-if="order.items" class="text-xs space-y-0.5">
                <p v-for="(item, i) in (typeof order.items === 'string' ? JSON.parse(order.items) : order.items)" :key="i">
                  {{ item.name }} ({{ item.size }}) × {{ item.qty }} pcs
                </p>
              </div>
            </td>
            <td class="px-4 py-3 text-right font-medium">{{ formatRupiah(order.amount) }}</td>
            <td class="px-4 py-3 text-center">
              <span :class="statusClass(order.status)" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                {{ statusLabel(order.status) }}
              </span>
            </td>
            <td class="px-4 py-3 text-center">
              <select 
                :value="order.status" 
                @change="updateStatus(order.order_id, $event.target.value)"
                class="text-xs border rounded px-2 py-1"
              >
                <option value="waiting_payment">Menunggu Bayar</option>
                <option value="paid">Sudah Bayar</option>
                <option value="processing">Diproses</option>
                <option value="shipped">Dikirim</option>
                <option value="completed">Selesai</option>
                <option value="cancelled">Dibatalkan</option>
              </select>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="pagination.last_page > 1" class="flex justify-center gap-2">
      <button 
        v-for="page in pagination.last_page" 
        :key="page" 
        @click="currentPage = page; fetchOrders()"
        :class="page === currentPage ? 'bg-blue-600 text-white' : 'bg-white border'"
        class="w-8 h-8 rounded-lg text-sm font-medium"
      >
        {{ page }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const API_URL = 'https://lanyardbagus.com'

const orders = ref([])
const stats = ref({})
const loading = ref(true)
const search = ref('')
const statusFilter = ref('all')
const currentPage = ref(1)
const pagination = ref({ last_page: 1 })

let debounceTimer = null
function debouncedFetch() {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => fetchOrders(), 400)
}

async function fetchOrders() {
  loading.value = true
  try {
    const params = new URLSearchParams({
      page: currentPage.value,
      per_page: 20,
      status: statusFilter.value,
    })
    if (search.value) params.append('search', search.value)

    const res = await fetch(`${API_URL}/api/admin/orders?${params}`, {
      headers: { Authorization: `Bearer ${auth.token}` }
    })
    const data = await res.json()
    orders.value = data.data || []
    pagination.value = { last_page: data.last_page || 1 }
  } catch (e) {
    console.error('Failed to fetch orders', e)
  }
  loading.value = false
}

async function fetchStats() {
  try {
    const res = await fetch(`${API_URL}/api/admin/orders/stats`, {
      headers: { Authorization: `Bearer ${auth.token}` }
    })
    stats.value = await res.json()
  } catch (e) {
    console.error('Failed to fetch stats', e)
  }
}

async function updateStatus(orderId, newStatus) {
  try {
    await fetch(`${API_URL}/api/admin/orders/${orderId}/status`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${auth.token}`
      },
      body: JSON.stringify({ status: newStatus })
    })
    fetchOrders()
    fetchStats()
  } catch (e) {
    console.error('Failed to update status', e)
  }
}

function formatRupiah(num) {
  return 'Rp ' + Number(num).toLocaleString('id-ID')
}

function formatDate(dateStr) {
  if (!dateStr) return '-'
  const d = new Date(dateStr)
  return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}

function statusLabel(status) {
  const map = {
    pending: 'Pending',
    waiting_payment: 'Menunggu Bayar',
    paid: 'Sudah Bayar',
    processing: 'Diproses',
    shipped: 'Dikirim',
    completed: 'Selesai',
    cancelled: 'Dibatalkan'
  }
  return map[status] || status
}

function statusClass(status) {
  const map = {
    pending: 'bg-gray-100 text-gray-700',
    waiting_payment: 'bg-yellow-100 text-yellow-800',
    paid: 'bg-green-100 text-green-800',
    processing: 'bg-blue-100 text-blue-800',
    shipped: 'bg-purple-100 text-purple-800',
    completed: 'bg-emerald-100 text-emerald-800',
    cancelled: 'bg-red-100 text-red-800'
  }
  return map[status] || 'bg-gray-100 text-gray-700'
}

onMounted(() => {
  fetchOrders()
  fetchStats()
})
</script>

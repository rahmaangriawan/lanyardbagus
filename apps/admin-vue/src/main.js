import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import App from './App.vue'
import './style.css'
import { useAuthStore } from './stores/auth'
import axios from 'axios'

// Global axios config for Sanctum
axios.defaults.withCredentials = true
axios.defaults.headers.common['Accept'] = 'application/json'

// Helper to get cookie value
function getCookie(name) {
  const value = `; ${document.cookie}`
  const parts = value.split(`; ${name}=`)
  if (parts.length === 2) return parts.pop().split(';').shift()
  return null
}

// Add XSRF-TOKEN header for CSRF protection
axios.interceptors.request.use(config => {
  const token = getCookie('XSRF-TOKEN')
  if (token) {
    config.headers['X-XSRF-TOKEN'] = decodeURIComponent(token)
  }
  return config
})

// Add response interceptor to handle 401 Unauthorized
axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.status === 401) {
      const auth = useAuthStore()
      auth.logout()
      router.push('/login')
    }
    return Promise.reject(error)
  }
)

const app = createApp(App)
app.use(createPinia())

// Initialize auth before mounting
const auth = useAuthStore()
auth.init()

app.use(router)
app.mount('#app')

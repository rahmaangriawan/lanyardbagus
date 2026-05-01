import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'

const API_URL = 'http://localhost:8000'

// Helper to get cookie value
function getCookie(name) {
  const value = `; ${document.cookie}`
  const parts = value.split(`; ${name}=`)
  if (parts.length === 2) return parts.pop().split(';').shift()
  return null
}

// Helper to get XSRF token header
function getXsrfHeader() {
  const token = getCookie('XSRF-TOKEN')
  return token ? { 'X-XSRF-TOKEN': decodeURIComponent(token) } : {}
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token'))
  const loading = ref(false)
  const error = ref(null)

  const isAuthenticated = computed(() => !!token.value)

  async function login(email, password) {
    loading.value = true
    error.value = null
    
    try {
      // Get CSRF cookie
      await axios.get(`${API_URL}/sanctum/csrf-cookie`, { withCredentials: true })
      
      // Wait for cookie to be set in browser
      await new Promise(r => setTimeout(r, 300))
      
      // Login with XSRF token header
      const xsrfHeaders = getXsrfHeader()
      console.log('Login with XSRF headers:', xsrfHeaders)
      
      const response = await axios.post(`${API_URL}/api/auth/login`, {
        email,
        password
      }, { 
        withCredentials: true,
        headers: xsrfHeaders 
      })
      
      user.value = response.data.user
      token.value = response.data.token || 'authenticated'
      localStorage.setItem('token', token.value)
      localStorage.setItem('user', JSON.stringify(response.data.user))
      localStorage.setItem('lastLoginTime', Date.now().toString())
      
      return true
    } catch (err) {
      error.value = err.response?.data?.message || 'Login failed'
      return false
    } finally {
      loading.value = false
    }
  }

  function logout() {
    user.value = null
    token.value = null
    localStorage.removeItem('token')
    localStorage.removeItem('user')
  }

  function init() {
    const savedUser = localStorage.getItem('user')
    if (savedUser) {
      user.value = JSON.parse(savedUser)
    }
  }

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    login,
    logout,
    init
  }
})

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { Button, Input, Card } from '../components/ui'
import { Loader2 } from 'lucide-vue-next'
import axios from 'axios'

const router = useRouter()
const auth = useAuthStore()

const email = ref('')
const password = ref('')

async function handleLogin() {
  try {
    const success = await auth.login(email.value, password.value)
    if (success) {
      router.push('/')
    }
  } catch (err) {
    console.error('Login error:', err)
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-muted/20 p-4">
    <Card class="w-full max-w-md p-8">
      <div class="text-center mb-6">
        <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mx-auto mb-4">
          <span class="text-primary-foreground font-bold text-xl">L</span>
        </div>
        <h1 class="text-2xl font-bold text-card-foreground">CMS Login</h1>
        <p class="text-sm text-muted-foreground mt-1">Lanyard Bagus Admin Panel</p>
      </div>
      
      <form @submit.prevent="handleLogin" class="space-y-4">
        <div class="space-y-2">
          <label class="text-sm font-medium">Email</label>
          <Input 
            v-model="email" 
            type="email" 
            placeholder="admin@lanyardbagus.com"
            required
          />
        </div>
        
        <div class="space-y-2">
          <label class="text-sm font-medium">Password</label>
          <Input 
            v-model="password" 
            type="password" 
            placeholder="••••••••"
            required
          />
        </div>
        
        <div v-if="auth.error" class="text-sm text-destructive text-center">
          {{ auth.error }}
        </div>
        
        <Button 
          type="submit" 
          class="w-full"
          :disabled="auth.loading"
        >
          <Loader2 v-if="auth.loading" class="w-4 h-4 mr-2 animate-spin" />
          {{ auth.loading ? 'Loading...' : 'Login' }}
        </Button>
      </form>
    </Card>
  </div>
</template>

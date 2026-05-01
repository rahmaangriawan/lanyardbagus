<script setup>
import { ref, computed, onMounted, watch, h } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import axios from 'axios'
import {
  Sidebar,
  SidebarHeader,
  SidebarContent,
  SidebarFooter,
  SidebarMenu,
  SidebarMenuItem,
  SidebarMenuButton,
  Button
} from '../components/ui'
import {
  LayoutDashboard, 
  Home,
  FileText, 
  FileStack, 
  Image, 
  Settings,
  LogOut,
  User,
  Users,
  ChevronLeft,
  ChevronRight,
  ChevronDown,
  Inbox,
  FolderOpen,
  Tag,
  Layers,
  Layout,
  Folder,
  Sparkles,
  Globe
} from 'lucide-vue-next'

const HeaderIcon = (props) => h('svg', { 
  xmlns: 'http://www.w3.org/2000/svg', 
  viewBox: '0 0 21 21',
  ...props
}, [
  h('path', {
    fill: 'none',
    stroke: 'currentColor',
    'stroke-linecap': 'round',
    'stroke-linejoin': 'round',
    'stroke-width': '1.5',
    d: 'm17.498 15.498l-.01-10a2 2 0 0 0-2-1.998h-10a2 2 0 0 0-1.995 1.85l-.006.152l.01 10a2 2 0 0 0 2 1.998h10a2 2 0 0 0 1.995-1.85zM7.5 7.5v9.817m10-9.817h-14'
  })
])

const FooterIcon = (props) => h('svg', { 
  xmlns: 'http://www.w3.org/2000/svg', 
  viewBox: '0 0 21 21',
  style: { transform: 'rotate(180deg) scaleX(-1)' },
  ...props
}, [
  h('path', {
    fill: 'none',
    stroke: 'currentColor',
    'stroke-linecap': 'round',
    'stroke-linejoin': 'round',
    'stroke-width': '1.5',
    d: 'm17.498 15.498l-.01-10a2 2 0 0 0-2-1.998h-10a2 2 0 0 0-1.995 1.85l-.006.152l.01 10a2 2 0 0 0 2 1.998h10a2 2 0 0 0 1.995-1.85zM7.5 7.5v9.817m10-9.817h-14'
  })
])

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000'

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()

const collapsed = ref(false)
const expandedMenus = ref({
  postingan: false,
  beranda: false
})

const user = computed(() => auth.user)
const userRole = computed(() => user.value?.role?.toLowerCase() || 'member')
const isAdmin = computed(() => userRole.value === 'admin')
const siteTitle = ref('Lanyard Bagus')

// Filter postingan children based on role
const postinganChildren = computed(() => {
  const all = [
    { path: '/posts', label: 'Semua Post', icon: FileText },
    { path: '/pages', label: 'Halaman', icon: Layout },
    { path: '/categories', label: 'Kategori', icon: Folder },
    { path: '/tags', label: 'Tags', icon: Tag }
  ]
  // Member only sees Posts and Pages
  if (!isAdmin.value) {
    return all.filter(item => item.path === '/posts' || item.path === '/pages')
  }
  return all
})

// Filter beranda children based on role
const berandaChildren = computed(() => {
  const all = [
    { path: '/customize', label: 'Customize', icon: Sparkles },
    { path: '/header', label: 'Header', icon: HeaderIcon },
    { path: '/footer', label: 'Footer', icon: FooterIcon },
  ]
  return all
})

const middleMenus = computed(() => {
  return [
    { path: '/media', label: 'Media', icon: Image }
  ]
})
const faviconUrl = ref(null)

// Dynamic page titles based on route
const pageTitles = {
  'Dashboard': 'Dashboard',
  'Customize': 'Customize',
  'Header': 'Header Settings',
  'Footer': 'Footer Settings',
  'Posts': 'Postingan',
  'PostEditor': 'Edit Post',
  'Pages': 'Halaman',
  'PageEditor': 'Edit Halaman',
  'Categories': 'Kategori',
  'Tags': 'Tags',
  'Media': 'Media',
  'Leads': 'Leads',
  'Users': 'Pengguna',
  'Settings': 'Pengaturan'
}

// Load settings and update title/favicon
async function loadSiteSettings() {
  try {
    const res = await axios.get(`${API_URL}/api/settings`)
    siteTitle.value = res.data.site_title || 'Lanyard Bagus'
    faviconUrl.value = res.data.favicon_image?.url || null
    updateFavicon()
    updateTitle()
  } catch (err) {
    console.error('Failed to load site settings:', err)
  }
}

function updateTitle() {
  const routeName = route.name
  const pageTitle = pageTitles[routeName] || routeName
  document.title = pageTitle ? `${pageTitle} | ${siteTitle.value}` : siteTitle.value
}

function updateFavicon() {
  let link = document.querySelector("link[rel*='icon']") || document.createElement('link')
  link.type = 'image/x-icon'
  link.rel = 'shortcut icon'
  if (faviconUrl.value) {
    link.href = faviconUrl.value
  } else {
    link.href = '/favicon.ico'
  }
  document.head.appendChild(link)
}

// Watch route changes to update title
watch(() => route.name, updateTitle)

onMounted(() => {
  loadSiteSettings()
})

// Simple menu items (no submenu)
const simpleMenuItems = [
  { path: '/', label: 'Dashboard', icon: LayoutDashboard },
]

// Postingan menu with submenu
const postinganMenu = {
  key: 'postingan',
  label: 'Postingan',
  icon: FileText,
  children: [
    { path: '/posts', label: 'Semua Pos', icon: FolderOpen },
    { path: '/categories', label: 'Kategori', icon: Layers },
    { path: '/tags', label: 'Tag', icon: Tag },
  ]
}



// Beranda menu with submenu
const berandaMenu = {
  key: 'beranda',
  label: 'Beranda',
  icon: Home,
  children: [
    { path: '/customize', label: 'Customize', icon: Sparkles },
    { path: '/header', label: 'Header', icon: HeaderIcon },
    { path: '/footer', label: 'Footer', icon: FooterIcon },
  ]
}

const leadsMenu = { path: '/leads', label: 'Leads', icon: Inbox }

// Lainnya section
const lainnyaLabel = 'Lainnya'
const lainnyaMenus = computed(() => {
  const all = [
    { path: '/profile', label: 'Profil Saya', icon: User },
    { path: '/settings', label: 'Pengaturan', icon: Settings },
    { path: '/users', label: 'Kelola User', icon: Users },
  ]
  if (!isAdmin.value) {
    return all.filter(item => item.path !== '/users')
  }
  return all
})

function isActive(path) {
  if (path === '/') {
    return route.path === '/'
  }
  return route.path.startsWith(path)
}

function isMenuActive(children) {
  const list = children && children.value ? children.value : children;
  if (!Array.isArray(list)) return false;
  return list.some(child => isActive(child.path))
}

function toggleSubmenu(key) {
  expandedMenus.value[key] = !expandedMenus.value[key]
}

function logout() {
  auth.logout()
  router.push('/login')
}

function toggleSidebar() {
  collapsed.value = !collapsed.value
}

// Auto-expand menus when child route is active
onMounted(() => {
  if (isMenuActive(postinganMenu.children)) {
    expandedMenus.value.postingan = true
  }
  if (isMenuActive(berandaChildren)) {
    expandedMenus.value.beranda = true
  }
})
</script>

<template>
  <div class="flex min-h-screen bg-background">
    <!-- Sidebar -->
    <Sidebar :class="collapsed ? 'w-16' : 'w-64'">
      <SidebarHeader class="border-b border-sidebar-border">
        <div class="flex items-center justify-between px-2">
          <div class="flex items-center gap-3 overflow-hidden">
            <div class="w-8 h-8 border border-border rounded-lg flex items-center justify-center flex-shrink-0">
              <span class="font-bold text-foreground">L</span>
            </div>
            <div v-show="!collapsed" class="overflow-hidden">
              <h1 class="font-bold text-sm text-sidebar-foreground truncate">{{ siteTitle }}</h1>
              <p class="text-xs text-muted-foreground">CMS Admin</p>
            </div>
          </div>
          <Button 
            variant="ghost" 
            size="icon" 
            class="h-8 w-8 flex-shrink-0 text-muted-foreground hover:text-foreground"
            @click="toggleSidebar"
          >
            <ChevronLeft v-if="!collapsed" class="w-4 h-4" />
            <ChevronRight v-else class="w-4 h-4" />
          </Button>
        </div>
      </SidebarHeader>
      
      <SidebarContent class="py-4">
        <!-- Dashboard -->
        <div class="px-3 mb-1">
          <SidebarMenuItem v-for="item in simpleMenuItems" :key="item.path">
            <SidebarMenuButton 
              :isActive="isActive(item.path)"
              asChild
              class="w-full"
              :class="collapsed ? 'justify-center' : ''"
            >
              <router-link :to="item.path" class="flex items-center gap-2.5">
                <component :is="item.icon" class="w-[18px] h-[18px] flex-shrink-0" />
                <span v-show="!collapsed" class="text-[13px]">{{ item.label }}</span>
              </router-link>
            </SidebarMenuButton>
          </SidebarMenuItem>
        </div>

        <!-- Postingan with submenu -->
        <div class="px-3 mb-1">
          <SidebarMenuItem>
            <SidebarMenuButton 
              :isActive="isMenuActive(postinganMenu.children)"
              @click="collapsed ? $router.push('/posts') : toggleSubmenu('postingan')"
              class="cursor-pointer w-full"
              :class="collapsed ? 'justify-center' : ''"
            >
              <component :is="postinganMenu.icon" class="w-[18px] h-[18px] flex-shrink-0" />
              <span v-show="!collapsed" class="flex-1 text-[13px] text-left">{{ postinganMenu.label }}</span>
              <ChevronDown 
                v-show="!collapsed" 
                :class="['w-3.5 h-3.5 transition-transform duration-200', expandedMenus.postingan ? 'rotate-180' : '']" 
              />
            </SidebarMenuButton>
          </SidebarMenuItem>
          
          <!-- Submenu with border line -->
          <div v-show="!collapsed && expandedMenus.postingan" class="relative mt-0.5">
            <div class="absolute left-[9px] top-0 bottom-2 w-px bg-border"></div>
            <div class="pl-6 space-y-0.5">
              <SidebarMenuItem v-for="child in postinganChildren" :key="child.path">
                <SidebarMenuButton 
                  :isActive="isActive(child.path)"
                  asChild
                  class="py-1.5 w-full"
                >
                  <router-link :to="child.path" class="flex items-center gap-2.5">
                    <component :is="child.icon" class="w-[16px] h-[16px] flex-shrink-0 opacity-70" />
                    <span class="text-[12px] text-muted-foreground">{{ child.label }}</span>
                  </router-link>
                </SidebarMenuButton>
              </SidebarMenuItem>
            </div>
          </div>
        </div>

        <!-- Media -->
        <div class="px-3 space-y-0.5">
          <SidebarMenuItem v-for="item in middleMenus" :key="item.path">
            <SidebarMenuButton 
              :isActive="isActive(item.path)"
              asChild
              class="w-full"
              :class="collapsed ? 'justify-center' : ''"
            >
              <router-link :to="item.path" class="flex items-center gap-2.5">
                <component :is="item.icon" class="w-[18px] h-[18px] flex-shrink-0" />
                <span v-show="!collapsed" class="text-[13px]">{{ item.label }}</span>
              </router-link>
            </SidebarMenuButton>
          </SidebarMenuItem>
        </div>

        <!-- Beranda with submenu -->
        <div class="px-3 mt-1">
          <SidebarMenuItem>
            <SidebarMenuButton 
              :isActive="isMenuActive(berandaChildren)"
              @click="toggleSubmenu('beranda')"
              class="cursor-pointer w-full"
              :class="collapsed ? 'justify-center' : ''"
            >
              <component :is="berandaMenu.icon" class="w-[18px] h-[18px] flex-shrink-0" />
              <span v-show="!collapsed" class="flex-1 text-[13px] text-left">{{ berandaMenu.label }}</span>
              <ChevronDown 
                v-show="!collapsed" 
                :class="['w-3.5 h-3.5 transition-transform duration-200', expandedMenus.beranda ? 'rotate-180' : '']" 
              />
            </SidebarMenuButton>
          </SidebarMenuItem>
          
          <!-- Submenu -->
          <div v-show="!collapsed && expandedMenus.beranda" class="relative mt-0.5">
            <div class="absolute left-[9px] top-0 bottom-2 w-px bg-border"></div>
            <div class="pl-6 space-y-0.5">
              <SidebarMenuItem v-for="child in berandaChildren" :key="child.path">
                <SidebarMenuButton 
                  :isActive="isActive(child.path)"
                  asChild
                  class="py-1.5 w-full"
                >
                  <router-link :to="child.path" class="flex items-center gap-2.5">
                    <component :is="child.icon" class="w-[16px] h-[16px] flex-shrink-0 opacity-70" />
                    <span class="text-[12px] text-muted-foreground">{{ child.label }}</span>
                  </router-link>
                </SidebarMenuButton>
              </SidebarMenuItem>
            </div>
          </div>
        </div>

        <!-- Leads -->
        <div class="px-3 mt-1">
          <SidebarMenuItem>
            <SidebarMenuButton 
              :isActive="isActive(leadsMenu.path)"
              asChild
              class="w-full"
              :class="collapsed ? 'justify-center' : ''"
            >
              <router-link :to="leadsMenu.path" class="flex items-center gap-2.5">
                <component :is="leadsMenu.icon" class="w-[18px] h-[18px] flex-shrink-0" />
                <span v-show="!collapsed" class="text-[13px]">{{ leadsMenu.label }}</span>
              </router-link>
            </SidebarMenuButton>
          </SidebarMenuItem>
        </div>
        
        <!-- Divider -->
        <div v-show="!collapsed" class="mx-3 my-4 h-px bg-border"></div>
        
        <!-- Lainnya Section -->
        <div v-show="!collapsed" class="px-3 mb-2">
          <p class="text-[11px] font-medium text-muted-foreground/70 uppercase tracking-wide">Lainnya</p>
        </div>
        <div class="px-3 space-y-0.5">
          <SidebarMenuItem v-for="item in lainnyaMenus" :key="item.path">
            <SidebarMenuButton 
              :isActive="isActive(item.path)"
              asChild
              class="w-full"
              :class="collapsed ? 'justify-center' : ''"
            >
              <router-link :to="item.path" class="flex items-center gap-2.5">
                <component :is="item.icon" class="w-[18px] h-[18px] flex-shrink-0" />
                <span v-show="!collapsed" class="text-[13px]">{{ item.label }}</span>
              </router-link>
            </SidebarMenuButton>
          </SidebarMenuItem>
        </div>
      </SidebarContent>
      
      <SidebarFooter class="border-t border-sidebar-border">
        <SidebarMenu>
          <SidebarMenuItem>
            <SidebarMenuButton 
              @click="logout"
              class="cursor-pointer text-destructive hover:text-destructive hover:bg-destructive/10"
              :class="collapsed ? 'justify-center px-2' : ''"
            >
              <LogOut class="w-5 h-5 flex-shrink-0" />
              <span v-show="!collapsed">Keluar</span>
            </SidebarMenuButton>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarFooter>
    </Sidebar>
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col" :class="collapsed ? 'ml-16' : 'ml-64'">
      <!-- Top Bar -->
      <header class="h-14 bg-card border-b flex items-center justify-between px-6 sticky top-0 z-20">
        <h2 class="text-lg font-semibold">{{ route.name }}</h2>
        <router-link to="/profile" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
          <span class="text-sm text-muted-foreground">
            Hi, <span class="font-medium text-foreground">{{ user?.name || 'admin' }}</span>
          </span>
          <div class="w-8 h-8 border border-border rounded-full flex items-center justify-center">
            <User class="w-4 h-4 text-muted-foreground" />
          </div>
        </router-link>
      </header>
      
      <main class="flex-1 p-6 overflow-auto">
        <router-view />
      </main>
    </div>
  </div>
</template>

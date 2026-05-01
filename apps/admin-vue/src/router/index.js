import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue'),
    meta: { guest: true }
  },
  {
    path: '/',
    component: () => import('../layouts/AdminLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'Dashboard',
        component: () => import('../views/Dashboard.vue')
      },
      {
        path: 'customize',
        name: 'Customize',
        component: () => import('../views/Homepage.vue'),
        props: { mode: 'customize' }
      },
      {
        path: 'header',
        name: 'Header',
        component: () => import('../views/Homepage.vue'),
        props: { mode: 'header' }
      },
      {
        path: 'footer',
        name: 'Footer',
        component: () => import('../views/Homepage.vue'),
        props: { mode: 'footer' }
      },
      {
        path: 'homepage',
        redirect: '/dashboard/customize'
      },
      {
        path: 'posts',
        name: 'Posts',
        component: () => import('../views/Posts.vue')
      },
      {
        path: 'posts/new',
        name: 'PostNew',
        component: () => import('../views/PostEditor.vue')
      },
      {
        path: 'posts/edit/:id',
        name: 'PostEdit',
        component: () => import('../views/PostEditor.vue')
      },
      {
        path: 'posts/view/:id',
        redirect: to => `/posts/edit/${to.params.id}`
      },
      {
        path: 'pages',
        name: 'Pages',
        component: () => import('../views/Pages.vue')
      },
      {
        path: 'pages/new',
        name: 'PageNew',
        component: () => import('../views/PageEditor.vue')
      },
      {
        path: 'pages/edit/:id',
        name: 'PageEdit',
        component: () => import('../views/PageEditor.vue')
      },
      {
        path: 'media',
        name: 'Media',
        component: () => import('../views/Media.vue')
      },
      {
        path: 'categories',
        name: 'Categories',
        component: () => import('../views/Categories.vue')
      },
      {
        path: 'tags',
        name: 'Tags',
        component: () => import('../views/Tags.vue')
      },
      {
        path: 'users',
        name: 'Users',
        component: () => import('../views/Users.vue')
      },
      {
        path: 'settings',
        name: 'Settings',
        component: () => import('../views/Settings.vue')
      },
      {
        path: 'leads',
        name: 'Leads',
        component: () => import('../views/Leads.vue')
      },
      {
        path: 'profile',
        name: 'Profile',
        component: () => import('../views/Profile.vue')
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory('/dashboard'),
  routes
})

router.beforeEach((to, from, next) => {
  const auth = useAuthStore()
  
  if (to.meta.requiresAuth && !auth.token) {
    next('/login')
  } else if (to.meta.guest && auth.token) {
    next('/')
  } else {
    next()
  }
})

export default router

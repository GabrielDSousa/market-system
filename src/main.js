import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

import './assets/main.css'
import { useAuthStore } from '@/stores/AuthStore'
import { useUserStore } from '@/stores/UserStore'
import { useTypeStore } from '@/stores/TypeStore'
import { useProductStore } from '@/stores/ProductStore'
import { useBagStore } from '@/stores/BagStore'
import 'sweetalert2/dist/sweetalert2.min.css'
import VueSweetalert2 from 'vue-sweetalert2'

const app = createApp(App)

app.use(createPinia())
app.use(VueSweetalert2)

const userStore = useUserStore()
const authStore = useAuthStore()
const typeStore = useTypeStore()
const productStore = useProductStore()
const bagStore = useBagStore()

userStore.fill()
typeStore.fill()
productStore.fill()
bagStore.fill()

app.use(router)

router.beforeEach((to) => {
  const authPages = ['/login', '/signup']
  const publicPages = ['/', '/products', '/login', '/signup']
  const authRequired = !publicPages.includes(to.path)

  if (authRequired && !authStore.isLoggedIn) {
    authStore.returnUrl = to.fullPath
    return '/login'
  }

  if (authPages.includes(to.path) && authStore.isLoggedIn) {
    return '/'
  }
})

app.mount('#app')

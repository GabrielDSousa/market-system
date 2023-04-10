import { createRouter, createWebHistory } from 'vue-router'
import ProductsView from '@/views/ProductsView.vue'
import LoginView from '@/views/LoginView.vue'
import ProfileView from '@/views/ProfileView.vue'
import ProductView from '@/views/ProductView.vue'
import SignupView from '@/views/SignupView.vue'
import AdminView from '@/views/AdminView.vue'
import SaveProductView from '@/views/SaveProductView.vue'
import SaveTypeView from '@/views/SaveTypeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'products',
      component: ProductsView
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/signup',
      name: 'signup',
      component: SignupView
    },
    {
      path: '/profile',
      name: 'profile',
      component: ProfileView
    },
    {
      path: '/products/:id',
      name: 'product',
      component: ProductView,
      props: true
    },
    {
      path: '/products/store',
      name: 'productStore',
      component: SaveProductView,
      props: true
    },
    {
      path: '/products/update/:id',
      name: 'productUpdate',
      component: SaveProductView,
      props: true
    },
    {
      path: '/types/store',
      name: 'typeStore',
      component: SaveTypeView,
      props: true
    },
    {
      path: '/types/update/:id',
      name: 'typeUpdate',
      component: SaveTypeView,
      props: true
    },
    {
      path: '/admin',
      name: 'admin',
      component: AdminView
    }
  ]
})
export default router

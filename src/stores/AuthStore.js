import { defineStore } from 'pinia'
import { usePost } from '@/composables/request'
import { useUserStore } from '@/stores/UserStore'
import router from '@/router/index'
import { useAlertStore } from '@/stores/AlertStore'

export const useAuthStore = defineStore('AuthStore', {
  state: () => ({
    bearer: JSON.parse(localStorage.getItem('token')),
    returnUrl: '/login'
  }),
  actions: {
    async login(email, password) {
      let body = { email: email, password: password }
      const alertStore = useAlertStore()
      const response = usePost('/login', body)

      response
        .then((response) => {
          this.bearer = response.token

          // store user details and jwt in local storage to keep user logged in between page refreshes
          localStorage.setItem('token', JSON.stringify(response.token))
          localStorage.setItem('user', JSON.stringify(response.user))

          useUserStore().fill()
          alertStore.alertSuccess('Welcome' + response.user.name, 'You are logged in')
          router.push('/')
        })
        .catch((response) => {
          console.log(response)
        })
    },
    logout() {
      const { user } = useUserStore()
      const response = usePost('/logout', { email: user.email })
      response
        .then((res) => {
          this.bearer = null
          localStorage.removeItem('token')
          localStorage.removeItem('user')
          window.localStorage.clear()
          useUserStore().empty()
          router.push('/login')
        })
        .catch((err) => {
          console.log(err)
        })
    }
  },
  getters: {
    isLoggedIn(state) {
      return typeof state.bearer !== 'undefined' && state.bearer !== null
    }
  }
})

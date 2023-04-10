import { defineStore } from 'pinia'
import { usePost } from '@/composables/request'
import { useAuthStore } from '@/stores/AuthStore'
import { useAlertStore } from '@/stores/AlertStore'

export const useUserStore = defineStore('UserStore', {
  state: () => ({
    id: null,
    name: null,
    email: null,
    admin: null,
    imageUrl: 'https://source.unsplash.com/random/?avatar',
    form: {
      errors: {
        name: null,
        email: null,
        password: null,
        confirmation: null
      }
    }
  }),
  actions: {
    fill() {
      const data = JSON.parse(localStorage.getItem('user'))
      if (typeof data === 'undefined' || data === null) {
        this.empty()
        return
      }
      this.id = data.id
      this.name = data.name
      this.email = data.email
      this.admin = data.admin
    },
    empty() {
      this.id = null
      this.name = null
      this.email = null
      this.admin = null
    },
    add(name, email, password, confirmation) {
      const response = usePost('/user/store', {
        name: name,
        email: email,
        password: password,
        confirmation: confirmation
      })

      response
        .then((response) => {
          // store user details and jwt in local storage to keep user logged in between page refreshes
          localStorage.setItem('user', JSON.stringify(response))
          const authStore = useAuthStore()
          const alertStore = useAlertStore()
          alertStore.alertSuccess('Success', 'User added successfully')
          authStore.login(email, password)
        })
        .catch((error) => {
          this.form.errors.name = typeof error.name !== 'undefined' ? error.name : null
          this.form.errors.email = typeof error.email !== 'undefined' ? error.email : null
          this.form.errors.password = typeof error.password !== 'undefined' ? error.password : null
          this.form.errors.confirmation =
            typeof error.confirmation !== 'undefined' ? error.confirmation : null
        })
    }
  },
  getters: {
    user: (state) => {
      return {
        id: state.id,
        name: state.name,
        email: state.email,
        admin: state.admin,
        imageUrl: state.imageUrl
      }
    },
    errors: (state) => {
      return state.form.errors
    }
  }
})

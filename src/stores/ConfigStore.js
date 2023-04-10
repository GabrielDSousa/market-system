import { defineStore } from 'pinia'

export const useConfigStore = defineStore('ConfigStore', {
  state: () => ({
    theme: 'light',
    bagOpen: false
  }),
  actions: {
    toggleTheme() {
      this.theme = this.theme === 'light' ? 'dark' : 'light'
    },
    toggleBag() {
      this.bagOpen = !this.bagOpen
    }
  }
})

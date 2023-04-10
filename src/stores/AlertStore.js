import { defineStore } from 'pinia'

export const useAlertStore = defineStore('AlertStore', {
  state: () => ({
    icon: 'success',
    title: 'Success',
    text: 'This is a success alert'
  }),
  actions: {
    alertSuccess(title, text) {
      this.icon = 'success'
      this.title = title
      this.text = text
    },
    alertError(title, text) {
      this.icon = 'error'
      this.title = title
      this.text = text
    }
  }
})

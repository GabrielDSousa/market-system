import { defineStore } from 'pinia'
import { useDelete, useGet, usePost, usePut } from '@/composables/request'
import { useAlertStore } from '@/stores/AlertStore'

export const useTypeStore = defineStore('TypeStore', {
  state: () => ({
    types: [
      {
        id: 1,
        name: 'Type 1',
        tax: 0.01
      }
    ],
    toUpdate: {
      id: null,
      name: null,
      tax: null
    }
  }),
  actions: {
    fill() {
      if (
        typeof localStorage.getItem('types') === 'undefined' ||
        localStorage.getItem('types') === null
      ) {
        const response = useGet('/types')

        response
          .then((res) => {
            // store user details and jwt in local storage to keep user logged in between page refreshes
            localStorage.setItem('types', JSON.stringify(res))
            this.types = res
          })
          .catch((err) => {
            console.log(err)
          })
      } else {
        this.types = JSON.parse(localStorage.getItem('types'))
      }
    },
    empty() {
      this.types = [
        {
          id: null,
          name: null,
          tax: null
        }
      ]
    },
    filter(parameter = 'id', value) {
      return this.types.filter((type) => type[parameter] === value)
    },
    prepareToUpdate(type) {
      this.toUpdate = {
        id: type.id,
        name: type.name,
        tax: type.tax
      }
    },
    add(type) {
      const response = usePost('/type/store', type)

      response
        .then((res) => {
          // store user details and jwt in local storage to keep user logged in between page refreshes
          this.types.push(res)
          const alertStore = useAlertStore()
          alertStore.alertSuccess('Success', 'Type added successfully')
          localStorage.setItem('types', JSON.stringify(this.types))
        })
        .catch((err) => {
          console.log(err)
        })
    },
    update(type) {
      const response = usePut('/type/update', type)
      response
        .then((res) => {
          // store user details and jwt in local storage to keep user logged in between page refreshes
          localStorage.removeItem('types')
          const alertStore = useAlertStore()
          alertStore.alertSuccess('Success', 'Type updated successfully')
          this.fill()
        })
        .catch((err) => {
          console.log(err)
        })
    },
    delete(type) {
      const response = useDelete('/type/delete', { id: type.id })

      response
        .then((res) => {
          // store user details and jwt in local storage to keep user logged in between page refreshes
          localStorage.removeItem('types')
          this.fill()
        })
        .catch((err) => {
          console.log(err)
        })
    }
  }
})

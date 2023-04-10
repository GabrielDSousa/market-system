import { defineStore } from 'pinia'
import { useDelete, useGet, usePost, usePut } from '@/composables/request'
import { useAlertStore } from '@/stores/AlertStore'

export const useProductStore = defineStore('ProductStore', {
  state: () => ({
    products: [
      {
        id: 1,
        name: 'Type 1',
        description: 'Description 1',
        price: 100,
        type_id: 1
      }
    ],
    toUpdate: {
      id: null,
      name: null,
      description: null,
      price: null,
      type_id: null
    }
  }),
  actions: {
    fill() {
      if (
        typeof localStorage.getItem('products') === 'undefined' ||
        localStorage.getItem('products') === null
      ) {
        const response = useGet('/products')

        response
          .then((res) => {
            // store user details and jwt in local storage to keep user logged in between page refreshes
            localStorage.setItem('products', JSON.stringify(res))
            this.products = res
          })
          .catch((err) => {
            console.log(err)
          })
      } else {
        this.products = JSON.parse(localStorage.getItem('products'))
      }

      if (
        typeof localStorage.getItem('toUpdate') === 'undefined' ||
        localStorage.getItem('toUpdate') === null
      ) {
        this.toUpdate = JSON.parse(localStorage.getItem('toUpdate'))
        localStorage.removeItem('toUpdate')
      }
    },
    empty() {
      this.products = [
        {
          id: null,
          name: null,
          description: null,
          price: null,
          type_id: null
        }
      ]
    },
    filter($parameter = 'id', $value) {
      return this.products.filter((product) => product[$parameter] === $value)
    },
    add($product) {
      const response = usePost('/product/store', $product)

      response
        .then((res) => {
          // store user details and jwt in local storage to keep user logged in between page refreshes
          this.products.push(res)
          const alertStore = useAlertStore()
          alertStore.alertSuccess('Success', 'Product added successfully')
          localStorage.setItem('products', JSON.stringify(this.products))
        })
        .catch((err) => {
          console.log(err)
        })
    },
    prepareUpdate($product) {
      this.toUpdate = {
        id: $product.id,
        name: $product.name,
        description: $product.description,
        value: $product.value,
        type_id: $product.type_id
      }
      localStorage.setItem('toUpdate', JSON.stringify(this.toUpdate))
    },
    update($product) {
      const response = usePut('/product/update', $product)
      response
        .then((res) => {
          // store user details and jwt in local storage to keep user logged in between page refreshes
          localStorage.removeItem('products')
          const alertStore = useAlertStore()
          alertStore.alertSuccess('Success', 'Product updated successfully')
          this.fill()
        })
        .catch((err) => {
          console.log(err)
        })
    },
    delete($product) {
      const response = useDelete('/product/delete', { id: $product.id })
      const alertStore = useAlertStore()

      response
        .then((res) => {
          // store user details and jwt in local storage to keep user logged in between page refreshes
          localStorage.removeItem('products')
          const alertStore = useAlertStore()
          alertStore.alertSuccess('Success', 'Product deleted successfully')
          this.fill()
        })
        .catch((err) => {
          console.log(err)
        })
    }
  }
})

import { defineStore } from 'pinia'
import { useUserStore } from '@/stores/UserStore'
import { useConfigStore } from '@/stores/ConfigStore'
import { usePost } from '@/composables/request'
import { useAlertStore } from '@/stores/AlertStore'

export const useBagStore = defineStore('BagStore', {
  state: () => ({
    bag: {
      id: null,
      user_id: null,
      cart: [],
      value: null,
      tax: null,
      total: null
    }
  }),
  actions: {
    fill() {
      if (
        typeof localStorage.getItem('bag') !== 'undefined' &&
        localStorage.getItem('bag') !== null
      ) {
        this.bag = JSON.parse(localStorage.getItem('bag'))
        const userStore = useUserStore()
        this.bag.user_id = userStore.user.id ?? null
        if (this.bag.cart !== []) {
          this.bag.value = this.price
          this.bag.tax = this.tax
          this.bag.total = this.total
        }

        localStorage.setItem('bag', JSON.stringify(this.bag))
      } else {
        this.empty()
      }
    },
    empty() {
      this.bag = {
        id: null,
        user_id: null,
        cart: [],
        value: null,
        tax: null,
        total: null
      }
      localStorage.removeItem('bag')
    },
    add($bag) {
      this.bag = $bag
      localStorage.setItem('bag', JSON.stringify(this.bag))
    },
    addProduct($product) {
      this.bag.cart.push($product)
      localStorage.setItem('bag', JSON.stringify(this.bag))
    },
    saveBag() {
      this.bag.chart = JSON.stringify(this.bag.chart)
      const response = usePost('/sale/store', this.bag)

      response
        .then((res) => {
          const configStore = useConfigStore()
          configStore.bagOpen = false
          const alertStore = useAlertStore()
          alertStore.alertSuccess('Success', 'Bag saved successfully')
          this.empty()
        })
        .catch((err) => {
          console.log(err)
        })
    }
  },
  getters: {
    total(state) {
      if (this.bag.cart === []) return 0

      let total = 0
      state.bag.cart.forEach((item) => {
        total += item.total * 1
      })
      return total
    },
    tax(state) {
      if (state.bag.cart === []) return 0

      let tax = 0
      state.bag.cart.forEach((item) => {
        tax += item.tax * 1
      })
      return tax
    },
    price(state) {
      if (state.bag.cart === []) return 0

      let price = 0
      state.bag.cart.forEach((item) => {
        price += item.value * 1
      })

      return price
    }
  }
})

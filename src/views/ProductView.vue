<script setup>
import Menu from '@/components/TheMenu.vue'
import PageHeader from '@/components/PageHeader.vue'
import PageMain from '@/components/PageMain.vue'
import { storeToRefs } from 'pinia'
import { useProductStore } from '@/stores/ProductStore'
import { useTypeStore } from '@/stores/TypeStore'
import ProductBreadcrumbs from '@/components/ProductBreadcumbs.vue'
import { computed, ref } from 'vue'
import InputQuantity from '@/components/InputQuantity.vue'
import { useBagStore } from '@/stores/BagStore'
import { useConfigStore } from '@/stores/ConfigStore'

const props = defineProps(['id'])

const { products } = storeToRefs(useProductStore())
const { types } = storeToRefs(useTypeStore())

const product = products.value.filter((product) => product.id === parseInt(props.id))[0]
const type = types.value.find((type) => type.id === product.type_id)
const quantity = ref(1)

const value = computed(() => {
  return product.value * quantity.value
})

const tax = computed(() => {
  let value = (product.value * type.tax) / 100
  value = value.toFixed(2)
  value = value.toString()
  return value * quantity.value
})

const total = computed(() => {
  return (product.value + tax.value) * quantity.value
})

const bagStore = useBagStore()
const configStore = useConfigStore()

function addProduct() {
  let toAdd = {
    product: product,
    quantity: quantity.value,
    value: value.value,
    tax: tax.value,
    total: total.value
  }
  bagStore.addProduct(toAdd)
  configStore.toggleBag()
}
</script>

<template>
  <Menu />
  <PageHeader>Market</PageHeader>
  <PageMain>
    <div class="bg-white">
      <div class="pt-6">
        <ProductBreadcrumbs :product="product" :type="type" />

        <!-- Image gallery -->
        <div
          class="mx-auto mt-6 max-w-2xl px-2 space-y-8 pb-8 items-end sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-2 lg:gap-x-8 lg:px-8"
        >
          <div class="aspect-h-4 aspect-w-3 overflow-hidden rounded-lg lg:block">
            <img
              :alt="'Product image of ' + product.name"
              :src="'https://source.unsplash.com/random/400x500/?' + product.name + ',product'"
              class="h-full w-full object-cover object-center"
            />
          </div>

          <div class="lg:flex lg:flex-col lg:justify-between lg:h-full">
            <div>
              <div class="lg:col-span-2 lg:border-r lg:border-gray-200 lg:pr-8">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                  {{ product.name }}
                </h1>
              </div>
              <div>
                <h3 class="sr-only">Description</h3>

                <div class="space-y-6">
                  <p class="text-base text-gray-900">{{ product.description }}</p>
                </div>
              </div>
            </div>

            <div>
              <h2 class="sr-only">Product information</h2>
              <div class="flex flex-col lg:flex-row justify-between">
                <div>
                  <div class="flex justify-between w-48">
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                      Value:
                      {{
                        new Intl.NumberFormat('pt-BR', {
                          style: 'currency',
                          currency: 'BRL',
                          minimumFractionDigits: 2
                        }).format(value)
                      }}
                    </p>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                      Tax:
                      {{
                        new Intl.NumberFormat('pt-BR', {
                          style: 'currency',
                          currency: 'BRL',
                          minimumFractionDigits: 2
                        }).format(tax)
                      }}
                    </p>
                  </div>

                  <p class="text-3xl tracking-tight text-gray-900">
                    {{
                      new Intl.NumberFormat('pt-BR', {
                        style: 'currency',
                        currency: 'BRL',
                        minimumFractionDigits: 2
                      }).format(total)
                    }}
                  </p>
                </div>

                <InputQuantity
                  id="quantity"
                  v-model="quantity"
                  label="quantity"
                  placeholder="Quantity"
                />
              </div>
              <form class="mt-10">
                <button
                  class="mt-10 flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                  type="submit"
                  @click.prevent="addProduct()"
                >
                  Add to bag
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </PageMain>
</template>

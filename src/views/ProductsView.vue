<script setup>
import Menu from '@/components/TheMenu.vue'
import PageHeader from '@/components/PageHeader.vue'
import PageMain from '@/components/PageMain.vue'
import { storeToRefs } from 'pinia'
import { useProductStore } from '@/stores/ProductStore'
import { useTypeStore } from '@/stores/TypeStore'

const params = new Proxy(new URLSearchParams(window.location.search), {
  get: (searchParams, prop) => searchParams.get(prop)
})

let filter = {
  type: null
}

const { products } = storeToRefs(useProductStore())
const { types } = storeToRefs(useTypeStore())

filter.type = params.type
if (filter.type) {
  const typeId = types.value.find(
    (type) => type.name.toLowerCase() === filter.type.toLowerCase()
  ).id
  products.value = products.value.filter((product) => product.type_id === typeId)
}
</script>

<template>
  <Menu />
  <PageHeader>Market</PageHeader>
  <PageMain>
    <div class="bg-white">
      <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Here are your products</h2>

        <div
          class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8"
        >
          <div v-for="product in products" :key="product.id" class="group relative">
            <a :href="'/products/' + product.id">
              <div
                class="min-h-80 aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80"
              >
                <img
                  :alt="'Product image of ' + product.name"
                  :src="'https://source.unsplash.com/random/400x500/?' + product.name + ',product'"
                  class="h-full w-full object-cover object-center lg:h-full lg:w-full"
                />
              </div>
              <div class="mt-4 flex justify-between">
                <div>
                  <h3 class="text-sm text-gray-700">
                    <a>
                      <span aria-hidden="true" class="absolute inset-0" />
                      {{ product.name }}
                    </a>
                  </h3>
                </div>
                <p class="text-sm font-medium text-gray-900">
                  {{
                    new Intl.NumberFormat('pt-BR', {
                      style: 'currency',
                      currency: 'BRL',
                      minimumFractionDigits: 2
                    }).format(parseInt(product.value))
                  }}
                </p>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </PageMain>
</template>

<script setup>
import { useProductStore } from '@/stores/ProductStore'
import { storeToRefs } from 'pinia'
import router from '@/router'

const { products } = storeToRefs(useProductStore())
const productsStore = useProductStore()

function prepareToAdd(product) {
  productsStore.prepareAdd(product)
  router.push('/products/store')
}

function prepareToUpdate(product) {
  productsStore.prepareUpdate(product)
  router.push('/products/update/' + product.id)
}

function prepareToDelete(product) {
  productsStore.delete(product)
  router.push('/admin')
}
</script>
<template>
  <div class="sm:p-7 p-4">
    <table class="w-full text-left">
      <thead>
        <tr class="text-gray-400">
          <th class="font-normal px-3 pt-0 pb-3 border-b border-gray-200 dark:border-gray-800">
            Name
          </th>
          <th class="font-normal px-3 pt-0 pb-3 border-b border-gray-200 dark:border-gray-800">
            Description
          </th>
          <th
            class="font-normal px-3 pt-0 pb-3 border-b border-gray-200 dark:border-gray-800 hidden md:table-cell"
          >
            Price
          </th>
          <th class="font-normal px-3 pt-0 pb-3 border-b border-gray-200 dark:border-gray-800">
            Type
          </th>
          <th
            class="font-normal px-3 pt-0 pb-3 border-b border-gray-200 dark:border-gray-800 sm:text-gray-400 text-white"
          >
            Actions
          </th>
        </tr>
      </thead>
      <tbody class="text-gray-600 dark:text-gray-100">
        <tr v-for="product in products" :key="product.id">
          <td class="sm:p-3 py-2 px-1 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center">{{ product.name }}</div>
          </td>
          <td class="sm:p-3 py-2 px-1 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center">
              {{ product.description }}
            </div>
          </td>
          <td
            class="sm:p-3 py-2 px-1 border-b border-gray-200 dark:border-gray-800 md:table-cell hidden"
          >
            {{
              new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
                minimumFractionDigits: 2
              }).format(parseInt(product.value))
            }}
          </td>
          <td class="sm:p-3 py-2 px-1 border-b border-gray-200 dark:border-gray-800 text-red-500">
            {{ product.type_id }}
          </td>
          <td class="sm:p-3 py-2 px-1 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center justify-items-center">
              <button
                class="w-8 h-8 inline-flex items-center justify-center text-gray-400 ml-auto"
                @click="prepareToUpdate(product)"
              >
                Update
              </button>
              <button
                class="w-8 h-8 inline-flex items-center justify-center text-gray-400 ml-auto"
                @click="prepareToDelete(product)"
              >
                Delete
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

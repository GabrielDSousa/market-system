<script setup>
import { storeToRefs } from 'pinia'
import { useTypeStore } from '@/stores/TypeStore'
import router from '@/router'

const { types } = storeToRefs(useTypeStore())
const typesStore = useTypeStore()

function prepareToUpdate(type) {
  typesStore.prepareToUpdate(type)
  router.push('/types/update/' + type.id)
}

function prepareToDelete(type) {
  typesStore.delete(type)
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
            Tax in percentage
          </th>
          <th
            class="font-normal px-3 pt-0 pb-3 border-b border-gray-200 dark:border-gray-800 sm:text-gray-400 text-white"
          >
            Actions
          </th>
        </tr>
      </thead>
      <tbody class="text-gray-600 dark:text-gray-100">
        <tr v-for="type in types" :key="type.id">
          <td class="sm:p-3 py-2 px-1 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center">{{ type.name }}</div>
          </td>
          <td class="sm:p-3 py-2 px-1 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center">
              {{ type.tax }}
            </div>
          </td>
          <td class="sm:p-3 py-2 px-1 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center justify-items-center">
              <button
                class="w-8 h-8 inline-flex items-center justify-center text-gray-400 ml-auto"
                @click="prepareToUpdate(type)"
              >
                Update
              </button>
              <button
                class="w-8 h-8 inline-flex items-center justify-center text-gray-400 ml-auto"
                @click="prepareToDelete(type)"
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

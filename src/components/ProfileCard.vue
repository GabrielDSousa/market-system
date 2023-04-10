<script setup>
import router from '@/router'

defineProps({
  title: {
    type: String,
    required: true
  },
  subtitle: {
    type: String,
    required: false
  },
  withAddButtonFor: {
    type: String,
    required: false,
    default: null
  },
  addNewRoute: {
    type: String,
    required: false,
    default: null
  }
})
</script>

<template>
  <div class="overflow-hidden bg-white shadow sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between">
      <div>
        <h3 class="text-base font-semibold leading-6 text-gray-900">{{ title }}</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ subtitle }}</p>
      </div>
      <button
        v-if="withAddButtonFor && addNewRoute"
        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        @click="router.push(addNewRoute)"
      >
        Add new {{ withAddButtonFor }}
      </button>
    </div>
    <div class="border-t border-gray-200">
      <slot></slot>
    </div>
  </div>
</template>

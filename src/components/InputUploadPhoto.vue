<script setup>
import { PhotoIcon } from '@heroicons/vue/24/solid'

defineProps({
  id: {
    type: String,
    required: true
  },
  label: {
    type: String,
    required: true
  },
  placeholder: {
    type: String,
    required: true
  },
  support: {
    type: String,
    required: false,
    default: 'PNG, JPG, GIF up to 10MB'
  }
})
</script>
<template>
  <label class="block text-sm font-medium leading-6 text-gray-900" for="cover-photo">{{
    label
  }}</label>
  <div
    class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10"
  >
    <div class="text-center">
      <PhotoIcon aria-hidden="true" class="mx-auto h-12 w-12 text-gray-300" />
      <div class="mt-4 flex text-sm leading-6 text-gray-600">
        <label
          :for="id"
          class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500"
        >
          <span>{{ placeholder }}</span>
          <input :id="id" :name="id" class="sr-only" type="file" />
        </label>
        <p class="pl-1">or drag and drop</p>
      </div>
      <p class="text-xs leading-5 text-gray-600">{{ support }}</p>
    </div>
  </div>
</template>

<script setup>
import { Tab, TabGroup, TabList } from '@headlessui/vue'

const props = defineProps({
  items: {
    type: Array,
    required: true
  },
  selected: {
    type: String,
    required: true
  },
  modelValue: {
    type: String,
    required: true
  }
})

defineEmits(['update:modelValue'])
</script>
<template>
  <div class="w-full max-w-md px-2 py-16 sm:px-0 mx-auto">
    <TabGroup>
      <TabList class="flex space-x-1 rounded-xl bg-blue-900/20 p-1">
        <Tab
          v-for="item in items"
          :key="item.name"
          v-slot="{ selected }"
          as="template"
          @click="$emit('update:modelValue', $event.target.slot)"
        >
          <button
            :class="[
              'w-full rounded-lg py-2.5 text-sm font-medium leading-5 text-blue-700',
              'ring-white ring-opacity-60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
              selected ? 'bg-white shadow' : 'text-blue-100 hover:bg-white/[0.12] hover:text-white'
            ]"
          >
            {{ item.name }}
          </button>
        </Tab>
      </TabList>
    </TabGroup>
  </div>
</template>

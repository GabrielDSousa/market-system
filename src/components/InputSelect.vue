<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: {
    type: String,
    required: true
  },
  placeholder: {
    type: String,
    required: true
  },
  id: {
    type: String,
    required: true
  },
  options: {
    type: Object,
    required: true
  },
  error: {
    type: String,
    required: false
  },
  disabled: {
    type: Boolean,
    required: false
  },
  required: {
    type: Boolean,
    required: false
  },
  defaultValue: {
    type: String,
    required: false
  },
  modelValue: {
    type: Number,
    required: false
  }
})

const emit = defineEmits(['update:modelValue'])
const value = computed({
  get() {
    return props.modelValue
  },
  set(value) {
    emit('update:modelValue', value)
  }
})
</script>
<template>
  <div class="sm:col-span-4">
    <label :for="id" class="block text-sm font-medium leading-6 text-gray-900">{{ label }}</label>
    <div class="mt-2">
      <select
        :id="id"
        v-model="value"
        :disabled="disabled"
        :name="id"
        :required="required"
        :value="defaultValue"
        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
      >
        <option
          v-for="option in options"
          :id="option.id"
          v-bind:key="option.id"
          v-bind:value="option.id"
        >
          {{ option.name }}
        </option>
      </select>
    </div>
  </div>
</template>

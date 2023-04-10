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
  readonly: {
    type: Boolean,
    required: false
  },
  max: {
    type: Number,
    required: false
  },
  min: {
    type: Number,
    required: false
  },
  pattern: {
    type: String,
    required: false
  },
  modelValue: {
    type: String,
    required: false,
    default: ''
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
    <label :for="id" class="block text-sm font-medium leading-6 text-gray-900">
      {{ label }}
    </label>
    <div class="mt-2">
      <div
        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md"
      >
        <input
          :id="id"
          v-model.trim="value"
          :max="max"
          :min="min"
          :name="id"
          :pattern="pattern"
          :placeholder="placeholder"
          :required="required"
          :size="max"
          class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
          type="email"
        />
      </div>
    </div>
  </div>
</template>

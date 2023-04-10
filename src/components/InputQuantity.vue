<template>
  <div class="input-quantity">
    <button class="input-quantity__button" @click="decrease">-</button>
    <input v-model="quantity" class="input-quantity__input" type="number" />
    <button class="input-quantity__button" @click="increase">+</button>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: {
    type: Number,
    required: false
  }
})

const emit = defineEmits(['update:modelValue'])
const quantity = computed({
  get() {
    return props.modelValue
  },
  set(value) {
    emit('update:modelValue', value)
  }
})

const increase = () => {
  quantity.value++
}

const decrease = () => {
  if (quantity.value > 1) {
    quantity.value--
  }
}
</script>

<style scoped>
.input-quantity {
  display: flex;
  align-items: center;
}

.input-quantity__button {
  width: 30px;
  height: 30px;
  border: 1px solid #ccc;
  border-radius: 4px;
  background-color: #fff;
  cursor: pointer;
}

.input-quantity__button:hover {
  background-color: #eee;
}

.input-quantity__button:active {
  background-color: #ddd;
}

.input-quantity__input {
  width: 50px;
  height: 30px;
  border: 1px solid #ccc;
  border-radius: 4px;
  text-align: center;
  margin: 0 10px;
}

.input-quantity__input:focus {
  outline: none;
}
</style>

<script setup>
import ProfileCard from '@/components/ProfileCard.vue'
import InputText from '@/components/InputText.vue'
import InputSelect from '@/components/InputSelect.vue'
import { reactive } from 'vue'
import InputTextArea from '@/components/InputTextArea.vue'
import { useTypeStore } from '@/stores/TypeStore'
import { useProductStore } from '@/stores/ProductStore'
import { storeToRefs } from 'pinia'
import InputNumber from '@/components/InputNumber.vue'
import router from '@/router'

defineProps({
  title: {
    type: String,
    required: false,
    default: 'Add a new product'
  }
})

const typeStore = useTypeStore()
typeStore.fill()
const { types } = storeToRefs(useTypeStore())

const form = reactive({
  id: null,
  name: '',
  description: '',
  value: 0,
  type_id: 0
})

const productStore = useProductStore()
const { toUpdate } = storeToRefs(useProductStore())
const product = toUpdate

if (product && product.value && product.value.id) {
  form.id = product.value.id
  form.name = product.value.name
  form.description = product.value.description
  form.value = product.value.value
  form.type_id = product.value.type_id
}

function addProduct() {
  if (product && product.value && product.value.id) {
    productStore.update(form)
    router.push('/admin')
    return
  }
  productStore.add(form)
  router.push('/admin')
}
</script>
<template>
  <ProfileCard :title="title" class="mt-12" subtitle="Fill the information's about the product">
    <form id="goToProductSaveForm" class="px-4 pb-8 sm:px-6" @submit.prevent="addProduct">
      <input v-if="form.id" :value="product.id" type="hidden" />
      <div class="space-y-12">
        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="col-span-full space-y-8">
            <InputText
              id="name"
              v-model="form.name"
              :required="true"
              label="Name"
              placeholder="Treats box"
            />
            <InputTextArea
              id="description"
              v-model="form.description"
              :required="true"
              label="Description"
              placeholder="Treats box with 11 treats to make your day happier"
            />
            <InputNumber
              id="value"
              v-model="form.value"
              :required="true"
              label="Price"
              placeholder=""
            />
          </div>
        </div>
      </div>

      <div class="border-b border-gray-900/10 pb-12">
        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <InputSelect
            id="type"
            v-model="form.type_id"
            :options="types"
            label="Product Type"
            placeholder="Product type"
          />
        </div>
      </div>

      <div class="mt-6 flex items-center justify-end gap-x-6">
        <button
          class="text-sm font-semibold leading-6 text-gray-900"
          type="button"
          @click="router.push('/admin')"
        >
          Cancel
        </button>
        <button
          class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
          type="submit"
        >
          Save
        </button>
      </div>
    </form>
  </ProfileCard>
</template>

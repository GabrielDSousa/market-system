<script setup>
import ProfileCard from '@/components/ProfileCard.vue'
import InputText from '@/components/InputText.vue'
import { reactive } from 'vue'
import { useTypeStore } from '@/stores/TypeStore'
import { storeToRefs } from 'pinia'
import router from '@/router'
import InputPercentage from '@/components/InputPercentage.vue'

defineProps({
  title: {
    type: String,
    required: false,
    default: 'Add a new product'
  }
})

const form = reactive({
  id: null,
  name: '',
  tax: ''
})

const typeStore = useTypeStore()
const { toUpdate } = storeToRefs(useTypeStore())
const type = toUpdate

if (type.value.id) {
  form.id = type.value.id
  form.name = type.value.name
  form.tax = type.value.tax
}

function addType() {
  if (type.value.id) {
    typeStore.update(form)
    router.push('/admin')
    return
  }
  typeStore.add(form)
  router.push('/admin')
}
</script>
<template>
  <ProfileCard :title="title" class="mt-12" subtitle="Fill the information's about the product">
    <form id="goToProductSaveForm" class="px-4 pb-8 sm:px-6" @submit.prevent="addType">
      <input :value="type.id" type="hidden" />
      <div class="space-y-12">
        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="col-span-full space-y-8">
            <InputText
              id="name"
              v-model="form.name"
              :required="true"
              label="Name"
              placeholder="Drinks"
            />
            <InputPercentage
              id="value"
              v-model="form.tax"
              :required="true"
              label="Price"
              placeholder=""
            />
          </div>
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

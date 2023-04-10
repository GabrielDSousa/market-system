<script setup>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { storeToRefs } from 'pinia'
import { useBagStore } from '@/stores/BagStore'
import { useConfigStore } from '@/stores/ConfigStore'

const bagStore = useBagStore()
const { bag, total, tax, price } = storeToRefs(useBagStore())
const { bagOpen } = storeToRefs(useConfigStore())

function removeProduct(id) {
  bag.value.cart = bag.value.cart.filter((item) => item.product.id !== id)
  bagStore.add(bag.value)
}

const configStore = useConfigStore()

function toggleBag() {
  configStore.toggleBag()
}

function saveBag() {
  bagStore.saveBag()
}
</script>
<template>
  <TransitionRoot :show="bagOpen" as="template">
    <Dialog as="div" class="relative z-10" @close="bagOpen">
      <TransitionChild
        as="template"
        enter="ease-in-out duration-500"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in-out duration-500"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
          <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
            <TransitionChild
              as="template"
              enter="transform transition ease-in-out duration-500 sm:duration-700"
              enter-from="translate-x-full"
              enter-to="translate-x-0"
              leave="transform transition ease-in-out duration-500 sm:duration-700"
              leave-from="translate-x-0"
              leave-to="translate-x-full"
            >
              <DialogPanel class="pointer-events-auto w-screen max-w-md">
                <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                  <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                    <div class="flex items-start justify-between">
                      <DialogTitle class="text-lg font-medium text-gray-900"
                        >Shopping cart
                      </DialogTitle>
                      <div class="ml-3 flex h-7 items-center">
                        <button
                          class="-m-2 p-2 text-gray-400 hover:text-gray-500"
                          type="button"
                          @click="toggleBag"
                        >
                          <span class="sr-only">Close panel</span>
                          <XMarkIcon aria-hidden="true" class="h-6 w-6" />
                        </button>
                      </div>
                    </div>

                    <div class="mt-8">
                      <div class="flow-root">
                        <ul class="-my-6 divide-y divide-gray-200" role="list">
                          <li v-for="item in bag.cart" :key="item.product.id" class="flex py-6">
                            <div
                              class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200"
                            >
                              <img
                                :alt="'Image for ' + item.product.name"
                                :src="'https://source.unsplash.com/random/?' + item.product.name"
                                class="h-full w-full object-cover object-center"
                              />
                            </div>

                            <div class="ml-4 flex flex-1 flex-col">
                              <div>
                                <div
                                  class="flex justify-between text-base font-medium text-gray-900"
                                >
                                  <h3>
                                    <a :href="'/?id=' + item.product.id">{{ item.product.name }}</a>
                                  </h3>
                                  <p class="ml-4">{{ item.product.price }}</p>
                                </div>
                              </div>
                              <div class="flex flex-1 items-end justify-between text-sm">
                                <p class="text-gray-500">Qty {{ item.quantity }}</p>

                                <div class="flex">
                                  <button
                                    class="font-medium text-indigo-600 hover:text-indigo-500"
                                    type="button"
                                    @click="removeProduct(item.product.id)"
                                  >
                                    Remove
                                  </button>
                                </div>
                              </div>
                            </div>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>

                  <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                    <div class="flex flex-row justify-between pb-4">
                      <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Value:
                        {{
                          new Intl.NumberFormat('pt-BR', {
                            style: 'currency',
                            currency: 'BRL',
                            minimumFractionDigits: 2
                          }).format(price)
                        }}
                      </p>
                      <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Tax:
                        {{
                          new Intl.NumberFormat('pt-BR', {
                            style: 'currency',
                            currency: 'BRL',
                            minimumFractionDigits: 2
                          }).format(tax)
                        }}
                      </p>
                    </div>

                    <div class="flex justify-between text-base font-medium text-gray-900">
                      <p>Subtotal</p>
                      <p>
                        {{
                          new Intl.NumberFormat('pt-BR', {
                            style: 'currency',
                            currency: 'BRL',
                            minimumFractionDigits: 2
                          }).format(total)
                        }}
                      </p>
                    </div>
                    <p class="mt-0.5 text-sm text-gray-500">
                      Shipping and taxes calculated at checkout.
                    </p>
                    <div class="mt-6">
                      <button
                        class="flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700"
                        @click="saveBag"
                      >
                        Save purchase
                      </button>
                    </div>
                    <div class="mt-6 flex justify-center text-center text-sm text-gray-500">
                      <p>
                        or
                        <button
                          class="font-medium text-indigo-600 hover:text-indigo-500"
                          type="button"
                          @click="toggleBag"
                        >
                          Continue Shopping
                          <span aria-hidden="true"> &rarr;</span>
                        </button>
                      </p>
                    </div>
                  </div>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

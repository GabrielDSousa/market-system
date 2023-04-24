<script setup>
import {
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  Menu,
  MenuButton,
  MenuItem,
  MenuItems
} from '@headlessui/vue'
import { Bars3Icon, BellIcon, ShoppingBagIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { storeToRefs } from 'pinia'
import { useUserStore } from '@/stores/UserStore'
import { useAuthStore } from '@/stores/AuthStore'
import BagSlider from '@/components/BagSlider.vue'
import { useConfigStore } from '@/stores/ConfigStore'

const { user, imageUrl } = storeToRefs(useUserStore())
const { logout, isLoggedIn } = useAuthStore()

const navigation = [{ name: 'Market', href: '/', current: window.location.pathname === '/' }]
const userNavigation = [
  { name: 'Your Profile', href: '/profile' },
  { name: 'Sign out', action: logout }
]

if (user.value.admin) {
  userNavigation.push({ name: 'Admin area', href: '/admin' })
}
const configStore = useConfigStore()

function toggleBag() {
  configStore.toggleBag()
}
</script>

<template>
  <Disclosure v-slot="{ open }" as="nav" class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <img
              alt="Your Company"
              class="h-8 w-8"
              src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500"
            />
          </div>
          <div class="hidden md:block">
            <div class="ml-10 flex items-baseline space-x-4">
              <a
                v-for="item in navigation"
                :key="item.name"
                :aria-current="item.current ? 'page' : undefined"
                :class="[
                  item.current
                    ? 'bg-gray-900 text-white'
                    : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                  'rounded-md px-3 py-2 text-sm font-medium'
                ]"
                :href="item.href"
                >{{ item.name }}</a
              >
            </div>
          </div>
        </div>
        <div class="hidden md:block">
          <div class="ml-4 flex items-center md:ml-6">
            <button
              class="rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
              type="button"
              @click="toggleBag"
            >
              <span class="sr-only">View shopping bag</span>
              <ShoppingBagIcon aria-hidden="true" class="h-6 w-6" />
            </button>

            <!-- Profile dropdown -->
            <Menu v-if="isLoggedIn" as="div" class="relative ml-3">
              <div>
                <MenuButton
                  class="flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                >
                  <span class="sr-only">Open user menu</span>
                  <img :src="imageUrl" alt="" class="h-8 w-8 rounded-full" />
                </MenuButton>
              </div>
              <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <MenuItems
                  class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                >
                  <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                    <a
                      :class="[
                        active ? 'bg-gray-100' : '',
                        'block px-4 py-2 text-sm text-gray-700'
                      ]"
                      :href="[item.href ? item.href : '#']"
                      @click="item.action"
                      >{{ item.name }}</a
                    >
                  </MenuItem>
                </MenuItems>
              </transition>
            </Menu>
          </div>
        </div>
        <div class="-mr-2 flex md:hidden">
          <!-- Mobile menu button -->
          <DisclosureButton
            class="inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
          >
            <span class="sr-only">Open main menu</span>
            <Bars3Icon v-if="!open" aria-hidden="true" class="block h-6 w-6" />
            <XMarkIcon v-else aria-hidden="true" class="block h-6 w-6" />
          </DisclosureButton>
        </div>
      </div>
    </div>

    <DisclosurePanel class="md:hidden">
      <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
        <DisclosureButton
          v-for="item in navigation"
          :key="item.name"
          :aria-current="item.current ? 'page' : undefined"
          :class="[
            item.current
              ? 'bg-gray-900 text-white'
              : 'text-gray-300 hover:bg-gray-700 hover:text-white',
            'block rounded-md px-3 py-2 text-base font-medium'
          ]"
          :href="item.href"
          as="a"
          >{{ item.name }}
        </DisclosureButton>
      </div>
      <div class="border-t border-gray-700 pb-3 pt-4">
        <div class="flex items-center px-5">
          <div class="flex-shrink-0">
            <img :src="imageUrl" alt="" class="h-10 w-10 rounded-full" />
          </div>
          <div class="ml-3">
            <div class="text-base font-medium leading-none text-white">{{ user.name }}</div>
            <div class="text-sm font-medium leading-none text-gray-400">{{ user.email }}</div>
          </div>
            <button
                    class="ml-auto flex-shrink-0 rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                    type="button"
                    @click="toggleBag"
            >
                <span class="sr-only">View shopping bag</span>
                <ShoppingBagIcon aria-hidden="true" class="h-6 w-6" />
            </button>
        </div>
        <div class="mt-3 space-y-1 px-2">
          <DisclosureButton
            v-for="item in userNavigation"
            :key="item.name"
            :href="item.href"
            as="a"
            class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white"
            >{{ item.name }}
          </DisclosureButton>
        </div>
      </div>
    </DisclosurePanel>
  </Disclosure>
  <BagSlider />
</template>

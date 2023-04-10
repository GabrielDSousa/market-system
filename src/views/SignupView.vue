<script setup>
import { ref } from 'vue'
import { LockClosedIcon } from '@heroicons/vue/20/solid'
import { useUserStore } from '@/stores/UserStore'

const name = ref('')
const email = ref('')
const password = ref('')
const confirmation = ref('')
const userStore = useUserStore()
const errors = userStore.errors

function add() {
  userStore.add(name.value, email.value, password.value, confirmation.value)
}
</script>

<template>
  <div class="flex min-h-full items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
      <div>
        <img
          alt="Your Company"
          class="mx-auto h-12 w-auto"
          src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
        />
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
          Create your account
        </h2>
      </div>
      <form class="mt-8 space-y-6" method="POST" @submit.prevent="add">
        <div class="space-y-8 rounded-md shadow-sm">
          <div>
            <label class="sr-only" for="email-address">Name</label>
            <input
              id="name"
              v-model="name"
              :required="true"
              autocomplete="name"
              class="relative block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              name="name"
              placeholder="Your name"
              type="text"
            />
            <ul v-if="errors.name !== null" class="mt-1 max-w-2xl text-sm text-red-400">
              <li v-for="(error, value) in errors.name">{{ value }} : {{ error }}</li>
            </ul>
          </div>
          <div>
            <label class="sr-only" for="email-address">Email address</label>
            <input
              id="email-address"
              v-model="email"
              :required="true"
              autocomplete="email"
              class="relative block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              name="email"
              placeholder="Email address"
              type="email"
            />
            <ul v-if="errors.email !== null" class="mt-1 max-w-2xl text-sm text-red-400">
              <li v-for="(error, value) in errors.email">{{ value }} : {{ error }}</li>
            </ul>
          </div>
          <div>
            <label class="sr-only" for="password">Password</label>
            <input
              id="password"
              v-model="password"
              :required="true"
              autocomplete="current-password"
              class="relative block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              name="password"
              placeholder="Password"
              type="password"
            />
            <ul v-if="errors.password !== null" class="mt-1 max-w-2xl text-sm text-red-400">
              <li v-for="(error, value) in errors.password">{{ value }} : {{ error }}</li>
            </ul>
          </div>
          <div>
            <label class="sr-only" for="password">Password confirmation</label>
            <input
              id="confirmation"
              v-model="confirmation"
              :required="true"
              autocomplete="current-password"
              class="relative block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              name="confirmation"
              placeholder="Password confirmation"
              type="password"
            />
            <ul v-if="errors.confirmation !== null" class="mt-1 max-w-2xl text-sm text-red-400">
              <li v-for="(error, value) in errors.confirmation">{{ value }} : {{ error }}</li>
            </ul>
          </div>
        </div>
        <div>
          <button
            class="group relative flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            type="submit"
          >
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
              <LockClosedIcon
                aria-hidden="true"
                class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400"
              />
            </span>
            Sign up
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
defineProps({
  product: {
    type: Object,
    required: true
  },
  type: {
    type: Object,
    required: true
  }
})
</script>

<template>
  <nav aria-label="Breadcrumb">
    <ol
      class="mx-auto flex max-w-2xl items-center space-x-2 px-4 sm:px-6 lg:max-w-7xl lg:px-8"
      role="list"
    >
      <li key="products">
        <div class="flex items-center">
          <a class="mr-2 text-sm font-medium text-gray-900" href="/public">Market</a>
          <svg
            aria-hidden="true"
            class="h-5 w-4 text-gray-300"
            fill="currentColor"
            height="20"
            viewBox="0 0 16 20"
            width="16"
          >
            <path d="M5.697 4.34L8.98 16.532h1.327L7.025 4.341H5.697z" />
          </svg>
        </div>
      </li>

      <li :key="type.name">
        <div class="flex items-center">
          <a :href="'/?type=' + type.name" class="mr-2 text-sm font-medium text-gray-900">{{
            type.name
          }}</a>
          <svg
            aria-hidden="true"
            class="h-5 w-4 text-gray-300"
            fill="currentColor"
            height="20"
            viewBox="0 0 16 20"
            width="16"
          >
            <path d="M5.697 4.34L8.98 16.532h1.327L7.025 4.341H5.697z" />
          </svg>
        </div>
      </li>
      <li class="text-sm">
        <a
          :href="'/products/' + product.id"
          aria-current="page"
          class="font-medium text-gray-500 hover:text-gray-600"
          >{{ product.name }}</a
        >
      </li>
    </ol>
  </nav>
</template>

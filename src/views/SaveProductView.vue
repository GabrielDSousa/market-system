<script setup>
import Menu from '@/components/TheMenu.vue'
import PageHeader from '@/components/PageHeader.vue'
import PageMain from '@/components/PageMain.vue'
import SaveProduct from '@/components/SaveProduct.vue'
import { ref } from 'vue'

const title = ref('')
</script>

<template>
  <Menu />
  <PageHeader>Profile</PageHeader>
  <PageMain>
    <SaveProduct :title="title" />
  </PageMain>
</template>

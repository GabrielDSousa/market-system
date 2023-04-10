<script setup>
import Menu from '@/components/TheMenu.vue'
import PageHeader from '@/components/PageHeader.vue'
import PageMain from '@/components/PageMain.vue'
import { ref } from 'vue'
import SaveType from '@/components/SaveType.vue'

const title = ref('')
</script>

<template>
  <Menu />
  <PageHeader>Profile</PageHeader>
  <PageMain>
    <SaveType :title="title" />
  </PageMain>
</template>

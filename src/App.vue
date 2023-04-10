<script setup>
import { RouterView } from 'vue-router'
import AlertsComponent from './components/AlertsComponent.vue'
import { storeToRefs } from 'pinia'
import { useAlertStore } from '@/stores/AlertStore'

const { icon, text, title } = storeToRefs(useAlertStore())
</script>

<template>
  <div class="h-full">
    <RouterView />
    <AlertsComponent :icon="icon" :text="text" :title="title" />
  </div>
</template>

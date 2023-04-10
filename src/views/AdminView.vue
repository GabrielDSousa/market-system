<script setup>
import Menu from '@/components/TheMenu.vue'
import PageHeader from '@/components/PageHeader.vue'
import PageMain from '@/components/PageMain.vue'
import { useUserStore } from '@/stores/UserStore'
import { storeToRefs } from 'pinia'
import ListProducts from '@/components/ListProducts.vue'
import ListTypes from '@/components/ListTypes.vue'
import ProfileCard from '@/components/ProfileCard.vue'

const { user } = storeToRefs(useUserStore())
</script>

<template>
  <Menu />
  <PageHeader>Profile</PageHeader>
  <PageMain>
    <ProfileCard
      addNewRoute="/types/store"
      class="mt-12"
      subtitle="Admin area to manage and add Type"
      title="List of the types"
      withAddButtonFor="Types"
    />
    <ListTypes v-if="user.admin" />

    <ProfileCard
      addNewRoute="/products/store"
      class="mt-12"
      subtitle="Admin area to manage and add Products"
      title="List of the products"
      withAddButtonFor="Products"
    />
    <ListProducts v-if="user.admin" />
  </PageMain>
</template>

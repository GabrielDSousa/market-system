<script setup>
import Menu from '@/components/TheMenu.vue'
import PageHeader from '@/components/PageHeader.vue'
import PageMain from '@/components/PageMain.vue'
import { useUserStore } from '@/stores/UserStore'
import { storeToRefs } from 'pinia'
import ProfileCard from '@/components/ProfileCard.vue'

const { user } = storeToRefs(useUserStore())
</script>

<template>
  <Menu />
  <PageHeader>Profile</PageHeader>
  <PageMain>
    <ProfileCard subtitle="Personal details." title="Your profile">
      <dl>
        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">Full name</dt>
          <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ user.name }}</dd>
        </div>
        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">Email address</dt>
          <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
            {{ user.email }}
          </dd>
        </div>
      </dl>
    </ProfileCard>
  </PageMain>
</template>

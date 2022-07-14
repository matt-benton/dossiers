<template>
  <TheNavbar :user="user" />

  <!-- Page Heading -->
  <header class="bg-white shadow" v-if="$slots.header">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <slot name="header" />
    </div>
  </header>

  <!-- Page Content -->
  <main>
    <div class="container">
      <slot />
    </div>
    <FlashAlert />
  </main>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import TheNavbar from '../Components/TheNavbar.vue'
import FlashAlert from '../Components/FlashAlert.vue'
import User from '../Types/User'
import { usePage } from '@inertiajs/inertia-vue3'

const user = computed(() => {
  return (usePage().props.value.auth as { user: User }).user
})
</script>

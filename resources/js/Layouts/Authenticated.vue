<template>
  <TheNavbar :user="$page.props.auth.user" />

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
import TheNavbar from '../Components/TheNavbar.vue'
import FlashAlert from '../Components/FlashAlert.vue'
</script>

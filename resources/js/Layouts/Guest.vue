<template>
  <TheNavbar />
  <main>
    <div class="container">
      <slot />
    </div>
  </main>
</template>

<script setup lang="ts">
import TheNavbar from '../Components/TheNavbar.vue'
</script>

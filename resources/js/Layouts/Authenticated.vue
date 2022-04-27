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
  </main>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import TheNavbar from '../Components/TheNavbar.vue'

export default {
  components: {
    Link,
    TheNavbar,
  },
}
</script>

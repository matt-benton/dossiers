<template>
  <TheNavbar />
  <main>
    <div class="container">
      <slot />
    </div>
  </main>
</template>

<script>
import TheNavbar from '../Components/TheNavbar.vue'

export default {
  components: {
    TheNavbar,
  },
}
</script>

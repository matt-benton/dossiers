<template>
  <Transition>
    <div v-if="alertVisible" class="alert">
      {{ message }}
    </div>
  </Transition>
</template>

<script>
import { computed } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'

export default {
  data() {
    return { alertVisible: false }
  },
  watch: {
    message() {
      this.flashAlert()
    },
  },
  setup() {
    const message = computed(() => usePage().props.value.flash.message)
    return { message }
  },
  mounted() {
    if (this.message) {
      this.flashAlert()
    }
  },
  methods: {
    flashAlert() {
      this.alertVisible = true
      setTimeout(() => (this.alertVisible = false), 4000)
    },
  },
}
</script>

<style scoped>
.alert {
  position: absolute;
  left: 50%;
  bottom: var(--size-6);
  transform: translate(-50%, 0);
  transition: transform 0.4s;
}

.v-enter-from,
.v-leave-to {
  transform: translate(-50%, 150%);
}
</style>

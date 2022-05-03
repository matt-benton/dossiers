<template>
  <Alert :visible="alertVisible">
    {{ message }}
  </Alert>
</template>

<script>
import { computed } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import Alert from './Alert.vue'

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
  components: {
    Alert,
  },
}
</script>

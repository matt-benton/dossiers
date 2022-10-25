<template>
  <Alert :visible="alertVisible">
    {{ message }}
  </Alert>
</template>

<script setup lang="ts">
import { computed, ref, watch, onMounted } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import Alert from './Alert.vue'

let alertVisible = ref(false)

onMounted(() => {
  if (message.value) {
    flashAlert()
  }
})

const message = computed<string>(() => {
  /**
   * The flash prop is an unknown type so it has to be narrowed
   * and cast in order to not throw an error.
   */
  if (
    usePage().props.value.flash &&
    typeof usePage().props.value.flash === 'object' &&
    Object.hasOwnProperty.call(usePage().props.value.flash, 'message')
  ) {
    return (usePage().props.value.flash as { message: string }).message
  }

  return ''
})

watch(message, function () {
  if (message) {
    flashAlert()
  }
})

function flashAlert() {
  alertVisible.value = true
  setTimeout(() => (alertVisible.value = false), 4000)
}
</script>

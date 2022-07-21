<script setup lang="ts">
import BreezeGuestLayout from '../../Layouts/Guest.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'

const form = useForm({
  password: '',
})

const submit = () => {
  form.post('/logout', {
    onFinish: () => form.reset(),
  })
}
</script>

<template>
  <BreezeGuestLayout>
    <Head title="Confirm Password" />

    <div class="mb-4 text-sm text-gray-600">
      This is a secure area of the application. Please confirm your password
      before continuing.
    </div>

    <BreezeValidationErrors class="mb-4" />

    <form @submit.prevent="submit">
      <div></div>
    </form>
  </BreezeGuestLayout>
</template>

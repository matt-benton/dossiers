<script setup lang="ts">
import BreezeGuestLayout from '@/Layouts/Guest.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'

const props = defineProps({
  email: String,
  token: String,
})

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post('/reset-password', {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <BreezeGuestLayout>
    <Head title="Reset Password" />

    <BreezeValidationErrors class="mb-4" />

    <form @submit.prevent="submit">
      <div>
        <BreezeLabel for="email" value="Email" />
        <BreezeInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autofocus
          autocomplete="username"
        />
      </div>

      <div class="flex items-center justify-end mt-4"></div>
    </form>
  </BreezeGuestLayout>
</template>

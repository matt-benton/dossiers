<template>
  <Layout>
    <Head title="Forgot Password" />

    <div class="alert">
      Forgot your password? No problem. Just let us know your email address and
      we will email you a password reset link that will allow you to choose a
      new one.
    </div>

    <div class="center-container-xs">
      <div class="card">
        <form @submit.prevent="submit">
          <div class="form-group">
            <label for="email">Email</label>
            <input
              id="email"
              type="email"
              v-model="form.email"
              required
              autofocus
              autocomplete="username"
            />
          </div>

          <button class="btn-primary" :disabled="form.processing">
            Email Password Reset Link
          </button>
        </form>
      </div>
    </div>
  </Layout>
</template>

<script setup lang="ts">
import Layout from '../../Layouts/Guest.vue'
import { useForm } from '@inertiajs/inertia-vue3'

const form = useForm({
  email: '',
})

function submit() {
  form.post('/forgot-password')
}

defineProps({
  type: String,
})
</script>

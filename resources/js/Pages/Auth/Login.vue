<template>
  <Layout>
    <Head title="Log in" />
    <div class="center-container-xs">
      <div class="card">
        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
          {{ status }}
        </div>
        <h2 class="text-lg">Log In</h2>

        <form @submit.prevent="submit">
          <div class="form-group">
            <label for="email">Email</label>
            <input
              id="email"
              type="email"
              :class="{ 'border-danger': form.errors.email }"
              v-model="form.email"
              autofocus
              autocomplete="username"
            />
            <span class="text-danger" v-if="form.errors.email">{{
              form.errors.email
            }}</span>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input
              id="password"
              type="password"
              v-model="form.password"
              autocomplete="current-password"
              :class="{ 'border-danger': form.errors.password }"
            />
            <span class="text-danger" v-if="form.errors.password">{{
              form.errors.password
            }}</span>
          </div>

          <div class="checkbox-group">
            <label for="remember">Remember me</label>
            <input
              type="checkbox"
              name="remember"
              id="remember"
              v-model="form.remember"
            />
          </div>
          <div class="flex justify-end">
            <button class="btn-primary">Submit</button>
          </div>
        </form>
      </div>
      <small
        ><Link v-if="canResetPassword" href="/forgot-password">
          Forgot your password?
        </Link></small
      >
    </div>
  </Layout>
</template>

<script setup lang="ts">
import Layout from '../../Layouts/Guest.vue'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

function submit() {
  form.post('/login', {
    onFinish: () => form.reset('password'),
  })
}

defineProps<{
  canResetPassword: Boolean
  status: String
}>()
</script>

<template>
  <Layout>
    <Head title="Forgot Password" />

    <div class="alert">
      Forgot your password? No problem. Just let us know your email address and
      we will email you a password reset link that will allow you to choose a
      new one.
    </div>

    <div v-if="status">
      {{ status }}
    </div>

    <br />

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

<script>
import Layout from '@/Layouts/Guest.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'

export default {
  setup() {
    const form = useForm({
      email: '',
    })

    return { form }
  },
  methods: {
    submit() {
      this.form.post(route('password.email'))
    },
  },
  props: {
    status: {
      type: String,
    },
  },
  components: {
    Layout,
    Head,
  },
}
</script>

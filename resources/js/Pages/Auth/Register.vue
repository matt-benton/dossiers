<template>
  <Layout>
    <Head title="Register" />
    <div class="center-container-xs">
      <div class="card">
        <h2 class="text-lg">Register</h2>
        <form @submit.prevent="submit">
          <div class="col-2">
            <div class="form-group">
              <label for="first_name">First Name</label>
              <input
                id="first_name"
                type="text"
                v-model="form.first_name"
                :class="{ 'border-danger': form.errors.first_name }"
                autofocus
                autocomplete="name"
              />
              <span class="text-danger" v-if="form.errors.first_name">{{
                form.errors.first_name
              }}</span>
            </div>

            <div class="form-group">
              <label for="last_name">Last Name</label>
              <input
                id="last_name"
                type="text"
                v-model="form.last_name"
                :class="{ 'border-danger': form.errors.last_name }"
                autofocus
                autocomplete="name"
              />
              <span class="text-danger" v-if="form.errors.last_name">{{
                form.errors.last_name
              }}</span>
            </div>
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input
              id="email"
              type="email"
              :class="{ 'border-danger': form.errors.email }"
              v-model="form.email"
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
              :class="{ 'border-danger': form.errors.password }"
              v-model="form.password"
              autocomplete="new-password"
            />
            <span class="text-danger" v-if="form.errors.password">{{
              form.errors.password
            }}</span>
          </div>
          <div class="flex justify-end">
            <button class="btn-primary">Submit</button>
          </div>
        </form>
      </div>
      <small><Link href="/login"> Already registered? </Link></small>
    </div>
  </Layout>
</template>
<script>
import Layout from '@/Layouts/Guest.vue'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'

export default {
  setup() {
    const form = useForm({
      first_name: '',
      last_name: '',
      email: '',
      password: '',
    })

    return { form }
  },
  methods: {
    submit() {
      this.form.post('/register', {
        onFinish: () => this.form.reset('password'),
      })
    },
  },
  components: {
    Layout,
    Link,
    Head,
  },
}
</script>

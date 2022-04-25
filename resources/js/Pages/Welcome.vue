<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3'

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
})
</script>

<template>
  <Head title="Welcome" />

  <div class="container">
    <h1>Dossiers</h1>
    <div v-if="canLogin">
      <Link v-if="$page.props.auth.user" :href="route('dashboard')">
        Dashboard
      </Link>

      <template v-else>
        <Link :href="route('login')"> Log in </Link>

        <Link v-if="canRegister" :href="route('register')"> Register </Link>
      </template>
    </div>
  </div>
</template>

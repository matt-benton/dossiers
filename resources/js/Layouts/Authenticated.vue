<template>
  <nav>
    <Link :href="route('dashboard')"> Home </Link>

    <!-- Navigation Links -->
    <ul>
      <li>
        <Link :href="route('dashboard')"> Dashboard </Link>
        <Link :href="route('people.index')"> People </Link>
      </li>
    </ul>
  </nav>

  <!-- Page Heading -->
  <header class="bg-white shadow" v-if="$slots.header">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <slot name="header" />
    </div>
  </header>

  <!-- Page Content -->
  <main>
    <slot />
  </main>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'

export default {
  components: {
    Link,
  },
}
</script>

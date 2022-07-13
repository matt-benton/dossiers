<template>
  <nav aria-label="Breadcrumb">
    <ol>
      <li v-for="link in links" :class="{ active: link.active }">
        <Link v-if="link.url" :href="link.url">{{ link.title }}</Link>
        <span v-else>{{ link.title }}</span>
      </li>
    </ol>
  </nav>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/inertia-vue3'

interface BreadcrumbLink {
  url?: string
  title: string
  active?: boolean
}

defineProps<{
  links: Array<BreadcrumbLink>
}>()
</script>

<style scoped>
ol {
  list-style-type: none;
  padding-left: 0;
  display: flex;
}

li + li::before {
  content: '/';
  padding: var(--size-2);
}
</style>

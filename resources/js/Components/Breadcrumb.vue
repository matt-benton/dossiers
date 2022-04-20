<template>
  <nav aria-label="Breadcrumb">
    <ol>
      <li v-for="link in links" :class="{ active: link.active }">
        <a v-if="link.url" :href="link.url">{{ link.title }}</a>
        <span v-else>{{ link.title }}</span>
      </li>
    </ol>
  </nav>
</template>

<script>
export default {
  props: {
    links: {
      required: true,
      type: Array,
    },
  },
}
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

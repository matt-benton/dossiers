<template>
  <Head title="Dashboard" />

  <Layout>
    <div class="row">
      <OccurrenceInput />
    </div>
    <div class="row">
      <div v-if="occurrences" id="occurrences-list">
        <OccurrenceCard v-for="occ in occurrences" :occurrence="occ" />
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { defineProps } from 'vue'
import Layout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3'
import OccurrenceInput from '../Components/OccurrenceInput.vue'
import OccurrenceCard from '../Components/OccurrenceCard.vue'

let props = defineProps({
  occurrences: Array,
})
</script>

<style scoped>
#occurrences-list .card {
  margin-bottom: var(--size-5);
}
</style>

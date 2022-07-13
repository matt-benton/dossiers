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

<script setup lang="ts">
import Layout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3'
import OccurrenceInput from '../Components/OccurrenceInput.vue'
import OccurrenceCard from '../Components/OccurrenceCard.vue'
import type Occurrence from '../Types/Occurrence'

let props = defineProps<{
  occurrences: Array<Occurrence>
}>()
</script>

<style scoped>
#occurrences-list .card {
  margin-bottom: var(--size-5);
}
</style>

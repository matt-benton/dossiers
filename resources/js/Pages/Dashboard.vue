<template>
  <Head title="Dashboard" />

  <Layout>
    <div class="row">
      <OccurrenceInput />
    </div>
    <div class="row">
      <div v-if="occurrences" id="occurrences-list">
        <div class="card" v-for="occ in occurrences">
          <p>{{ occ.description }}</p>
          <small>{{ useFormatDate(new Date(occ.created_at)) }}</small>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { defineProps } from 'vue'
import { useFormatDate } from '../Composables/format'
import Layout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3'
import OccurrenceInput from '../Components/OccurrenceInput.vue'

let props = defineProps({
  occurrences: Array,
})
</script>

<style scoped>
#occurrences-list .card {
  margin-bottom: var(--size-5);
}
</style>

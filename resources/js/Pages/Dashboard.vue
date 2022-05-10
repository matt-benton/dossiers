<template>
  <Head title="Dashboard" />

  <Layout>
    <div>
      <OccurrenceInput />
    </div>
  </Layout>
</template>

<script>
import Layout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3'
import OccurrenceInput from '../Components/OccurrenceInput.vue'

export default {
  components: {
    Head,
    Layout,
    OccurrenceInput,
  },
}
</script>

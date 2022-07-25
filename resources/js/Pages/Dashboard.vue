<template>
  <Head title="Dashboard" />

  <Layout>
    <div class="row">
      <DevelopmentInput />
    </div>
    <div class="row">
      <div v-if="threads" id="threads-list">
        <div v-for="thread in threads">
          <DevelopmentCard
            v-for="dev in thread.developments"
            :development="dev"
            :people-in-thread="thread.people"
          />
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup lang="ts">
import Layout from '../Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3'
import DevelopmentInput from '../Components/DevelopmentInput.vue'
import DevelopmentCard from '../Components/DevelopmentCard.vue'
import Thread from '../Types/Thread'

defineProps<{
  threads: Array<Thread>
}>()
</script>

<style scoped>
#threads-list .card {
  margin-bottom: var(--size-5);
}
</style>

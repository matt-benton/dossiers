<template>
  <Head title="Dashboard" />

  <Layout>
    <div class="row">
      <DevelopmentInput unique-id="dash" :focus="true" />
    </div>
    <div class="row">
      <div v-if="threads">
        <ThreadList v-for="thread in threads" :thread="thread" />
      </div>
    </div>
  </Layout>
</template>

<script setup lang="ts">
import Layout from '../Layouts/Authenticated.vue'
import DevelopmentInput from '../Components/DevelopmentInput.vue'
import ThreadList from '../Components/ThreadList.vue'
import Thread from '../Types/Thread'

defineProps<{
  threads: Array<Thread>
}>()
</script>

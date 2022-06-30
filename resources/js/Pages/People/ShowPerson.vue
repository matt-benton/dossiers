<template>
  <Head :title="person.name" />
  <Authenticated>
    <div class="row">
      <Breadcrumb :links="breadcrumbLinks" />
      <h2>{{ person.name }}</h2>
      <h4>{{ person.relationship }}</h4>
      <p v-if="person.birthmonth && person.birthday">
        <Cake /> {{ person.birthmonth_text }} {{ person.birthday }}
      </p>
      <Link :href="`/people/${person.id}/edit`" class="btn btn-icon"
        ><Pencil /> Update</Link
      >
    </div>
    <div v-if="person.occurrences" id="occurrences-list">
      <div class="card" v-for="occ in person.occurrences">
        <p>{{ occ.description }}</p>
        <small>{{ useFormatDate(new Date(occ.created_at)) }}</small>
      </div>
    </div>
  </Authenticated>
</template>

<script setup>
import { reactive, defineProps } from 'vue'
import { Link, Head } from '@inertiajs/inertia-vue3'
import Authenticated from '../../Layouts/Authenticated.vue'
import Breadcrumb from '../../Components/Breadcrumb.vue'
import Cake from '../../Components/Icons/Cake.vue'
import Pencil from '../../Components/Icons/Pencil.vue'
import { useFormatDate } from '../../Composables/format'

let breadcrumb = reactive([
  {
    url: '/people',
    title: 'People',
  },
  {
    title: props.person.name,
  },
])

let props = defineProps({
  person: {
    required: true,
    type: Object,
  },
})
</script>

<style scoped>
#occurrences-list .card {
  margin-bottom: var(--size-5);
}
</style>

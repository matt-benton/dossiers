<template>
  <Head :title="group.name" />
  <Authenticated>
    <div class="row">
      <Breadcrumb :links="breadcrumb" />
      <h2>{{ group.name }}</h2>
      <Link :href="`/groups/${group.id}/edit`" class="btn btn-icon-text"
        ><Pencil /> Update</Link
      >
    </div>
  </Authenticated>
</template>

<script setup lang="ts">
import { Link, Head } from '@inertiajs/inertia-vue3'
import { reactive } from 'vue'
import Breadcrumb from '../../Components/Breadcrumb.vue'
import Authenticated from '../../Layouts/Authenticated.vue'
import Group from '../../Types/Group'
import Pencil from '../../Components/Icons/Pencil.vue'

const props = defineProps<{
  group: Group
}>()

const breadcrumb = reactive([
  {
    url: '/groups',
    title: 'Groups',
  },
  {
    title: props.group.name,
  },
])
</script>

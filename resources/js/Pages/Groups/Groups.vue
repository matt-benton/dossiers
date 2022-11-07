<template>
  <Head title="Groups" />
  <Authenticated>
    <div class="center-container-sm">
      <h2 class="text-xl">Groups</h2>
      <div class="flex justify-end">
        <Link href="/groups/create">Add Group</Link>
      </div>
      <ul class="resource-list">
        <li v-for="group in groups" :key="group.id">
          <Link :href="`groups/${group.id}`">{{ group.name }}</Link>
        </li>
      </ul>
    </div>
  </Authenticated>
</template>

<script setup lang="ts">
import Authenticated from '../../Layouts/Authenticated.vue'
import Group from '../../Types/Group'

defineProps<{
  groups: Group[]
}>()
</script>

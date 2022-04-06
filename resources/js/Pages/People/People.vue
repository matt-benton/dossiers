<template>
  <Authenticated>
    <Link href="/people/create">Add Person</Link>
    <h1>list of people</h1>
    <ul>
      <li v-for="person in people" :key="person.id">
        {{ person.name }} - {{ person.relationship }}
      </li>
    </ul>
  </Authenticated>
</template>

<script>
import Authenticated from '../../Layouts/Authenticated.vue'
import { Link } from '@inertiajs/inertia-vue3'

export default {
  components: {
    Authenticated,
    Link,
  },
  props: ['people'],
}
</script>

<template>
  <Head :title="person.name" />
  <Authenticated>
    <Breadcrumb :links="breadcrumbLinks" />
    <h2>{{ person.name }}</h2>
    <h4>{{ person.relationship }}</h4>
    <p v-if="person.birthmonth && person.birthday">
      <Cake /> {{ person.birthmonth_text }} {{ person.birthday }}
    </p>
    <Link :href="`/people/${person.id}/edit`" class="btn btn-icon"
      ><Pencil /> Update</Link
    >
    <ul v-if="person.occurrences">
      <li v-for="occ in person.occurrences">{{ occ.description }}</li>
    </ul>
  </Authenticated>
</template>

<script>
import { Link, Head } from '@inertiajs/inertia-vue3'
import Authenticated from '../../Layouts/Authenticated.vue'
import Breadcrumb from '../../Components/Breadcrumb.vue'
import Cake from '../../Components/Icons/Cake.vue'
import Pencil from '../../Components/Icons/Pencil.vue'

export default {
  data() {
    return {
      breadcrumbLinks: [
        {
          url: '/people',
          title: 'People',
        },
        {
          title: this.person.name,
        },
      ],
    }
  },
  props: {
    person: {
      required: true,
      type: Object,
    },
  },
  components: {
    Authenticated,
    Breadcrumb,
    Cake,
    Pencil,
    Link,
    Head,
  },
}
</script>

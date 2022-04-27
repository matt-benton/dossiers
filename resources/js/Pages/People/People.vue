<template>
  <Head title="People" />
  <Authenticated>
    <div class="center-container-sm">
      <h2 class="text-lg">People</h2>
      <ul>
        <li v-for="person in people" :key="person.id">
          <p>
            <Link :href="`/people/${person.id}`">{{ person.name }}</Link>
          </p>
          <div>
            <small>{{ person.relationship }}</small>
            <small v-if="hasBirthday(person)" class="birthday-text">
              <Cake />{{ person.birthmonth_text }} {{ person.birthday }}
            </small>
          </div>
        </li>
      </ul>
      <Link href="/people/create">Add Person</Link>
    </div>
  </Authenticated>
</template>

<script>
import Authenticated from '../../Layouts/Authenticated.vue'
import Cake from '../../Components/Icons/Cake.vue'
import { Link, Head } from '@inertiajs/inertia-vue3'

export default {
  methods: {
    hasBirthday(person) {
      return person.birthmonth && person.birthday
    },
  },
  components: {
    Authenticated,
    Link,
    Cake,
    Head,
  },
  props: ['people'],
}
</script>

<style scoped>
ul {
  padding-left: 0;
  background-color: var(--cardBg);
  border-radius: var(--rounded-sm);
}

li {
  list-style-type: none;
  padding: var(--size-2);
  border-bottom: 1px solid var(--textColor);
}

li:last-of-type {
  border-bottom: none;
}

li p {
  margin-bottom: var(--size-1);
}

li a {
  text-decoration: none;
}

li > div:last-of-type {
  display: flex;
  justify-content: space-between;
}

li svg {
  margin-right: var(--size-2);
  height: var(--text-sm);
  width: var(--text-sm);
}

.birthday-text {
  display: inline-flex;
  align-items: center;
}
</style>

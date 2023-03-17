<template>
  <Head title="People" />
  <Authenticated>
    <div class="center-container-sm">
      <h2 class="text-xl">People</h2>
      <SearchMenuRow 
        :search-text="search.text" 
        :link="{ text: 'Add Person', url: '/people/create' }"
        @update:search-text="onSearchKeydown"
        @reset-search="resetSearch"
        placeholder="Name or Relationship"
      />
      <ul class="resource-list">
        <li v-for="person in displayedPeople" :key="person.id">
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
    </div>
  </Authenticated>
</template>

<script setup lang="ts">
import Authenticated from '../../Layouts/Authenticated.vue'
import Cake from '../../Components/Icons/Cake.vue'
import Person from '../../Types/Person'
import { reactive, computed } from 'vue'
import axios from 'axios'
import SearchMenuRow from '../../Components/SearchMenuRow.vue'

const displayedPeople = computed(() =>
  search.results.length > 0 ? search.results : props.people
)

interface Search {
  text: string
  results: Array<Person>
  timeout: number
}

let search: Search = reactive({
  text: '',
  results: [],
  timeout: 0,
})

function onSearchKeydown(searchText: string) {
  search.text = searchText

  clearTimeout(search.timeout)

  search.timeout = window.setTimeout(getPeople, 500)
}

async function getPeople() {
  await axios
    .get(`/search/people?name=${search.text}&relationship=${search.text}`)
    .then((response) => {
      search.results = response.data.people
    })
}

function resetSearch() {
  search.text = ''
  search.results = []
  search.timeout = 0
}

const props = defineProps<{
  people: Array<Person>
}>()

function hasBirthday(person: Person) {
  return person.birthmonth && person.birthday
}
</script>

<style scoped>

li p {
  margin-bottom: var(--size-1);
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

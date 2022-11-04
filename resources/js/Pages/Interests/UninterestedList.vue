<template>
  <div v-if="!everybodyInterested()">
    <div id="search-row">
      <label for="search">Search</label>
      <input
        type="text"
        v-model="search.text"
        id="search"
        @keydown="onSearchKeydown"
        placeholder="Name"
      />
      <button type="button" @click="resetSearch">Reset</button>
    </div>
  </div>
  <div class="card" v-if="searchResultsEmpty()">
    <span>No results found</span>
  </div>
  <div class="card" v-else-if="everybodyInterested()">
    <span>Everyone is interested</span>
  </div>
  <ul v-else>
    <li v-for="person in shownUninterested">
      <Link :href="`/people/${person.id}`">{{ person.name }}</Link>
      <button type="button" @click="onPersonInterested(person)">
        Interested
      </button>
    </li>
  </ul>
</template>

<script setup lang="ts">
import { reactive, computed } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import Person from '../../Types/Person'
import Interest from '../../Types/Interest'
import axios from 'axios'

const props = defineProps<{
  uninterested: Person[]
  interest: Interest
}>()

const shownUninterested = computed(() =>
  search.text.length > 0 ? search.results : props.uninterested
)

function everybodyInterested() {
  return props.uninterested.length === 0
}

interface Search {
  text: string
  results: Person[]
  timeout: number
}

let search: Search = reactive({
  text: '',
  results: [],
  timeout: 0,
})

function resetSearch() {
  search.text = ''
  search.results = []
  search.timeout = 0
}

function onSearchKeydown() {
  clearTimeout(search.timeout)

  search.timeout = window.setTimeout(searchUninterested, 500)
}

async function searchUninterested() {
  axios
    .get(`/search/uninterested/${props.interest.id}?name=${search.text}`)
    .then((response) => {
      search.results = response.data.uninterested
    })
}

function searchResultsEmpty() {
  return search.text.length > 0 && search.results.length === 0
}

const emit = defineEmits<{
  (e: 'personInterested', person: Person): void
}>()

function onPersonInterested(person: Person) {
  emit('personInterested', person)

  removeSearchResult(person)
}

function removeSearchResult(person: Person) {
  const index = search.results.findIndex((result) => result.id === person.id)
  search.results.splice(index, 1)
}
</script>

<style scoped>
#search-row {
  display: flex;
  gap: var(--size-1);
  align-items: center;
  margin-bottom: var(--size-1);
  padding: var(--size-2);
}

ul {
  margin-top: 0;
  margin-bottom: 0;
  padding-left: 0;
  background-color: var(--cardBg);
  border-radius: var(--rounded-sm);
}

li {
  list-style-type: none;
  padding: var(--size-2);
  border-bottom: 1px solid var(--textColor);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

li:last-of-type {
  border-bottom: none;
}
</style>

<template>
  <Head title="Interests" />
  <Authenticated>
    <div class="center-container-sm">
      <h2 class="text-xl">Interests</h2>
      <div class="menu-row">
        <div class="menu-row-left">
          <label for="search">Search</label>
          <input
            type="text"
            v-model="search.text"
            id="search"
            @keydown="onSearchKeydown"
            placeholder="Search Interests"
          />
          <button type="button" @click="resetSearch">Reset</button>
        </div>
        <div class="menu-row-right">
          <Link href="/interests/create">Add Interest</Link>
        </div>
      </div>
      <ul>
        <li v-for="interest in displayedInterests" :key="interest.id">
          <p>
            <Link :href="`/interests/${interest.id}`">{{ interest.name }}</Link>
          </p>
        </li>
      </ul>
    </div>
  </Authenticated>
</template>

<script setup lang="ts">
import Authenticated from '../../Layouts/Authenticated.vue'
import { Link, Head } from '@inertiajs/inertia-vue3'
import { reactive, computed } from 'vue'
import axios from 'axios'
import Interest from '../../Types/Interest'

const displayedInterests = computed(() =>
  search.results.length > 0 ? search.results : props.interests
)

interface Search {
  text: string
  results: Interest[]
  timeout: number
}

let search: Search = reactive({
  text: '',
  results: [],
  timeout: 0,
})

function onSearchKeydown() {
  clearTimeout(search.timeout)

  search.timeout = window.setTimeout(getInterests, 500)
}

async function getInterests() {
  await axios.get(`/api/interests?name=${search.text}`).then((response) => {
    search.results = response.data.interests
  })
}

function resetSearch() {
  search.text = ''
  search.results = []
  search.timeout = 0
}

const props = defineProps<{
  interests: Interest[]
}>()
</script>

<style scoped>
.menu-row {
  display: flex;
  justify-content: space-between;
  margin: var(--size-4) 0 var(--size-2) 0;
}

.menu-row-left {
  display: flex;
  align-items: center;
  gap: var(--size-2);
}

.menu-row-right {
  display: flex;
  align-items: flex-end;
}

#search {
  width: 250px;
}

ul {
  margin-top: 0;
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

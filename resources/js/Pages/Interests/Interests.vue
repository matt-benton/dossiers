<template>
  <Head title="Interests" />
  <Authenticated>
    <div class="center-container-sm">
      <h2 class="text-xl">Interests</h2>
      <SearchMenuRow
        :search-text="search.text"
        :link="{ text: 'Add Interest', url: '/interests/create' }"
        @update:search-text="onSearchKeydown"
        @reset-search="resetSearch"
        placeholder="Search Interests"
      />
      <ul class="resource-list">
        <li v-for="interest in displayedInterests" :key="interest.id">
          <Link :href="`/interests/${interest.id}`">{{ interest.name }}</Link>
        </li>
      </ul>
    </div>
  </Authenticated>
</template>

<script setup lang="ts">
import Authenticated from '../../Layouts/Authenticated.vue'
import { reactive, computed } from 'vue'
import axios from 'axios'
import Interest from '../../Types/Interest'
import SearchMenuRow from '../../Components/SearchMenuRow.vue';

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
  await axios.get(`/search/interests?name=${search.text}`).then((response) => {
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

li svg {
  margin-right: var(--size-2);
  height: var(--text-sm);
  width: var(--text-sm);
}
</style>

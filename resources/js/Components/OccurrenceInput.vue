<template>
  <form @submit.prevent="submitForm">
    <label for="occurrence_text">What's happening?</label>
    <textarea
      id="occurrence_text"
      v-model="form.description"
      @keydown="onKeydown"
    ></textarea>
    <div
      class="flex"
      :class="{
        'justify-between': form.errors.description,
        'justify-end': !form.errors.description,
      }"
    >
      <span v-show="form.errors.description" class="text-danger">
        {{ form.errors.description }}
      </span>
      <button>Save</button>
    </div>
    <ul v-show="searchResults.length > 0">
      <li v-for="person in search.results" :key="person.id">
        {{ person.name }}
      </li>
    </ul>
  </form>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3'

export default {
  setup() {
    const form = useForm({
      description: null,
    })

    return { form }
  },
  data() {
    return {
      searchResults: [],
      search: {
        text: '',
        results: [],
      },
    }
  },
  methods: {
    submitForm() {
      this.form.post('/occurrences')
    },
    onKeydown() {
      let url =
        this.search.text.length > 0
          ? `/api/people?search=${this.search.text}`
          : '/api/people'

      axios.get(url).then((response) => {
        this.search.results = response.data.people
      })
    },
  },
}
</script>

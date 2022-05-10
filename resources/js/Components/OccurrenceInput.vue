<template>
  <form @submit.prevent="submitForm">
    <label for="occurrence_text">What's happening?</label>
    <textarea id="occurrence_text" v-model="form.description"></textarea>
    <button>Save</button>
  </form>
  <ul>
    <li v-for="person in results" :key="person.id">{{ person.name }}</li>
  </ul>
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
  methods: {
    submitForm() {
      this.form.post('/occurrences')
    },
  },
}
</script>

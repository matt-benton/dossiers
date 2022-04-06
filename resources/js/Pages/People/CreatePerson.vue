<template>
  <Authenticated>
    <h1>create person</h1>
    <form @submit.prevent="submitForm">
      <label for="name">Name</label>
      <input type="text" id="name" v-model="form.name" />
      <label for="relationship">Relationship</label>
      <input type="text" id="relationship" v-model="form.relationship" />
      <label for="birthmonth">Birth Month</label>
      <select v-model="form.birthmonth" id="birthmonth">
        <option v-for="n in 12">{{ n }}</option>
      </select>
      <label for="birthday">Birth Day</label>
      <select v-model="form.birthday" id="birthday">
        <option v-for="n in 31">{{ n }}</option>
      </select>
      <button type="submit">Add Person</button>
    </form>
  </Authenticated>
</template>

<script>
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import Authenticated from '../../Layouts/Authenticated.vue'
export default {
  setup() {
    const form = reactive({
      name: null,
      relationship: null,
      birthmonth: null,
      birthday: null,
    })

    return { form }
  },
  methods: {
    submitForm() {
      Inertia.post('/people', this.form)
    },
  },
  components: {
    Authenticated,
  },
}
</script>

<template>
  <Head title="Update Person" />
  <Authenticated>
    <Breadcrumb :links="breadCrumbLinks" />
    <div class="center-container-xs">
      <div class="card">
        <form @submit.prevent="submitForm">
          <div class="form-group">
            <label for="name">Name</label>
            <input
              type="text"
              :class="{ 'border-danger': form.errors.name }"
              id="name"
              v-model="form.name"
            />
            <span v-if="form.errors.name" class="text-danger">{{
              form.errors.name
            }}</span>
          </div>
          <div class="form-group">
            <label for="relationship">Relationship</label>
            <input
              type="text"
              id="relationship"
              v-model="form.relationship"
              placeholder="e.g. Mother, Best Friend, Manager"
              :class="{ 'border-danger': form.errors.relationship }"
            />
            <span v-if="form.errors.relationship" class="text-danger">{{
              form.errors.relationship
            }}</span>
          </div>
          <div class="col-2">
            <div class="form-group">
              <label for="birthmonth">Birth Month</label>
              <select
                v-model="form.birthmonth"
                id="birthmonth"
                :class="{ 'border-danger': form.errors.birthmonth }"
              >
                <option></option>
                <option v-for="(month, index) in months" :value="index + 1">
                  {{ month }}
                </option>
              </select>
              <span v-if="form.errors.birthmonth" class="text-danger">{{
                form.errors.birthmonth
              }}</span>
            </div>
            <div class="form-group">
              <label for="birthday">Birth Day</label>
              <select
                v-model="form.birthday"
                id="birthday"
                :class="{ 'border-danger': form.errors.birthday }"
              >
                <option></option>
                <option v-for="n in 31">{{ n }}</option>
              </select>
              <span v-if="form.errors.birthday" class="text-danger">{{
                form.errors.birthday
              }}</span>
            </div>
          </div>
          <div class="flex justify-end">
            <button class="btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </Authenticated>
</template>

<script>
import { Head, useForm } from '@inertiajs/inertia-vue3'
import Authenticated from '../../Layouts/Authenticated.vue'
import Breadcrumb from '../../Components/Breadcrumb.vue'

export default {
  setup(props) {
    const form = useForm({
      name: props.person.name,
      relationship: props.person.relationship,
      birthmonth: props.person.birthmonth,
      birthday: props.person.birthday,
    })

    return { form }
  },
  data() {
    return {
      breadCrumbLinks: [
        {
          url: '/people',
          title: 'People',
        },
        {
          url: `/people/${this.person.id}`,
          title: this.person.name,
        },
        {
          title: 'Edit',
        },
      ],
      months: [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December',
      ],
    }
  },
  methods: {
    submitForm() {
      this.form.put(`/people/${this.person.id}`)
    },
  },
  components: {
    Authenticated,
    Head,
    Breadcrumb,
  },
  props: {
    person: Object,
  },
}
</script>

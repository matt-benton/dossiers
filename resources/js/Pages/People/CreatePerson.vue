<template>
  <Head title="Add Person" />
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
              ref="nameInput"
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
              autocomplete="off"
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
                <option v-for="n in 31">{{ n }}</option>
              </select>
              <span v-if="form.errors.birthday" class="text-danger">{{
                form.errors.birthday
              }}</span>
            </div>
          </div>
          <div class="flex justify-end">
            <button class="btn-primary">Add Person</button>
          </div>
        </form>
      </div>
    </div>
  </Authenticated>
</template>

<script setup lang="ts">
import { reactive, onMounted, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Authenticated from '../../Layouts/Authenticated.vue'
import Breadcrumb from '../../Components/Breadcrumb.vue'

// autofocus name input
const nameInput = ref<HTMLInputElement | null>(null)
onMounted(() => nameInput.value?.focus())

let breadCrumbLinks = reactive([
  {
    url: '/people',
    title: 'People',
  },
  {
    title: 'Add Person',
  },
])

const months = reactive([
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
])

let form = useForm({
  name: null,
  relationship: null,
  birthmonth: null,
  birthday: null,
})

const submitForm = function () {
  form.post('/people')
}
</script>

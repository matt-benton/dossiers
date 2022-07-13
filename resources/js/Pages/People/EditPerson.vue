<template>
  <Head title="Update Person" />
  <Authenticated>
    <Breadcrumb :links="breadCrumbLinks" />
    <div class="center-container-xs">
      <div class="card">
        <form @submit.prevent="submitEditForm">
          <div class="form-group">
            <label for="name">Name</label>
            <input
              type="text"
              :class="{ 'border-danger': editForm.errors.name }"
              id="name"
              v-model="editForm.name"
            />
            <span v-if="editForm.errors.name" class="text-danger">{{
              editForm.errors.name
            }}</span>
          </div>
          <div class="form-group">
            <label for="relationship">Relationship</label>
            <input
              type="text"
              id="relationship"
              v-model="editForm.relationship"
              placeholder="e.g. Mother, Best Friend, Manager"
              :class="{ 'border-danger': editForm.errors.relationship }"
            />
            <span v-if="editForm.errors.relationship" class="text-danger">{{
              editForm.errors.relationship
            }}</span>
          </div>
          <div class="col-2">
            <div class="form-group">
              <label for="birthmonth">Birth Month</label>
              <select
                v-model="editForm.birthmonth"
                id="birthmonth"
                :class="{ 'border-danger': editForm.errors.birthmonth }"
              >
                <option></option>
                <option v-for="(month, index) in months" :value="index + 1">
                  {{ month }}
                </option>
              </select>
              <span v-if="editForm.errors.birthmonth" class="text-danger">{{
                editForm.errors.birthmonth
              }}</span>
            </div>
            <div class="form-group">
              <label for="birthday">Birth Day</label>
              <select
                v-model="editForm.birthday"
                id="birthday"
                :class="{ 'border-danger': editForm.errors.birthday }"
              >
                <option></option>
                <option v-for="n in 31">{{ n }}</option>
              </select>
              <span v-if="editForm.errors.birthday" class="text-danger">{{
                editForm.errors.birthday
              }}</span>
            </div>
          </div>
          <div class="btn-row flex justify-end">
            <button class="btn-primary">Save</button>
            <button type="button" @click="deleteModalVisible = true">
              Delete
            </button>
          </div>
        </form>
      </div>
    </div>
    <Modal
      :visible="deleteModalVisible"
      v-on:modal-closed="deleteModalVisible = false"
    >
      <p>Remove {{ person.name }}?</p>
      <div class="btn-row flex justify-end">
        <button type="button" class="btn-primary" @click="confirmDelete">
          Delete
        </button>
        <button type="button" @click="deleteModalVisible = false">
          Cancel
        </button>
      </div>
    </Modal>
  </Authenticated>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import Authenticated from '../../Layouts/Authenticated.vue'
import Breadcrumb from '../../Components/Breadcrumb.vue'
import Modal from '../../Components/Modal.vue'
import Person from '../../Types/Person'

let props = defineProps<{
  person: Person
}>()

const editForm = useForm({
  name: props.person.name,
  relationship: props.person.relationship,
  birthmonth: props.person.birthmonth,
  birthday: props.person.birthday,
})

const deleteForm = useForm({})

let deleteModalVisible = ref(false)

let breadCrumbLinks = reactive([
  {
    url: '/people',
    title: 'People',
  },
  {
    url: `/people/${props.person.id}`,
    title: props.person.name,
  },
  {
    title: 'Edit',
  },
])

let months = reactive([
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

function submitEditForm() {
  editForm.put(`/people/${props.person.id}`)
}

function confirmDelete() {
  deleteForm.delete(`/people/${props.person.id}`)
  deleteModalVisible.value = false
}
</script>

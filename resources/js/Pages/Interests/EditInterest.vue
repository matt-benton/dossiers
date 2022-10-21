<template>
  <Head title="Edit Interest" />
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
              autocomplete="off"
            />
            <span v-if="editForm.errors.name" class="text-danger">{{
              editForm.errors.name
            }}</span>
          </div>
          <div class="btn-row flex justify-end">
            <button class="btn-primary">Save</button>
            <button type="button" @click="deleteModalVisible = true">
              Delete
            </button>
          </div>
        </form>
      </div>
      <br />
      <h5>Interested in {{ interest.name }}</h5>
      <div class="card" v-if="nobodyInterested()">
        <span>No one is interested</span>
      </div>
      <ul v-else>
        <li v-for="person in interest.people">
          <Link :href="`/people/${person.id}`">{{ person.name }}</Link>
          <button type="button" @click="removeUninterested(person)">
            Remove
          </button>
        </li>
      </ul>
      <br />
      <h5>Not Interested in {{ interest.name }}</h5>
      <div class="card" v-if="everybodyInterested()">
        <span>Everyone is interested</span>
      </div>
      <ul v-else>
        <li v-for="person in uninterested">
          <Link :href="`/people/${person.id}`">{{ person.name }}</Link>
          <button type="button" @click="addPerson(person)">Interested</button>
        </li>
      </ul>
    </div>
    <Modal
      :visible="deleteModalVisible"
      v-on:modal-closed="deleteModalVisible = false"
    >
      <p>Remove {{ interest.name }}?</p>
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
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import Authenticated from '../../Layouts/Authenticated.vue'
import Breadcrumb from '../../Components/Breadcrumb.vue'
import Modal from '../../Components/Modal.vue'
import Interest from '../../Types/Interest'
import Person from '../../Types/Person'

let props = defineProps<{
  interest: Interest
  uninterested: Person[]
}>()

const editForm = useForm({
  name: props.interest.name,
})

const deleteForm = useForm({})

let deleteModalVisible = ref(false)

let breadCrumbLinks = reactive([
  {
    url: '/interests',
    title: 'Interests',
  },
  {
    url: `/interests/${props.interest.id}`,
    title: props.interest.name,
  },
  {
    title: 'Edit',
  },
])

function submitEditForm() {
  editForm.put(`/interests/${props.interest.id}`)
}

function confirmDelete() {
  deleteForm.delete(`/interests/${props.interest.id}`)
  deleteModalVisible.value = false
}

const addPersonForm = useForm({
  personId: 0,
})

function addPerson(person: Person) {
  addPersonForm.personId = person.id

  addPersonForm.post(`/interests/${props.interest.id}/add_person`)
}

const removePersonForm = useForm({
  personId: 0,
})

function removeUninterested(person: Person) {
  removePersonForm.personId = person.id

  removePersonForm.post(`/interests/${props.interest.id}/remove_person`)
}

function nobodyInterested() {
  return props.interest.people && props.interest.people.length === 0
}

function everybodyInterested() {
  return props.uninterested.length === 0
}
</script>

<style scoped>
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

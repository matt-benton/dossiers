<template>
  <Head title="Update Person" />
  <Authenticated>
    <Breadcrumb :links="breadCrumbLinks" />
    <form @submit.prevent="submitEditForm">
      <div class="grid-layout">
        <div class="card">
          <h3 class="card-heading">Profile</h3>
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
              autocomplete="off"
            />
            <span v-if="editForm.errors.relationship" class="text-danger">{{
              editForm.errors.relationship
            }}</span>
          </div>
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
        <div class="card">
          <h3 class="card-heading">Interests</h3>
          <div v-for="int in interests" class="checkbox-group">
            <input
              type="checkbox"
              :value="int.id"
              name="interests"
              :id="`interest-${int.id}`"
              v-model="editForm.interest_ids"
            />
            <label :for="`interest-${int.id}`">{{ int.name }}</label>
          </div>
        </div>
        <div class="card">
          <h3 class="card-heading">Groups</h3>
          <div v-for="group in groups" class="checkbox-group">
            <input
              type="checkbox"
              :value="group.id"
              name="groups"
              :id="`group-${group.id}`"
              v-model="editForm.group_ids"
            />
            <label :for="`group-${group.id}`">{{ group.name }}</label>
          </div>
        </div>
        <div class="btn-row flex justify-end">
          <button class="btn-primary">Save</button>
          <button type="button" @click="deleteModalVisible = true">
            Delete
          </button>
        </div>
      </div>
    </form>
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
import { useForm } from '@inertiajs/vue3'
import Authenticated from '../../Layouts/Authenticated.vue'
import Breadcrumb from '../../Components/Breadcrumb.vue'
import Modal from '../../Components/Modal.vue'
import Person from '../../Types/Person'
import Interest from '../../Types/Interest'
import Group from '../../Types/Group'

let props = defineProps<{
  person: Person
  interests: Interest[]
  groups: Group[]
}>()

const editForm = useForm({
  name: props.person.name,
  relationship: props.person.relationship,
  birthmonth: props.person.birthmonth,
  birthday: props.person.birthday,
  interest_ids: props.person.interests?.map((int) => int.id),
  group_ids: props.person.groups?.map((group) => group.id),
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

<style scoped>
.grid-layout {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: var(--size-6);
}

.card-heading {
  font-size: var(--text-lg);
  margin-bottom: var(--size-3);
}

.btn-row {
  grid-column: span 3;
}

@media (max-width: 640px) {
  .grid-layout {
    display: flex;
    flex-direction: column;
    gap: var(--size-3);
  }
}
</style>

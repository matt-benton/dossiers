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
import { Head, useForm } from '@inertiajs/inertia-vue3'
import Authenticated from '../../Layouts/Authenticated.vue'
import Breadcrumb from '../../Components/Breadcrumb.vue'
import Modal from '../../Components/Modal.vue'
import Interest from '../../Types/Interest'

let props = defineProps<{
  interest: Interest
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
</script>

<template>
  <Head title="Edit Group" />
  <Authenticated>
    <Breadcrumb :links="breadcrumb" />
    <div class="center-container-xs">
      <div class="card">
        <form @submit.prevent="editForm.put(`/groups/${group.id}`)">
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
    </div>
  </Authenticated>
  <Modal
    :visible="deleteModalVisible"
    v-on:modal-closed="deleteModalVisible = false"
  >
    <p>Remove {{ group.name }}?</p>
    <div class="btn-row flex justify-end">
      <button type="button" class="btn-primary" @click="confirmDelete">
        Delete
      </button>
      <button type="button" @click="deleteModalVisible = false">Cancel</button>
    </div>
  </Modal>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import Breadcrumb from '../../Components/Breadcrumb.vue'
import Authenticated from '../../Layouts/Authenticated.vue'
import Group from '../../Types/Group'
import Modal from '../../Components/Modal.vue'

const props = defineProps<{
  group: Group
}>()

const editForm = useForm({
  name: props.group.name,
})

const deleteForm = useForm({})

const deleteModalVisible = ref(false)

function confirmDelete() {
  deleteForm.delete(`/groups/${props.group.id}`)
  deleteModalVisible.value = false
}

const breadcrumb = reactive([
  {
    url: '/groups',
    title: 'Groups',
  },
  {
    url: `/groups/${props.group.id}`,
    title: props.group.name,
  },
  {
    title: 'Edit',
  },
])
</script>

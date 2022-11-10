<template>
  <Head :title="group.name" />
  <Authenticated>
    <div class="row">
      <Breadcrumb :links="breadcrumb" />
      <h2>{{ group.name }}</h2>
      <Link :href="`/groups/${group.id}/edit`" class="btn btn-icon-text"
        ><Pencil /> Update</Link
      >
    </div>
    <div>
      <div v-if="threads && threads.length > 0">
        <h3 class="text-lg">Events</h3>
        <ThreadList
          v-for="thread in threads"
          :thread="thread"
          :key="thread.id"
          :removable-developments="true"
          @development-removed="selectDevelopmentForDelete"
        />
      </div>
      <div v-else>
        <h3 class="text-lg">Events</h3>
        <div class="card">No events have been added yet.</div>
      </div>
    </div>
  </Authenticated>
  <Modal :visible="modal.visible" v-on:modal-closed="resetModal">
    <p>Delete this event?</p>
    <p class="alert">
      {{
        modal.developmentForDelete ? modal.developmentForDelete.description : ''
      }}
    </p>
    <div class="btn-row flex justify-end">
      <button
        type="button"
        class="btn-primary"
        @click="confirmDelete(modal.developmentForDelete)"
      >
        Delete
      </button>
      <button type="button" @click="resetModal">Cancel</button>
    </div>
  </Modal>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/inertia-vue3'
import { reactive } from 'vue'
import Breadcrumb from '../../Components/Breadcrumb.vue'
import Authenticated from '../../Layouts/Authenticated.vue'
import Group from '../../Types/Group'
import Pencil from '../../Components/Icons/Pencil.vue'
import ThreadList from '../../Components/ThreadList.vue'
import Development from '../../Types/Development'
import Modal from '../../Components/Modal.vue'
import Thread from '../../Types/Thread'

const props = defineProps<{
  group: Group
  threads: Thread[]
}>()

interface Modal {
  visible: boolean
  developmentForDelete: Development | null
}

let modal: Modal = reactive({
  visible: false,
  developmentForDelete: null,
})

const selectDevelopmentForDelete = function (development: Development) {
  modal.visible = true
  modal.developmentForDelete = development
}

const resetModal = function () {
  modal.visible = false
}

let deleteDevelopmentForm = useForm({})

const confirmDelete = function (development: Development | null) {
  if (development) {
    deleteDevelopmentForm.delete(`/developments/${development.id}`, {
      preserveScroll: true,
    })
  }

  resetModal()
}

const breadcrumb = reactive([
  {
    url: '/groups',
    title: 'Groups',
  },
  {
    title: props.group.name,
  },
])
</script>

<template>
  <Head :title="group.name" />
  <Authenticated>
    <div class="row">
      <Breadcrumb :links="breadcrumb" />
    </div>
    <div class="row">
      <div class="show-page-header">
        <h2>{{ group.name }}</h2>
        <Link :href="`/groups/${group.id}/edit`" class="icon-text-link"
          ><Pencil /> Edit</Link
        >
      </div>
    </div>
    <div class="layout-2-col">
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
      <div>
        <h5>Members</h5>
        <div class="card">
          <span v-if="noMembers()">No one is in this group.</span>
          <ul v-else>
            <li v-for="member in group.members">
              <Link :href="`/people/${member.id}`">{{ member.name }}</Link>
              <small>{{ member.pivot?.role }}</small>
            </li>
          </ul>
        </div>
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
import { useForm } from '@inertiajs/vue3'
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

function noMembers() {
  return props.group.members.length === 0
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

<style scoped>
.layout-2-col {
  display: grid;
  grid-template-columns: 75% 25%;
  gap: var(--size-5);
}

ul {
  padding-left: 0;
  margin: 0;
}

li {
  list-style-type: none;
  margin-bottom: var(--size-3);
  display: flex;
  flex-direction: column;
}
</style>

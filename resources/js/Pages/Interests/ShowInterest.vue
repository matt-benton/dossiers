<template>
  <Head :title="interest.name" />
  <Authenticated>
    <div class="row">
      <Breadcrumb :links="breadcrumb" />
    </div>
    <div class="show-page-header">
      <h2>
        {{ interest.name }}
        <small>
          <Link :href="`/interests/${interest.id}/edit`" class="icon-text-link"
            ><Pencil /> Edit</Link
          >
        </small>
      </h2>
    </div>
    <div class="layout-2-col">
      <div id="threads-container">
          <div
            v-if="interest.threads && interest.threads.length > 0"
            id="threads-list"
          >
            <h3 class="text-lg">Events</h3>
            <ThreadList
              v-for="thread in interest.threads"
              :thread="thread"
              :removable-developments="true"
              @development-removed="selectDevelopmentForDelete"
              :key="thread.id"
            />
          </div>
          <div v-else>
            <h3 class="text-lg">Events</h3>
            <div class="card">No events have been added yet.</div>
          </div>
      </div>
      <div>
        <h5>Interested</h5>
        <div class="card">
          <span v-if="nobodyInterested()">No one is interested</span>
          <ul v-else>
            <li v-for="person in interest.people">
              <Link :href="`/people/${person.id}`">{{ person.name }}</Link>
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
import { reactive, defineProps } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Authenticated from '../../Layouts/Authenticated.vue'
import Breadcrumb from '../../Components/Breadcrumb.vue'
import Pencil from '../../Components/Icons/Pencil.vue'
import Modal from '../../Components/Modal.vue'
import Interest from '../../Types/Interest'
import Development from '../../Types/Development'
import ThreadList from '../../Components/ThreadList.vue'

let props = defineProps<{
  interest: Interest
}>()

let breadcrumb = reactive([
  {
    url: '/interests',
    title: 'Interests',
  },
  {
    title: props.interest.name,
  },
])

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

function nobodyInterested() {
  return props.interest.people && props.interest.people.length === 0
}
</script>

<style scoped>
.layout-2-col {
  display: grid;
  grid-template-columns: 75% 25%;
  gap: var(--size-5);
}

#threads-list .card {
  margin-bottom: var(--size-5);
}

ul {
  padding-left: 0;
  margin: 0;
}

li {
  list-style-type: none;
  margin-bottom: var(--size-1);
}

@media (max-width: 640px) {
    .layout-2-col {
        grid-template-columns: 100%;
    }

    #threads-container {
        order: 2;
    }
}
</style>

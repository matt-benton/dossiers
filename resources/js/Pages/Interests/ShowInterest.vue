<template>
  <Head :title="interest.name" />
  <Authenticated>
    <div class="row">
      <Breadcrumb :links="breadcrumb" />
      <h2>{{ interest.name }}</h2>
      <Link :href="`/interests/${interest.id}/edit`" class="btn btn-icon-text"
        ><Pencil /> Update</Link
      >
    </div>
    <div
      v-if="interest.threads && interest.threads.length > 0"
      id="threads-list"
    >
      <ThreadList
        v-for="thread in interest.threads"
        :thread="thread"
        :removable-developments="true"
        @development-removed="selectDevelopmentForDelete"
      />
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
import { Link, Head, useForm } from '@inertiajs/inertia-vue3'
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
</script>

<style scoped>
#threads-list .card {
  margin-bottom: var(--size-5);
}
</style>

<template>
  <Head :title="person.name" />
  <Authenticated>
    <div class="row">
      <Breadcrumb :links="breadcrumb" />
      <h2>{{ person.name }}</h2>
      <h4>{{ person.relationship }}</h4>
      <p v-if="person.birthmonth && person.birthday">
        <Cake /> {{ person.birthmonth_text }} {{ person.birthday }}
      </p>
      <Link :href="`/people/${person.id}/edit`" class="btn btn-icon-text"
        ><Pencil /> Update</Link
      >
    </div>
    <div v-if="person.occurrences" id="occurrences-list">
      <OccurrenceCard
        v-for="occ in person.occurrences"
        :occurrence="occ"
        :closeable="true"
        v-on:close-button-clicked="selectOccurrenceForDelete(occ)"
      />
    </div>
  </Authenticated>
  <Modal :visible="modal.visible" v-on:modal-closed="resetModal">
    <p>Delete this event?</p>
    <p class="alert">
      {{
        modal.occurrenceForDelete ? modal.occurrenceForDelete.description : ''
      }}
    </p>
    <div class="btn-row flex justify-end">
      <button
        type="button"
        class="btn-primary"
        @click="confirmDelete(modal.occurrenceForDelete.id)"
      >
        Delete
      </button>
      <button type="button" @click="resetModal">Cancel</button>
    </div>
  </Modal>
</template>

<script setup>
import { reactive, defineProps } from 'vue'
import { Link, Head, useForm } from '@inertiajs/inertia-vue3'
import Authenticated from '../../Layouts/Authenticated.vue'
import Breadcrumb from '../../Components/Breadcrumb.vue'
import Cake from '../../Components/Icons/Cake.vue'
import Pencil from '../../Components/Icons/Pencil.vue'
import { useFormatDate } from '../../Composables/format'
import Close from '../../Components/Icons/Close.vue'
import Modal from '../../Components/Modal.vue'
import OccurrenceCard from '../../Components/OccurrenceCard.vue'

let breadcrumb = reactive([
  {
    url: '/people',
    title: 'People',
  },
  {
    title: props.person.name,
  },
])

let props = defineProps({
  person: {
    required: true,
    type: Object,
  },
})

let modal = reactive({
  visible: false,
  occurrenceForDelete: null,
})

const selectOccurrenceForDelete = function (occurrence) {
  modal.visible = true
  modal.occurrenceForDelete = occurrence
}

const resetModal = function () {
  modal.visible = false
}

let deleteOccurrenceForm = useForm()

const confirmDelete = function (occId) {
  deleteOccurrenceForm.delete(`/occurrences/${occId}`)
  resetModal()
}
</script>

<style scoped>
#occurrences-list .card {
  margin-bottom: var(--size-5);
}
</style>

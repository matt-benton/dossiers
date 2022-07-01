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
      <div class="card" v-for="occ in person.occurrences">
        <p>{{ occ.description }}</p>
        <small>{{ useFormatDate(new Date(occ.created_at)) }}</small>
        <button
          type="button"
          class="close-button flex no-border justify-center"
          @click="selectOccurrenceForDelete(occ)"
        >
          <Close />
        </button>
      </div>
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

.card {
  position: relative;
}

.close-button {
  position: absolute;
  top: var(--size-4);
  right: var(--size-4);
}

.close-button svg {
  opacity: 0.5;
  height: var(--size-4);
  width: var(--size-4);
  margin-top: var(--size-1);
  margin-bottom: var(--size-1);
}

.close-button svg:hover {
  cursor: pointer;
}
</style>

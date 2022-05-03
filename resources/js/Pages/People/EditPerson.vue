<template>
  <Head title="Update Person" />
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
          <div class="form-group">
            <label for="relationship">Relationship</label>
            <input
              type="text"
              id="relationship"
              v-model="editForm.relationship"
              placeholder="e.g. Mother, Best Friend, Manager"
              :class="{ 'border-danger': editForm.errors.relationship }"
            />
            <span v-if="editForm.errors.relationship" class="text-danger">{{
              editForm.errors.relationship
            }}</span>
          </div>
          <div class="col-2">
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

<script>
import { Head, useForm } from '@inertiajs/inertia-vue3'
import Authenticated from '../../Layouts/Authenticated.vue'
import Breadcrumb from '../../Components/Breadcrumb.vue'
import Modal from '../../Components/Modal.vue'

export default {
  setup(props) {
    const editForm = useForm({
      name: props.person.name,
      relationship: props.person.relationship,
      birthmonth: props.person.birthmonth,
      birthday: props.person.birthday,
    })

    const deleteForm = useForm()

    return { editForm, deleteForm }
  },
  data() {
    return {
      deleteModalVisible: false,
      breadCrumbLinks: [
        {
          url: '/people',
          title: 'People',
        },
        {
          url: `/people/${this.person.id}`,
          title: this.person.name,
        },
        {
          title: 'Edit',
        },
      ],
      months: [
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
      ],
    }
  },
  methods: {
    submitEditForm() {
      this.editForm.put(`/people/${this.person.id}`)
    },
    confirmDelete() {
      this.deleteForm.delete(`/people/${this.person.id}`)
      this.deleteModalVisible = false
    },
  },
  components: {
    Authenticated,
    Head,
    Breadcrumb,
    Modal,
  },
  props: {
    person: Object,
  },
}
</script>

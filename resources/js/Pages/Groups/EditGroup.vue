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
      <br />
      <h5>In Group</h5>
      <div class="card" v-if="nobodyInGroup">
        <span>No one is in this group yet</span>
      </div>
      <ul v-else>
        <li v-for="person in group.people" :key="person.id">
          <div>
            <Link :href="`/people/${person.id}`">{{ person.name }}</Link>
            <br />
            <small>{{ person.pivot?.role }}</small>
          </div>
          <button type="button" @click="showRemovePersonModal(person)">
            Remove
          </button>
        </li>
      </ul>
      <br />
      <h5>Not In Group</h5>
      <UngroupedList
        :group="group"
        :ungrouped="ungrouped"
        @addPerson="showRoleModal"
      />
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
  <Modal :visible="roleModal.visible" @modal-closed="roleModal.visible = false">
    <form @submit.prevent="confirmAddPerson(roleModal.selectedPerson)">
      <p>
        Give {{ roleModal.selectedPerson?.name }} a role in {{ group.name }}?
        (optional)
      </p>
      <label for="role">Role</label>
      <div class="btn-row">
        <input type="text" name="role" id="role" v-model="addPersonForm.role" />
        <span v-if="addPersonForm.errors.role" class="text-danger">{{
          addPersonForm.errors.role
        }}</span>
        <button class="btn-primary">Add to Group</button>
        <button type="button" @click="roleModal.visible = false">Cancel</button>
      </div>
    </form>
  </Modal>
  <Modal
    :visible="removePersonModal.visible"
    v-on:modal-closed="removePersonModal.visible = false"
  >
    <p>
      Remove {{ removePersonModal.selectedPerson?.name }} from {{ group.name }}?
    </p>
    <div class="btn-row flex justify-end">
      <button
        type="button"
        class="btn-primary"
        @click="confirmRemovePerson(removePersonModal.selectedPerson)"
      >
        Remove
      </button>
      <button type="button" @click="removePersonModal.visible = false">
        Cancel
      </button>
    </div>
  </Modal>
</template>

<script setup lang="ts">
import { reactive, ref, computed } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import Breadcrumb from '../../Components/Breadcrumb.vue'
import Authenticated from '../../Layouts/Authenticated.vue'
import Group from '../../Types/Group'
import Person from '../../Types/Person'
import Modal from '../../Components/Modal.vue'
import UngroupedList from './UngroupedList.vue'

const props = defineProps<{
  group: Group
  ungrouped: Person[]
}>()

const editForm = useForm({
  name: props.group.name,
})

const deleteForm = useForm({})

const deleteModalVisible = ref(false)

interface RoleModal {
  visible: boolean
  selectedPerson: Person | null
}

const roleModal: RoleModal = reactive({
  visible: false,
  selectedPerson: null,
})

function showRoleModal(person: Person) {
  roleModal.visible = true
  roleModal.selectedPerson = person
}

const nobodyInGroup = computed(() => props.group.people.length === 0)

function confirmDelete() {
  deleteForm.delete(`/groups/${props.group.id}`)
  deleteModalVisible.value = false
}

const addPersonForm = useForm({
  personId: 0,
  role: null,
})

function confirmAddPerson(person: Person | null) {
  if (person) {
    addPersonForm.personId = person.id

    addPersonForm.post(`/groups/${props.group.id}/add_person`, {
      preserveScroll: true,
      onSuccess: () => {
        roleModal.visible = false
        addPersonForm.reset()
      },
    })
  }
}

const removePersonForm = useForm({
  personId: 0,
})

interface RemovePersonModal {
  visible: boolean
  selectedPerson: Person | null
}

const removePersonModal: RemovePersonModal = reactive({
  visible: false,
  selectedPerson: null,
})

function showRemovePersonModal(person: Person) {
  removePersonModal.visible = true
  removePersonModal.selectedPerson = person
}

function confirmRemovePerson(person: Person | null) {
  if (person) {
    removePersonForm.personId = person.id

    removePersonForm.post(`/groups/${props.group.id}/remove_person`, {
      preserveScroll: true,
      onSuccess: () => {
        removePersonModal.visible = false
      },
    })
  }
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

<style scoped>
ul {
  margin-top: 0;
  margin-bottom: 0;
  padding-left: 0;
  background-color: var(--cardBg);
  border-radius: var(--rounded-sm);
}

li {
  list-style-type: none;
  padding: var(--size-2);
  border-bottom: 1px solid var(--textColor);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

li:last-of-type {
  border-bottom: none;
}
</style>

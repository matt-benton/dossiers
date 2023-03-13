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
      <h5>Members</h5>
      <div class="card" v-if="noMembers">
        <span>No one is in this group yet</span>
      </div>
      <ul v-else>
        <li v-for="member in group.members" :key="member.id">
          <div>
            <Link :href="`/people/${member.id}`">{{ member.name }}</Link>
            <br />
            <small>{{ member.pivot?.role }}</small>
          </div>
          <div class="btn-row">
            <button type="button" @click="showChangeRoleModal(member)">
              Change Role
            </button>
            <button type="button" @click="showRemoveMemberModal(member)">
              Remove
            </button>
          </div>
        </li>
      </ul>
      <br />
      <h5>Not In Group</h5>
      <NonMemberList
        :group="group"
        :nonMembers="nonMembers"
        @addMember="showRoleModal"
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
    <form @submit.prevent="confirmAddMember(roleModal.selectedPerson)">
      <p>
        Give {{ roleModal.selectedPerson?.name }} a role in {{ group.name }}?
        (optional)
      </p>
      <label for="role">Role</label>
      <div class="btn-row">
        <input type="text" name="role" id="role" v-model="addMemberForm.role" />
        <span v-if="addMemberForm.errors.role" class="text-danger">{{
          addMemberForm.errors.role
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
        @click="confirmRemoveMember(removePersonModal.selectedPerson)"
      >
        Remove
      </button>
      <button type="button" @click="removePersonModal.visible = false">
        Cancel
      </button>
    </div>
  </Modal>
  <Modal
    :visible="changeRoleModal.visible"
    @modal-closed="changeRoleModal.visible = false"
  >
    <form @submit.prevent="confirmChangeRole(changeRoleModal.selectedPerson)">
      <p>
        Change role for {{ changeRoleModal.selectedPerson?.name }} in
        {{ group.name }}?
      </p>
      <label for="change-role">Role</label>
      <div class="btn-row">
        <input
          type="text"
          name="change-role"
          id="change-role"
          v-model="changeRoleForm.role"
        />
        <span v-if="changeRoleForm.errors.role" class="text-danger">{{
          changeRoleForm.errors.role
        }}</span>
        <button class="btn-primary">Confirm Role Change</button>
        <button type="button" @click="changeRoleModal.visible = false">
          Cancel
        </button>
      </div>
    </form>
  </Modal>
</template>

<script setup lang="ts">
import { reactive, ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Breadcrumb from '../../Components/Breadcrumb.vue'
import Authenticated from '../../Layouts/Authenticated.vue'
import Group from '../../Types/Group'
import Person from '../../Types/Person'
import Modal from '../../Components/Modal.vue'
import NonMemberList from './NonMemberList.vue'

const props = defineProps<{
  group: Group
  nonMembers: Person[]
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

const noMembers = computed(() => props.group.members.length === 0)

function confirmDelete() {
  deleteForm.delete(`/groups/${props.group.id}`)
  deleteModalVisible.value = false
}

const addMemberForm = useForm({
  personId: 0,
  role: null,
})

function confirmAddMember(person: Person | null) {
  if (person) {
    addMemberForm.personId = person.id

    addMemberForm.post(`/groups/${props.group.id}/add_member`, {
      preserveScroll: true,
      onSuccess: () => {
        roleModal.visible = false
        addMemberForm.reset()
      },
    })
  }
}

const removeMemberForm = useForm({
  personId: 0,
})

interface RemoveMemberModal {
  visible: boolean
  selectedPerson: Person | null
}

const removePersonModal: RemoveMemberModal = reactive({
  visible: false,
  selectedPerson: null,
})

function showRemoveMemberModal(person: Person) {
  removePersonModal.visible = true
  removePersonModal.selectedPerson = person
}

function confirmRemoveMember(person: Person | null) {
  if (person) {
    removeMemberForm.personId = person.id

    removeMemberForm.post(`/groups/${props.group.id}/remove_member`, {
      preserveScroll: true,
      onSuccess: () => {
        removePersonModal.visible = false
      },
    })
  }
}

const changeRoleForm = useForm({
  personId: 0,
  role: '',
})

interface ChangeRoleModal {
  visible: boolean
  selectedPerson: Person | null
}

const changeRoleModal: ChangeRoleModal = reactive({
  visible: false,
  selectedPerson: null,
})

function showChangeRoleModal(person: Person) {
  changeRoleModal.visible = true
  changeRoleModal.selectedPerson = person
  changeRoleForm.role = person.pivot?.role || ''
}

function confirmChangeRole(person: Person | null) {
  if (person) {
    changeRoleForm.personId = person.id

    changeRoleForm.post(`/groups/${props.group.id}/update_role`, {
      preserveScroll: true,
      onSuccess: () => {
        changeRoleModal.visible = false
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

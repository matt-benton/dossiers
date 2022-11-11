<template>
  <Head :title="person.name" />
  <Authenticated>
    <div class="row">
      <Breadcrumb :links="breadcrumb" />
    </div>
    <div class="row">
      <div class="show-page-header">
        <h2>{{ person.name }}</h2>
        <Link :href="`/people/${person.id}/edit`" class="icon-text-link"
          ><Pencil /> Edit</Link
        >
      </div>
      <h3 class="text-xl">{{ person.relationship }}</h3>
      <p v-if="person.birthmonth && person.birthday">
        <Cake /> {{ person.birthmonth_text }} {{ person.birthday }}
      </p>
    </div>
    <div class="row" v-if="person.interests && person.interests.length > 0">
      <h3 class="text-lg">Interests</h3>
      <ul class="interests-list">
        <li v-for="int in person.interests">{{ int.name }}</li>
      </ul>
    </div>
    <div class="layout-2-col">
      <div class="events-col">
        <h3 class="text-lg">Events</h3>
        <DevelopmentInput
          unique-id="show-person"
          :label="`What's happening with ${person.name}?`"
          :text="`@${person.name}`"
        />
        <div v-if="shownThreads.length > 0" id="threads-list">
          <ThreadList
            v-for="thread in shownThreads"
            :key="thread.id"
            :thread="thread"
            :removable-developments="true"
            @development-removed="selectDevelopmentForDelete"
          />
        </div>
        <div v-else>
          <div class="card">
            <p>No events to show for {{ person.name }}.</p>
          </div>
        </div>
      </div>
      <div>
        <h5>Toggle</h5>
        <div class="card">
          <div class="checkbox-group">
            <input
              type="checkbox"
              id="interests-checkbox"
              v-model="toggles.interests"
              :true-value="true"
              :false-value="false"
            />
            <label for="interests-checkbox">Interests</label>
          </div>
          <div class="checkbox-group">
            <input
              type="checkbox"
              id="personal-checkbox"
              v-model="toggles.personal"
              :true-value="true"
              :false-value="false"
            />
            <label for="personal-checkbox">Personal</label>
          </div>
          <div class="checkbox-group">
            <input
              type="checkbox"
              id="groups-checkbox"
              v-model="toggles.groups"
              :true-value="true"
              :false-value="false"
            />
            <label for="groups-checkbox">Groups</label>
          </div>
          <div class="checkbox-group">
            <input
              type="checkbox"
              id="grouped-checkbox"
              v-model="toggles.groupMembers"
              :true-value="true"
              :false-value="false"
            />
            <label for="grouped-checkbox">Group Members</label>
          </div>
        </div>
        <h5>Groups</h5>
        <div class="card" v-if="person.groups.length === 0">
          {{ person.name }} is not in a group.
        </div>
        <div class="card" v-else>
          <ul class="groups-list">
            <li v-for="group in person.groups" :key="group.id">
              <Link :href="`/groups/${group.id}`">{{ group.name }}</Link>
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
import { reactive, defineProps, computed } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import Authenticated from '../../Layouts/Authenticated.vue'
import Breadcrumb from '../../Components/Breadcrumb.vue'
import Cake from '../../Components/Icons/Cake.vue'
import Pencil from '../../Components/Icons/Pencil.vue'
import Modal from '../../Components/Modal.vue'
import Person from '../../Types/Person'
import Development from '../../Types/Development'
import Thread from '../../Types/Thread'
import ThreadList from '../../Components/ThreadList.vue'
import DevelopmentInput from '../../Components/DevelopmentInput.vue'

let props = defineProps<{
  person: Person
  threads: Thread[]
}>()

let breadcrumb = reactive([
  {
    url: '/people',
    title: 'People',
  },
  {
    title: props.person.name,
  },
])

const shownThreads = computed<Thread[]>(() =>
  props.threads.filter((thread) => showThread(thread, props.person))
)

interface Modal {
  visible: boolean
  developmentForDelete: Development | null
}

let modal: Modal = reactive({
  visible: false,
  developmentForDelete: null,
})

const toggles = reactive({
  personal: true,
  interests: true,
  groups: true,
  groupMembers: true,
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

const showThread = function (thread: Thread, person: Person) {
  const has: boolean[] = []

  if (toggles.interests) {
    has.push(hasInterests(thread))
  }

  if (toggles.groups) {
    has.push(hasGroups(thread))
  }

  if (toggles.personal) {
    has.push(hasPerson(thread, person))
  }

  if (toggles.groupMembers) {
    has.push(hasGroupMembers(thread))
  }

  // we want to show the thread if any elements in the array are true
  return has.some((val) => val)
}

const hasInterests = function (thread: Thread) {
  return thread.interests.length > 0
}

const hasPerson = function (thread: Thread, person: Person) {
  const foundPerson = thread.people.find((per) => per.id === person.id)

  return foundPerson ? true : false
}

function hasGroups(thread: Thread) {
  return thread.groups.length > 0
}

/**
 * Return true if thread contains person who is in a group
 * with the subject person of this page
 */
function hasGroupMembers(thread: Thread) {
  const allOtherGroupMembers = props.person.groups
    .flatMap((group) => group.members)
    .filter((member) => member.id !== props.person.id)

  return allOtherGroupMembers.some((member) => hasPerson(thread, member))
}
</script>

<style scoped>
.layout-2-col {
  display: grid;
  grid-template-columns: 75% 25%;
  gap: var(--size-5);
}

.events-col {
  display: grid;
  grid-template-rows: min-content min-content 1fr;
  grid-gap: var(--size-3);
}

#threads-list .card {
  margin-bottom: var(--size-5);
}

.interests-list {
  list-style-type: none;
  padding-left: 0;
  display: flex;
  gap: var(--size-2);
  flex-wrap: wrap;
}

.interests-list li {
  background-color: var(--cardBg);
  padding: var(--size-1) var(--size-3);
  border-radius: var(--rounded-md);
  white-space: nowrap;
}

.groups-list {
  padding-left: 0;
  margin: 0;
}

.groups-list li {
  list-style-type: none;
  margin-bottom: var(--size-1);
}
</style>

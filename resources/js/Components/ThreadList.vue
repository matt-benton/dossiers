<template>
  <div class="card thread">
    <div v-for="(dev, index) in thread.developments">
      <DevelopmentCard
        :development="dev"
        :people-in-thread="thread.people"
        :interests-in-thread="thread.interests"
        :replyable="index + 1 === thread.developments.length"
        :closeable="removableDevelopments"
        @reply-button-clicked="showModal(thread)"
        @close-button-clicked="forwardDevelopmentRemovedEvent(dev)"
      />
      <hr v-if="index + 1 !== thread.developments.length" />
    </div>
  </div>
  <Modal :visible="modal.visible" @modal-closed="modal.visible = false">
    <ul>
      <li v-for="dev in thread.developments">
        <DevelopmentText
          :development="dev"
          :people-in-thread="thread.people"
          :interests-in-thread="thread.interests"
        />
      </li>
    </ul>
    <DevelopmentInput
      :thread="thread"
      @development-created="modal.visible = false"
      label="What happened next?"
      :unique-id="thread.id"
    />
  </Modal>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import DevelopmentCard from '../Components/DevelopmentCard.vue'
import Modal from '../Components/Modal.vue'
import Development from '../Types/Development'
import Thread from '../Types/Thread'
import DevelopmentInput from './DevelopmentInput.vue'
import DevelopmentText from './DevelopmentText.vue'

interface Modal {
  visible: boolean
  thread: Thread | null
}

const modal: Modal = reactive({
  visible: false,
  thread: null,
})

function showModal(thread: Thread) {
  modal.visible = true
  modal.thread = thread
}

defineProps<{
  thread: Thread
  removableDevelopments?: boolean
}>()

const emit = defineEmits<{
  (e: 'development-removed', developmentId: Development): void
}>()

function forwardDevelopmentRemovedEvent(development: Development) {
  emit('development-removed', development)
}
</script>

<style scoped>
.thread {
  margin-bottom: var(--size-5);
}

ul {
  margin-top: 0;
  padding-left: 0;
}

li {
  list-style-type: none;
}
</style>

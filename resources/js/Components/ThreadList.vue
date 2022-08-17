<template>
  <div class="card thread">
    <div v-for="(dev, index) in thread.developments">
      <DevelopmentCard
        :development="dev"
        :people-in-thread="thread.people"
        :replyable="index + 1 === thread.developments.length"
        @reply-button-clicked="showModal(thread)"
      />
      <hr v-if="index + 1 !== thread.developments.length" />
    </div>
  </div>
  <Modal :visible="modal.visible" @modal-closed="modal.visible = false">
    <DevelopmentInput
      :thread="thread"
      @development-created="modal.visible = false"
    />
  </Modal>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import DevelopmentCard from '../Components/DevelopmentCard.vue'
import Modal from '../Components/Modal.vue'
import Thread from '../Types/Thread'
import DevelopmentInput from './DevelopmentInput.vue'

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
}>()
</script>

<style scoped>
.thread {
  margin-bottom: var(--size-5);
}
</style>

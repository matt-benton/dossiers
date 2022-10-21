<template>
  <div class="layout-2-col">
    <div>
      <DevelopmentText
        :development="development"
        :people-in-thread="peopleInThread"
        :interests-in-thread="interestsInThread"
      />
      <p>
        <small>{{ useFormatDate(new Date(development.created_at)) }}</small>
      </p>
      <button
        type="button"
        class="btn-icon-text no-border"
        v-if="replyable"
        @click="onReplyButtonClicked"
      >
        <Reply />
        Next
      </button>
    </div>
    <div>
      <button
        v-if="closeable"
        type="button"
        class="close-button flex no-border justify-center"
        @click="onCloseButtonClicked"
        aria-label="Close"
      >
        <Close />
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineEmits } from 'vue'
import { useFormatDate } from '../Composables/format'
import Close from './Icons/Close.vue'
import Reply from './Icons/Reply.vue'
import Development from '../Types/Development'
import Person from '../Types/Person'
import Interest from '../Types/Interest'
import DevelopmentText from './DevelopmentText.vue'

defineProps<{
  development: Development
  peopleInThread: Person[]
  interestsInThread: Interest[]
  closeable?: Boolean
  replyable?: Boolean
}>()

const emit = defineEmits(['close-button-clicked', 'reply-button-clicked'])

function onCloseButtonClicked() {
  emit('close-button-clicked')
}

function onReplyButtonClicked() {
  emit('reply-button-clicked')
}
</script>

<style scoped>
.layout-2-col {
  display: grid;
  grid-template-columns: 1fr min-content;
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

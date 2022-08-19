<template>
  <div class="relative">
    <DevelopmentText
      :development="development"
      :people-in-thread="peopleInThread"
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
</template>

<script setup lang="ts">
import { defineEmits } from 'vue'
import { useFormatDate } from '../Composables/format'
import Close from './Icons/Close.vue'
import Reply from './Icons/Reply.vue'
import Development from '../Types/Development'
import Person from '../Types/Person'
import DevelopmentText from './DevelopmentText.vue'

defineProps<{
  development: Development
  peopleInThread: Person[]
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
.relative {
  position: relative;
}

.close-button {
  position: absolute;
  top: 0;
  right: 0;
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

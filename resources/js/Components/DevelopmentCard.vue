<template>
  <div class="relative">
    <p v-html="developmentText" />
    <p>
      <small>{{ useFormatDate(new Date(development.created_at)) }}</small>
    </p>
    <button
      type="button"
      class="flex justify-center no-border"
      v-if="replyable"
      @click="onReplyButtonClicked"
    >
      <Reply />
    </button>
    <button
      v-if="closeable"
      type="button"
      class="close-button flex no-border justify-center"
      @click="onCloseButtonClicked"
    >
      <Close />
    </button>
  </div>
</template>

<script setup lang="ts">
import { defineEmits, computed } from 'vue'
import { useFormatDate } from '../Composables/format'
import Close from './Icons/Close.vue'
import Reply from './Icons/Reply.vue'
import Development from '../Types/Development'
import Person from '../Types/Person'

const developmentText = computed(() => {
  let insertedText = props.development.description

  // loop through all the people tagged in this development
  // and insert tags to highlight their names
  props.peopleInThread.forEach((person) => {
    // get the index of the start and end of this person's name in the text
    const nameLoc = findPersonNameInText(insertedText, `@${person.name}`)

    if (nameLoc.start === null || nameLoc.end === null) {
      return ''
    }

    // this is a variable so we can get the length for the second insert
    const firstInsertText = '<b>'

    // insert the beginning tags
    insertedText = insertIntoMidString(
      insertedText,
      firstInsertText,
      nameLoc.start
    )

    // insert the end tags
    insertedText = insertIntoMidString(
      insertedText,
      '</b>',
      nameLoc.end + firstInsertText.length
    )
  })

  return insertedText
})

const findPersonNameInText = function (
  text: string,
  personName: string
): { start: number | null; end: number | null } {
  const startIndex = text
    .split('')
    .findIndex(
      (letter, index) =>
        text.slice(index, index + personName.length) === personName
    )

  return startIndex >= 0
    ? { start: startIndex, end: startIndex + personName.length }
    : { start: null, end: null }
}

const insertIntoMidString = function (
  string: string,
  insertText: string,
  index: number
) {
  return (
    string.slice(0, index) + insertText + string.slice(index, string.length)
  )
}

const props = defineProps<{
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

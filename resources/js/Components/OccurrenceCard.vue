<template>
  <div class="card">
    <p v-html="occurrenceText" />
    <small>{{ useFormatDate(new Date(occurrence.created_at)) }}</small>
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

<script setup>
import { defineProps, defineEmits, computed } from 'vue'
import { useFormatDate } from '../Composables/format'
import Close from '../Components/Icons/Close.vue'

const occurrenceText = computed(() => {
  let insertedText = props.occurrence.description

  // loop through all the people tagged in this occurrence
  // and insert tags to highlight their names
  props.occurrence.people.forEach((person) => {
    // get the index of the start and end of this person's name in the text
    const nameLoc = findPersonNameInText(insertedText, `@${person.name}`)

    // this is a variable so we can get the length for the second insert
    const firstInsertText = `<b>`

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

const findPersonNameInText = function (text, personName) {
  for (let i in text) {
    if (text.slice(i, parseInt(i) + personName.length) === personName) {
      return { start: parseInt(i), end: parseInt(i) + personName.length }
    }
  }
}

const insertIntoMidString = function (string, insertText, index) {
  return (
    string.slice(0, index) + insertText + string.slice(index, string.length)
  )
}

const props = defineProps({
  occurrence: Object,
  closeable: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['close-button-clicked'])

function onCloseButtonClicked() {
  emit('close-button-clicked')
}
</script>

<style scoped>
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

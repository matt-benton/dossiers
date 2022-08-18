<template>
  <p v-html="developmentText" />
</template>

<script setup lang="ts">
import { computed } from 'vue'
import Development from '../Types/Development'
import Person from '../Types/Person'

const props = defineProps<{
  development: Development
  peopleInThread: Person[]
}>()

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
</script>

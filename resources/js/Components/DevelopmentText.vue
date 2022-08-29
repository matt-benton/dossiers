<template>
  <p v-html="developmentText" />
</template>

<script setup lang="ts">
import { computed } from 'vue'
import Development from '../Types/Development'
import Person from '../Types/Person'
import Interest from '../Types/Interest'

const props = defineProps<{
  development: Development
  peopleInThread: Person[]
  interestsInThread: Interest[]
}>()

const developmentText = computed(() => {
  let insertedText = props.development.description

  // loop through all the people tagged in this development
  // and insert tags to highlight their names
  props.peopleInThread.forEach((person) => {
    insertedText = insertedText.replaceAll(
      `@${person.name}`,
      `<b>@${person.name}</b>`
    )
  })

  props.interestsInThread.forEach((interest) => {
    insertedText = insertedText.replaceAll(
      `@${interest.name}`,
      `<b>@${interest.name}</b>`
    )
  })

  return insertedText
})
</script>

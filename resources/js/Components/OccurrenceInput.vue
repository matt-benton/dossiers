<template>
  <form @submit.prevent="submitForm">
    <label for="occurrence_text">What's happening?</label>
    <textarea
      id="occurrence_text"
      @input="onInput"
      @keydown="onKeydown"
    ></textarea>
    <div
      class="flex"
      :class="{
        'justify-between': form.errors.description,
        'justify-end': !form.errors.description,
      }"
    >
      <span v-show="form.errors.description" class="text-danger">
        {{ form.errors.description }}
      </span>
      <button>Save</button>
    </div>
    <ul v-show="search.results.length > 0" id="search-results-list">
      <li
        v-for="(person, index) in search.results"
        :key="index"
        @click="onSearchResultClick"
        :class="{ highlighted: search.highlightedResultIndex === index }"
        @mouseover="search.highlightedResultIndex = index"
      >
        <PersonIcon />
        {{ person.name }}
      </li>
    </ul>
  </form>
</template>

<script setup lang="ts">
import axios from 'axios'
import { useForm } from '@inertiajs/inertia-vue3'
import { reactive } from 'vue'
import PersonIcon from './Icons/Person.vue'
import Person from '../Types/Person'

const form = useForm({
  description: '',
})

interface Search {
  text: string
  results: Array<Person>
  keylogging: boolean
  highlightedResultIndex: number | null
}

let search: Search = reactive({
  text: '',
  results: [],
  keylogging: false,
  highlightedResultIndex: null,
})

const submitForm = function () {
  form.post('/occurrences')
  resetForm()
}

const resetForm = function () {
  form.description = ''

  const occurrenceText: HTMLTextAreaElement | null =
    document.querySelector('#occurrence_text')

  if (occurrenceText) {
    occurrenceText.value = ''
  }
}

const onInput = function (event: Event) {
  // ! This only really works in ideal scenarios. In
  // ! many cases this will fail and there will be
  // ! no search. But I have decided that usually
  // ! users will use it correctly so I am going to
  // ! leave it in. When it works it is better than
  // ! anything else.
  const oldText = form.description
  const target = event.target as HTMLTextAreaElement
  const newText = target.value

  // find the difference between the old text and new text
  const numAddedChars = textWasAdded(oldText, newText)

  if (numAddedChars) {
    const added = getAddedText(oldText, newText, numAddedChars)

    if (search.keylogging && added.pos) {
      search.text = insertNewText(search.text, added.pos, added.text)
    }

    if (added.text === '@') {
      search.keylogging = true
    }
  } else {
    if (search.keylogging) {
      let searchArr = search.text.split('')
      let nonSearchOldTextLength = oldText.length - search.text.length

      let deletedTextArr = searchArr.splice(
        getStartOfTextDifference(oldText, newText) - nonSearchOldTextLength,
        oldText.length - newText.length
      )

      if (
        getDeletedText(oldText, newText, oldText.length - newText.length) ===
        '@'
      ) {
        resetSearch()
      }

      search.text = searchArr.join('')
    }
  }

  form.description = newText

  if (search.text.length > 0) {
    let url = `/api/people?search=${search.text}`

    axios.get(url).then((response) => {
      search.results = response.data.people

      if (search.results.length > 0) {
        search.highlightedResultIndex = 0
      }
    })
  }
}

const onKeydown = function (event: KeyboardEvent) {
  if (search.keylogging) {
    if (event.key === 'Tab') {
      event.preventDefault()

      if (!search.highlightedResultIndex) {
        if (search.results.length > 0) {
          selectPerson(search.results[0])
        }
      } else {
        selectPerson(search.results[search.highlightedResultIndex])
      }
    }
  }

  if (event.key === 'Enter') {
    event.preventDefault()
    submitForm()
  }

  if (event.key === 'ArrowDown') {
    nextResult()
  }

  if (event.key === 'ArrowUp') {
    prevResult()
  }
}

const nextResult = function () {
  if (
    search.highlightedResultIndex === null ||
    search.highlightedResultIndex + 1 === search.results.length
  ) {
    search.highlightedResultIndex = 0
  } else {
    search.highlightedResultIndex += 1
  }
}

const prevResult = function () {
  if (!search.highlightedResultIndex) {
    search.highlightedResultIndex = search.results.length - 1
  } else {
    search.highlightedResultIndex -= 1
  }
}

const textWasAdded = function (oldText: string, newText: string) {
  const numAddedChars = newText.length - oldText.length

  return numAddedChars > 0 ? numAddedChars : 0
}

const getAddedText = function (
  oldText: string,
  newText: string,
  numAddedChars: number
): { text: string; pos: number | null } {
  const startIndex = newText
    .split('')
    .findIndex((letter, i) => oldText[i] !== letter)

  return startIndex >= 0
    ? {
        text: newText.slice(startIndex, startIndex + numAddedChars),
        pos: startIndex,
      }
    : { text: '', pos: null }
}

const insertNewText = function (str: string, pos: number, newText: string) {
  return (
    str.substring(0, pos - 1) +
    newText +
    str.substring(pos + newText.length - 1)
  )
}

const getDeletedText = function (
  oldText: string,
  newText: string,
  numDeletedChars: number
): string {
  const startIndex = oldText
    .split('')
    .findIndex((letter, i) => letter !== newText[i])

  return startIndex >= 0
    ? oldText.slice(startIndex, startIndex + numDeletedChars)
    : ''
}

function onSearchResultClick() {
  if (search.highlightedResultIndex) {
    selectPerson(search.results[search.highlightedResultIndex])
  }
}

const selectPerson = function (person: Person) {
  form.description = form.description.replace(search.text, person.name)
  resetSearch()
  const occurrenceTextArea = document.querySelector(
    '#occurrence_text'
  ) as HTMLTextAreaElement
  occurrenceTextArea.value = form.description
  occurrenceTextArea.focus()
}

const resetSearch = function () {
  search.text = ''
  search.results = []
  search.keylogging = false
}

const getStartOfTextDifference = function (
  oldText: string,
  newText: string
): number {
  // loop through each character and see if they match
  // if they don't match, return the index where the difference occurs
  const nonMatchingIndex = oldText
    .split('')
    .findIndex((letter, i) => letter !== newText[i])

  // if we get through all of oldText without finding a match then the difference is at the end
  return nonMatchingIndex >= 0 ? nonMatchingIndex : oldText.length
}
</script>

<style scoped>
form {
  position: relative;
}

#search-results-list {
  position: absolute;
  background-color: var(--cardBg);
  top: 70px;
  border: 2px solid var(--inputBorder);
  border-radius: var(--rounded-md);
  padding: var(--size-1) 0;
  list-style-type: none;
  z-index: 50;
}

#search-results-list li {
  padding: var(--size-1) var(--size-5);
  display: flex;
  align-items: center;
}

#search-results-list li:hover {
  cursor: pointer;
}

.highlighted {
  background-color: var(--bgHighlight);
}

#search-results-list svg {
  height: var(--text-base);
  width: var(--text-base);
  margin-right: var(--size-2);
}
</style>

<template>
  <form @submit.prevent="submitForm">
    {{ search.text }}
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
    <ul v-show="search.results.length > 0">
      <li
        v-for="person in search.results"
        :key="person.id"
        @click="selectPerson($event, person)"
      >
        {{ person.name }}
      </li>
    </ul>
  </form>

  <h2>{{ search.text }}</h2>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { reactive } from 'vue'

const form = useForm({
  description: '',
})

let search = reactive({
  text: '',
  results: [],
  keylogging: false,
})

const submitForm = function () {
  form.post('/occurrences')
}

const onInput = function (event) {
  // ! This only really works in ideal scenarios. In
  // ! many cases this will fail and there will be
  // ! no search. But I have decided that usually
  // ! users will use it correctly so I am going to
  // ! leave it in. When it works it is better than
  // ! anything else.
  const oldText = form.description
  const newText = event.target.value

  // find the difference between the old text and new text
  const numAddedChars = textWasAdded(oldText, newText)

  if (numAddedChars) {
    const added = getAddedText(oldText, newText, numAddedChars)

    if (search.keylogging) {
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
    })
  }
}

const onKeydown = function (event) {
  if (search.keylogging) {
    if (event.key === 'Tab') {
      event.preventDefault()
      selectPerson(event, search.results[0])
    }
  }
}

const textWasAdded = function (oldText, newText) {
  const numAddedChars = newText.length - oldText.length

  return numAddedChars > 0 ? numAddedChars : 0
}

const getAddedText = function (oldText, newText, numAddedChars) {
  for (let i in newText) {
    if (oldText[i] !== newText[i]) {
      return { text: newText.slice(i, i + numAddedChars), pos: parseInt(i) }
    }
  }
}

const insertNewText = function (str, pos, newText) {
  return (
    str.substring(0, pos - 1) +
    newText +
    str.substring(pos + newText.length - 1)
  )
}

const getDeletedText = function (oldText, newText, numDeletedChars) {
  for (let i in oldText) {
    if (oldText[i] !== newText[i]) {
      return oldText.slice(i, parseInt(i) + numDeletedChars)
    }
  }
}

const selectPerson = function (event, person) {
  form.description = form.description.replace(search.text, person.name)
  resetSearch()
  document.querySelector('#occurrence_text').value = form.description
  document.querySelector('#occurrence_text').focus()
}

const resetSearch = function () {
  search.text = ''
  search.results = []
  search.keylogging = false
}

const getStartOfTextDifference = function (oldText, newText) {
  let newTextArr = newText.split('')

  // loop through each character and see if they match
  for (let i in oldText) {
    // if they don't match, return the index where the difference occurs
    if (oldText[i] !== newTextArr[i]) {
      return parseInt(i)
    }
  }

  // if we get through all of oldText without finding a match then the difference is at the end
  return oldText.length
}
</script>

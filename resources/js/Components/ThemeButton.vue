<template>
  <button type="button" class="btn-icon-text no-border" @click="changeTheme">
    <Sun v-if="theme === 'light'" />
    <Moon v-else-if="theme === 'dark'" />
    <Computer v-else /> Theme
  </button>
</template>

<script setup lang="ts">
import { ref, onBeforeMount, watch } from 'vue'
import Sun from './Icons/Sun.vue'
import Moon from './Icons/Moon.vue'
import Computer from './Icons/Computer.vue'

let theme = ref('')

onBeforeMount(() => {
  theme.value = localStorage.getItem('theme') ?? 'light'
})

watch(theme, (newVal) => {
  switch (newVal) {
    case 'light':
      localStorage.setItem('theme', setLightTheme())
      break
    case 'dark':
      localStorage.setItem('theme', setDarkTheme())
      break
    case 'os':
      localStorage.setItem('theme', setOsTheme())
  }
})

const changeTheme = function () {
  switch (theme.value) {
    case 'light':
      theme.value = 'dark'
      break
    case 'dark':
      theme.value = 'os'
      break
    case 'os':
      theme.value = 'light'
  }
}

function setDarkTheme() {
  let body = document.querySelector('body') as Element
  body.classList.replace('light', 'dark') ??
    body.classList.replace('os', 'dark')

  return 'dark'
}

function setLightTheme() {
  let body = document.querySelector('body') as Element
  body.classList.replace('dark', 'light') ??
    body.classList.replace('os', 'light')

  return 'light'
}

function setOsTheme() {
  let userPrefDark = window.matchMedia('(prefers-color-scheme: dark)').matches

  if (userPrefDark) {
    setDarkTheme()
  } else {
    setLightTheme()
  }

  return 'os'
}
</script>

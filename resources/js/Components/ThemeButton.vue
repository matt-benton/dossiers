<template>
  <button type="button" class="btn-icon-text no-border" @click="changeTheme">
    <Sun v-if="theme === 'light'" />
    <Moon v-else-if="theme === 'dark'" />
    <span v-else>OS</span> Theme
  </button>
</template>

<script>
import Sun from './Icons/Sun.vue'
import Moon from './Icons/Moon.vue'
export default {
  data() {
    return {
      theme: '',
    }
  },
  created() {
    this.theme = localStorage.getItem('theme') ?? 'light'
  },
  watch: {
    theme(newVal) {
      switch (newVal) {
        case 'light':
          localStorage.setItem('theme', this.setLightTheme())
          break
        case 'dark':
          localStorage.setItem('theme', this.setDarkTheme())
          break
        case 'os':
          localStorage.setItem('theme', this.setOsTheme())
      }
    },
  },
  methods: {
    changeTheme() {
      switch (this.theme) {
        case 'light':
          this.theme = 'dark'
          break
        case 'dark':
          this.theme = 'os'
          break
        case 'os':
          this.theme = 'light'
      }
    },
    setDarkTheme() {
      let body = document.querySelector('body')
      body.classList.replace('light', 'dark') ??
        body.classList.replace('os', 'dark')

      return 'dark'
    },
    setLightTheme() {
      let body = document.querySelector('body')
      body.classList.replace('dark', 'light') ??
        body.classList.replace('os', 'light')

      return 'light'
    },
    setOsTheme() {
      let userPrefDark = window.matchMedia(
        '(prefers-color-scheme: dark)'
      ).matches

      if (userPrefDark) {
        this.setDarkTheme()
      } else {
        this.setLightTheme()
      }

      return 'os'
    },
  },
  components: {
    Sun,
    Moon,
  },
}
</script>

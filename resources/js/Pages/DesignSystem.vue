<template>
  <div class="container">
    <h1>This is the design system</h1>
    <h2>This is the design system</h2>
    <h3>This is the design system</h3>
    <h4>This is the design system</h4>
    <h5>This is the design system</h5>
    <button type="button" @click="toggle">Toggle Light/Dark</button>
    <p>
      Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
      tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
      veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
      commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
      velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
      cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id
      est laborum.
    </p>
    <small>Small text</small>
    <hr />
    <h3>Forms and Inputs</h3>
    <div class="form-group">
      <label for="text-input">Text</label>
      <input type="text" id="text-input" placeholder="Placeholder text" />
    </div>
    <div class="form-group">
      <label for="select">Select</label>
      <select id="select">
        <option disabled selected>Please choose an option</option>
        <option>First Option</option>
        <option>Second Option</option>
        <option>Third Option</option>
      </select>
    </div>
    <div class="form-group">
      <label for="textarea">Textarea</label>
      <textarea id="textarea" placeholder="Fill out this please"></textarea>
    </div>
    <div class="btn-row">
      <button type="button" class="btn-primary">Primary</button>
      <button type="button">Default</button>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    toggle() {
      let body = document.querySelector('body')
      let bodyClass = body.classList[0]
      body.classList.remove(bodyClass)

      if (bodyClass === 'light') {
        body.classList.add('dark')
      } else {
        body.classList.add('light')
      }
    },
  },
}
</script>

<style></style>

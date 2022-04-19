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
      tempor incididunt ut labore et dolore magna aliqua. Arcu cursus vitae
      congue mauris rhoncus aenean vel. Tincidunt lobortis feugiat vivamus at
      augue eget arcu dictum. Velit laoreet id donec ultrices tincidunt.
      Adipiscing at in tellus integer feugiat scelerisque varius morbi. Commodo
      odio aenean sed adipiscing diam donec adipiscing tristique risus. Mi eget
      mauris pharetra et ultrices. Turpis egestas maecenas pharetra convallis
      posuere morbi leo urna. Congue mauris rhoncus aenean vel elit scelerisque.
      Ut enim blandit volutpat maecenas volutpat blandit aliquam etiam.
    </p>

    <p>
      Amet consectetur adipiscing elit duis tristique sollicitudin nibh.
      Ultrices tincidunt arcu non sodales neque sodales ut etiam sit. A diam
      maecenas sed enim ut sem. Volutpat diam ut venenatis tellus. Tincidunt
      tortor aliquam nulla facilisi cras fermentum odio. Sem fringilla ut morbi
      tincidunt augue interdum velit euismod in. Faucibus purus in massa tempor.
      Ultrices sagittis orci a scelerisque purus semper eget duis at. Mi ipsum
      faucibus vitae aliquet nec ullamcorper sit amet risus. Sapien faucibus et
      molestie ac feugiat sed lectus. Sit amet nisl suscipit adipiscing.
    </p>

    <p>
      Nullam non nisi est sit amet facilisis magna. Tempor id eu nisl nunc mi
      ipsum faucibus vitae. Mauris pharetra et ultrices neque ornare aenean
      euismod. Maecenas ultricies mi eget mauris pharetra. Et egestas quis ipsum
      suspendisse ultrices gravida dictum fusce. Id cursus metus aliquam
      eleifend mi in nulla posuere. At consectetur lorem donec massa sapien
      faucibus et. Id aliquet lectus proin nibh nisl condimentum id. Volutpat
      consequat mauris nunc congue nisi vitae suscipit. Consequat nisl vel
      pretium lectus quam. Et netus et malesuada fames ac turpis egestas integer
      eget. Proin nibh nisl condimentum id venenatis a condimentum. Sapien
      pellentesque habitant morbi tristique senectus et netus et. Ultricies
      tristique nulla aliquet enim tortor at auctor. Pharetra vel turpis nunc
      eget lorem dolor. Porta non pulvinar neque laoreet. Sollicitudin tempor id
      eu nisl. Et tortor consequat id porta nibh venenatis cras. Vitae tempus
      quam pellentesque nec. Et odio pellentesque diam volutpat commodo sed
      egestas egestas.
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
    <div class="checkbox-group">
      <input type="checkbox" id="checkbox" />
      <label for="checkbox">Checkbox</label>
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

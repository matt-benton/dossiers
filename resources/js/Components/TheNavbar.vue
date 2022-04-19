<template>
  <nav>
    <div class="brand">
      <h1 class="text-lg"><a href="#">Dossiers</a></h1>
    </div>
    <div class="navbar-menu">
      <div class="navbar-menu-left">
        <ul>
          <li v-for="n in 3"><a href="#">Nav Link</a></li>
        </ul>
      </div>
      <div class="navbar-menu-right">
        <ul>
          <li class="dropdown">johndoe@test.com <ChevronDown /></li>
        </ul>
      </div>
    </div>
  </nav>
</template>
<script>
import ChevronDown from './Icons/ChevronDown.vue'

export default {
  components: {
    ChevronDown,
  },
}
</script>

<style scoped>
nav {
  display: flex;
  padding: var(--size-3) var(--size-5);
}

nav a {
  text-decoration: none;
}

ul {
  display: flex;
  list-style-type: none;
  padding-left: 0;
}

li {
  padding: 0 var(--size-3);
}

.brand {
  display: flex;
  align-items: center;
  justify-content: center;
}

.brand a {
  color: var(--fontColor);
}

.navbar-menu {
  display: flex;
  justify-content: space-between;
  width: 100%;
  margin-left: var(--size-5);
}

.dropdown {
  display: flex;
  align-items: center;
}

.dropdown svg {
  margin-left: var(--size-3);
}
</style>

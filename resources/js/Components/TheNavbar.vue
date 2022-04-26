<template>
  <nav aria-label="Main">
    <div class="brand">
      <h1 class="text-lg"><Link href="/">Dossiers</Link></h1>
    </div>
    <div class="navbar-menu" v-if="user">
      <div class="navbar-menu-left">
        <ul>
          <li><Link href="/people">People</Link></li>
          <li v-for="n in 3"><a href="#">Nav Link</a></li>
        </ul>
      </div>
      <div class="navbar-menu-right">
        <ul>
          <li>
            <ThemeButton />
          </li>
          <li>
            <div class="dropdown">
              <div
                class="dropdown-toggle"
                @click="accountDropDownVisible = true"
              >
                {{ user.email }} <ChevronDown />
              </div>
              <ul class="dropdown-menu" v-show="accountDropDownVisible">
                <li><a href="#">Dropdown Link</a></li>
                <li><a href="#">Dropdown Link</a></li>
                <li><Link href="/design_system">Design System</Link></li>
                <li><Link href="/logout">Log out</Link></li>
              </ul>
            </div>
            <Teleport to="body">
              <div
                class="dropdown-cover"
                v-show="accountDropDownVisible"
                @click="hideAccountDropDown"
              ></div>
            </Teleport>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
<script>
import ChevronDown from './Icons/ChevronDown.vue'
import ThemeButton from './ThemeButton.vue'
import { Link } from '@inertiajs/inertia-vue3'

export default {
  data() {
    return {
      accountDropDownVisible: false,
    }
  },
  methods: {
    hideAccountDropDown(event) {
      if (!event.target.classList.contains('dropdown-menu')) {
        this.accountDropDownVisible = false
      }
    },
  },
  components: {
    ChevronDown,
    ThemeButton,
    Link,
  },
  props: {
    user: {
      type: Object,
    },
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
  position: relative;
}

.dropdown-toggle {
  display: flex;
  align-items: center;
  cursor: pointer;
}

.dropdown-toggle svg {
  margin-left: var(--size-3);
}

.dropdown-menu {
  position: absolute;
  top: var(--size-8);
  right: 0;
  display: flex;
  flex-direction: column;
  gap: var(--size-2);
  background-color: var(--cardBg);
  border-radius: 5px;
  padding: var(--size-4) var(--size-5);
  z-index: 9999;
}

.dropdown-menu li a {
  white-space: nowrap;
}

.dropdown-cover {
  background: transparent;
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  right: 0;
}
</style>

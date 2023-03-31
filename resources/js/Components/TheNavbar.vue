<template>
  <nav aria-label="Main">
    <div class="brand">
      <h1 class="text-lg"><Link href="/">Dossiers</Link></h1>
    </div>
    <div class="navbar-menu" v-if="user">
      <div class="navbar-menu-left">
        <ul>
          <li><Link href="/people">People</Link></li>
          <li><Link href="/interests">Interests</Link></li>
          <li><Link href="/groups">Groups</Link></li>
        </ul>
      </div>
      <div class="navbar-menu-right">
        <ul>
          <li>
            <ThemeButton />
          </li>
          <li>
            <div class="dropdown-container">
              <div
                class="dropdown-toggle"
                @click="accountDropDownVisible = true"
              >
                {{ user.email }} <ChevronDown />
              </div>
              <Dropdown
                :visible="accountDropDownVisible"
                @dropdown-close="hideAccountDropDown"
                direction="right"
              >
                <ul class="dropdown-menu" v-show="accountDropDownVisible">
                  <li><Link href="/design_system">Design System</Link></li>
                  <li><Link href="/logout">Log out</Link></li>
                </ul>
              </Dropdown>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <MenuIcon class="mobile-menu-button nonresponsive-icon" @click="mobileMenuVisible = true"/>
    <MobileMenu :visible="mobileMenuVisible" @menu-closed="mobileMenuVisible = false"/>
  </nav>
</template>
<script setup lang="ts">
import ChevronDown from './Icons/ChevronDown.vue'
import ThemeButton from './ThemeButton.vue'
import { ref } from 'vue'
import type User from '../types/User'
import Dropdown from './Dropdown.vue'
import MenuIcon from './Icons/Menu.vue'
import MobileMenu from './MobileMenu.vue'

let accountDropDownVisible = ref(false)
let mobileMenuVisible = ref(false)

function hideAccountDropDown(event: Event) {
  const target = event.target as Element
  if (!target.classList.contains('dropdown-menu')) {
    accountDropDownVisible.value = false
  }
}

defineProps<{
  user?: User
}>()
</script>

<style scoped>
nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
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

.dropdown-container {
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
  display: flex;
  flex-direction: column;
  gap: var(--size-2);
  background-color: var(--cardBg);
  border-radius: 5px;
  padding: var(--size-4) var(--size-5);
}

.dropdown-menu li a {
  white-space: nowrap;
}

.mobile-menu-button {
    display: none;
}

@media (max-width: 640px) {
  .navbar-menu {
    display: none;
  }

  .mobile-menu-button {
    display: block;
  }
}
</style>

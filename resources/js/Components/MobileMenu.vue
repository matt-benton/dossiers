<template>
    <Teleport to="body">
        <Transition>
            <nav id="mobile-menu" v-if="visible">
                <div id="top-row">
                  <h2 class="text-lg"><Link href="/">Dossiers</Link></h2>
                  <CloseIcon class="nonresponsive-icon" @click="onMenuClosed" />
                </div>
                <ul>
                  <li><Link href="/people">People</Link></li>
                  <li><Link href="/interests">Interests</Link></li>
                  <li><Link href="/groups">Groups</Link></li>
                  <hr />
                  <li><ThemeButton /></li>
                  <li><Link href="/logout">Log out</Link></li>
                </ul>
            </nav>
        </Transition>
    </Teleport>
</template>
<script setup lang="ts">
import ThemeButton from './ThemeButton.vue'
import CloseIcon from './Icons/Close.vue'

defineProps<{
    visible: boolean
}>()

const emit = defineEmits<{
    (e: 'menu-closed'): void
}>()

function onMenuClosed() {
    emit('menu-closed')
}
</script>
<style scoped>
#mobile-menu {
    position: absolute;
    top: 0;
    width: 100vw;
    height: 100vh;
    background-color: var(--bgColor);
    padding: var(--size-3) var(--size-5);
}

#top-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

h2 a {
    color: var(--fontColor);
}

ul {
    list-style-type: none;
    padding-left: 0;
    width: 100%;
    font-size: var(--text-lg);
    margin-top: var(--size-12);
}

li {
    display: flex;
    justify-content: center;
    margin: var(--size-5) 0;
}

.v-enter-active,
.v-leave-active {
  transition: opacity 0.2s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>

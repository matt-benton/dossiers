<template>
  <Transition>
    <div v-show="visible" class="alert">
      <slot></slot>
    </div>
  </Transition>
</template>

<script>
export default {
  props: {
    visible: Boolean,
  },
}
</script>

<style scoped>
.alert {
  position: fixed;
  left: 50%;
  bottom: var(--size-6);
  transform: translate(-50%, 0);
  transition: transform 0.4s;
}

.v-enter-from,
.v-leave-to {
  transform: translate(-50%, 150%);
}
</style>

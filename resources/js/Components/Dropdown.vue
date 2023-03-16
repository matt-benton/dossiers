<!-- NOTE: Component must be placed as the direct child of a relatively positioned element to be positioned correctly. -->
<template>
  <div
    class="dropdown"
    v-show="visible"
    :class="{ right: direction === 'right', left: direction === 'left' }"
  >
    <slot></slot>
  </div>
  <Teleport to="body">
    <div
      class="dropdown-cover"
      v-show="visible"
      @click="$emit('dropdown-close', $event)"
    ></div>
  </Teleport>
</template>

<script setup lang="ts">
interface Props {
  visible: boolean
  direction?: 'right' | 'left'
}

withDefaults(defineProps<Props>(), {
  direction: 'left',
})

defineEmits(['dropdown-close'])
</script>

<style scoped>
.dropdown {
  position: absolute;
  top: var(--size-8);
  z-index: 9998;
}

.right {
  right: 0;
}

.left {
  left: 0;
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

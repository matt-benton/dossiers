<template>
  <Teleport to="body">
    <Transition>
      <div
        class="backdrop"
        v-if="visible"
        @click="onBackdropClicked"
        role="dialog"
        aria-label="Dialog"
      >
        <div class="modal">
          <div class="center-container-sm">
            <div class="card">
              <slot></slot>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
function onBackdropClicked(event: Event) {
  const target = event.target as Element

  if (target.classList.contains('backdrop')) {
    emit('modal-closed')
  }
}

defineProps({
  visible: Boolean,
})

const emit = defineEmits(['modal-closed'])
</script>

<style scoped>
.backdrop {
  background-color: rgba(27, 26, 26, 0.7);
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

.modal {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -70%);
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

<template>
  <Head title="Add Interest" />
  <Authenticated>
    <Breadcrumb :links="breadCrumbLinks" />
    <div class="center-container-xs">
      <div class="card">
        <form @submit.prevent="submitForm">
          <div class="form-group">
            <label for="name">Name</label>
            <input
              type="text"
              :class="{ 'border-danger': form.errors.name }"
              id="name"
              v-model="form.name"
              autocomplete="off"
              ref="nameInput"
            />
            <span v-if="form.errors.name" class="text-danger">{{
              form.errors.name
            }}</span>
          </div>
          <div class="flex justify-end">
            <button class="btn-primary">Add Interest</button>
          </div>
        </form>
      </div>
    </div>
  </Authenticated>
</template>

<script setup lang="ts">
import { reactive, onMounted, ref } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import Authenticated from '../../Layouts/Authenticated.vue'
import Breadcrumb from '../../Components/Breadcrumb.vue'

// autofocus name input
const nameInput = ref<HTMLInputElement | null>(null)
onMounted(() => nameInput.value?.focus())

let breadCrumbLinks = reactive([
  {
    url: '/interests',
    title: 'Interests',
  },
  {
    title: 'Add Interest',
  },
])

let form = useForm({
  name: null,
})

const submitForm = function () {
  form.post('/interests')
}
</script>

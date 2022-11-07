<template>
  <Head title="Add Group" />
  <Authenticated>
    <Breadcrumb :links="breadcrumbLinks" />
    <div class="center-container-xs">
      <div class="card">
        <form @submit.prevent="form.post('/groups')">
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
            <button class="btn-primary">Add Group</button>
          </div>
        </form>
      </div>
    </div>
  </Authenticated>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/inertia-vue3'
import Authenticated from '../../Layouts/Authenticated.vue'
import Breadcrumb from '../../Components/Breadcrumb.vue'
import { reactive, ref, onMounted } from 'vue'

// autofocus name input
const nameInput = ref<HTMLInputElement | null>(null)
onMounted(() => nameInput.value?.focus())

const breadcrumbLinks = reactive([
  {
    url: '/groups',
    title: 'Groups',
  },
  {
    title: 'Add Group',
  },
])

let form = useForm({
  name: null,
})
</script>

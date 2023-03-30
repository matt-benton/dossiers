<template>
  <h5>Toggle</h5>
  <div class="card">
    <div class="checkbox-group">
      <input
        type="checkbox"
        id="interests-checkbox"
        v-model="toggles.interests"
        :true-value="true"
        :false-value="false"
      />
      <label for="interests-checkbox">Interests</label>
    </div>
    <div class="checkbox-group">
      <input
        type="checkbox"
        id="personal-checkbox"
        v-model="toggles.personal"
        :true-value="true"
        :false-value="false"
      />
      <label for="personal-checkbox">Personal</label>
    </div>
    <div class="checkbox-group">
      <input
        type="checkbox"
        id="groups-checkbox"
        v-model="toggles.groups"
        :true-value="true"
        :false-value="false"
      />
      <label for="groups-checkbox">Groups</label>
    </div>
    <div class="checkbox-group">
      <input
        type="checkbox"
        id="grouped-checkbox"
        v-model="toggles.groupMembers"
        :true-value="true"
        :false-value="false"
      />
      <label for="grouped-checkbox">Group Members</label>
    </div>
  </div>
</template>
<script setup lang="ts">
type Toggles = {
  personal: boolean,
  interests: boolean,
  groups: boolean,
  groupMembers: boolean,
}

defineProps<{
  toggles: Toggles,
}>()
</script>

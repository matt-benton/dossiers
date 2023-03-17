<template>
    <div class="menu-row">
        <div class="menu-row-left">
            <label for="search">Search</label>
            <input
                type="text"
                :value="searchText"
                id="search"
                :placeholder="placeholder"
                @input="emitUpdateSearchText"
            />
            <button type="button" @click="resetSearch">Reset</button>
        </div>
        <div class="menu-row-right">
            <Link :href="link.url">{{ link.text }}</Link>
        </div>
    </div>
</template>

<script setup lang="ts">

defineProps<{
    searchText: string,
    placeholder: string,
    link: Link,
}>()

interface Link {
    text: string,
    url: string,
}

const emit = defineEmits(['update:searchText', 'resetSearch'])

function emitUpdateSearchText (event: Event) {
    const target = event.target as HTMLInputElement

    if (target) {
        emit('update:searchText', target.value)
    }
}

function resetSearch() {
    emit('resetSearch')
}

</script>

<style scoped>
.menu-row {
  display: flex;
  justify-content: space-between;
  margin: var(--size-4) 0 var(--size-2) 0;
  flex-wrap: wrap;
  gap: var(--size-1);
}

.menu-row-left {
  display: flex;
  align-items: center;
  gap: var(--size-2);
}

.menu-row-right {
  display: flex;
  align-items: flex-end;
}

#search {
  width: 250px;
}

@media (max-width: 640px) {
  #search {
    width: 100%;
  }
}
</style>
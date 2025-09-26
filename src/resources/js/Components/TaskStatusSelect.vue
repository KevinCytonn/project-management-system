<script setup>
const props = defineProps({
  value: String
});
const emit = defineEmits(['change']);

const statuses = [
  { value: 'not_started', label: 'Not Started' },
  { value: 'in_progress', label: 'In Progress' },
  { value: 'testing', label: 'Testing' },
  { value: 'complete', label: 'Complete' }
];
</script>

<template>
  <select
    :value="value"
    @change="emit('change', $event.target.value)"
    class="border rounded p-2 text-sm w-full"
  >
    <option v-for="s in statuses" :key="s.value" :value="s.value">
      {{ s.label }}
    </option>
  </select>
</template>

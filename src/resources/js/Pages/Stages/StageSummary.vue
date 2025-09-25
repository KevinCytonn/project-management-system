<script setup>
const props = defineProps({
  project: Object
});
</script>

<template>
  <div class="mt-6 border rounded p-4 bg-white shadow">
    <h2 class="text-lg font-bold mb-2">Stage Summary</h2>
    <p>Current Stage: {{ project.current_stage }}</p>
    <p>Requires Design: {{ project.requires_design ? 'Yes' : 'No' }}</p>
  </div>
</template>

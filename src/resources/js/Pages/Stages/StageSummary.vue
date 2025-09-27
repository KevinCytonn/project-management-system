<script setup>
const props = defineProps({
  project: Object
});
</script>

<template>
  <div class="mt-6 border rounded-xl p-5 bg-white shadow-sm">
    <h2 class="text-lg font-bold mb-3 text-gray-700">Stage Summary</h2>
    <p class="text-sm text-gray-600">
      Current Stage:
      <span class="font-medium capitalize text-gray-800">
        {{ project.current_stage }}
      </span>
    </p>

    <p
      v-if="project.requires_design"
      class="text-sm text-gray-600 mt-1"
    >
      Requires Design: <span class="font-medium">Yes</span>
    </p>
  </div>
</template>

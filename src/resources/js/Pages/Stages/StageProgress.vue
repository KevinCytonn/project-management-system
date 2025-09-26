<script setup>
const props = defineProps({
  project: Object
});

// Base stages
let stages = ['product', 'design', 'development', 'completed'];

// If design is NOT required, remove it
if (!props.project.requires_design) {
  stages = stages.filter(s => s !== 'design');
}
</script>

<template>
  <div class="flex space-x-4">
    <div
      v-for="s in stages"
      :key="s"
      class="flex-1 text-center py-2 rounded capitalize font-medium"
      :class="[
        project.current_stage === s ? 'bg-blue-600 text-white' :
        stages.indexOf(s) < stages.indexOf(project.current_stage) ? 'bg-green-500 text-white' :
        'bg-gray-200 text-gray-600'
      ]"
    >
      {{ s }}
    </div>
  </div>
</template>

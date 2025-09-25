<script setup>
const props = defineProps({
  stage: String
});

const stages = ['product', 'design', 'development', 'completed'];
</script>

<template>
  <div class="flex space-x-4">
    <div
      v-for="s in stages"
      :key="s"
      class="flex-1 text-center py-2 rounded"
      :class="[
        stage === s ? 'bg-blue-600 text-white' :
        stages.indexOf(s) < stages.indexOf(stage) ? 'bg-green-500 text-white' :
        'bg-gray-200 text-gray-600'
      ]"
    >
      {{ s.toUpperCase() }}
    </div>
  </div>
</template>

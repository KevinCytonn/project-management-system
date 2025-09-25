<script setup>
import { Link } from '@inertiajs/vue3';
import StageProgress from '../Stages/StageProgress.vue';
import StageSummary from '../Stages/StageSummary.vue';

const props = defineProps({
  project: Object
});
</script>

<template>
  <div class="space-y-6">
   
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold">{{ project.name }}</h1>
      <Link
        :href="route('projects.index')"
        class="text-sm text-gray-600 hover:underline"
      >Back to Projects</Link>
    </div>

    <p class="text-gray-700">{{ project.description }}</p>
   

    <StageProgress :stage="project.current_stage" />

    <StageSummary :project="project" />

    <div class="mt-6">
      <Link
        :href="route('tasks.index', project.id)"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        View Tasks
      </Link>
    </div>
  </div>
</template>

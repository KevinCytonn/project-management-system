<script setup>
import { Link } from '@inertiajs/vue3';
import StageProgress from '../Stages/StageProgress.vue';
import StageSummary from '../Stages/StageSummary.vue';
import ManagerLayout from '@/Layouts/ManagerLayout.vue';

const props = defineProps({
  project: Object
});
</script>

<template>
  <ManagerLayout>
    <div class="space-y-8 max-w-5xl mx-auto">
      <!-- Header -->
      <div class="flex justify-between items-center border-b pb-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">{{ project.name }}</h1>
          <p class="text-gray-600 mt-1">
            {{ project.description || "No description provided." }}
          </p>
        </div>
        <Link
          :href="route('projects.index')"
          class="px-3 py-1.5 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 transition"
        >
          Back
        </Link>
      </div>

      <!-- Stage Info -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white shadow-sm rounded-xl p-5">
          <h2 class="text-lg font-semibold text-gray-700 mb-3">Progress</h2>
          <StageProgress :project="project" />
        </div>

        <div class="bg-white shadow-sm rounded-xl p-5">
          <h2 class="text-lg font-semibold text-gray-700 mb-3">Summary</h2>
          <StageSummary :project="project" />
        </div>
      </div>

      <!-- Tasks -->
      <div class="bg-white shadow-sm rounded-xl p-5">
        <h2 class="text-lg font-semibold text-gray-700 mb-3">Tasks</h2>
        <p class="text-sm text-gray-500 mb-4">
          View all tasks related to this project and track their progress.
        </p>
        <Link
          :href="route('tasks.index', { project: project.id })"
          class="inline-block px-4 py-2 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700 transition"
        >
          View Tasks
        </Link>
      </div>
    </div>
  </ManagerLayout>
</template>

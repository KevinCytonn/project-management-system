<script setup>
import { Link } from '@inertiajs/vue3';
import TaskStatusUpdater from './TaskStatusUpdater.vue';

const props = defineProps({
  task: Object,
  project: Object
});
</script>

<template>
  <div class="border p-4 rounded bg-white shadow-sm">
    <h2 class="font-semibold">{{ task.title }}</h2>
    <p class="text-sm text-gray-600">{{ task.description }}</p>

    <div class="mt-2 text-sm">
      <p>Status: <TaskStatusUpdater :task="task" :project="project" /></p>
      <p>Assigned to: {{ task.assignee?.name || "Unassigned" }}</p>
      <p v-if="task.due_date" class="text-gray-500">Due: {{ task.due_date }}</p>
      <p>{{ task }}</p>
    </div>

    <div class="mt-4 flex space-x-3">
      <Link :href="route('tasks.edit', [project.id, task.id])" class="text-blue-600">Edit</Link>
    </div>
  </div>
</template>

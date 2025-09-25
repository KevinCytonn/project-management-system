<script setup>
import { Link } from '@inertiajs/vue3';
import TaskCard from './TaskCard.vue';

const props = defineProps({
  project: Object,
  tasks: Array
});
</script>

<template>
  <div>
    <h1 class="text-xl font-bold mb-4">Tasks for {{ project.name }}</h1>

    <Link
      :href="route('tasks.create', project.id)"
      class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700"
    >
      + Add Task
    </Link>

    <div class="mt-6 space-y-4">
      <TaskCard
        v-for="task in tasks"
        :key="task.id"
        :task="task"
        :project="project"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
  project: Object,
  tasks: Array
});

const statusColors = {
  not_started: 'bg-gray-300',
  in_progress: 'bg-yellow-400',
  complete: 'bg-green-500',
  testing: 'bg-blue-500'
};
</script>

<template>
  <Head :title="`Tasks for ${project.name}`" />

  <div class="p-6">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold">Tasks - {{ project.name }}</h2>
      <Link :href="`/projects/${project.id}/tasks/create`" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Create Task</Link>
    </div>

    <div class="space-y-4">
      <div v-for="task in tasks" :key="task.id" class="p-4 bg-white shadow rounded-lg flex justify-between items-center">
        <div>
          <h3 class="font-medium">{{ task.title }}</h3>
          
          
          <p class="text-sm text-gray-500">{{ task.description }}</p>
          <p class="text-xs text-gray-500">Assigned to: {{ task.assignee?.name }}</p>
        </div>

        <div class="flex items-center gap-2">
          <span :class="`${statusColors[task.status]} px-2 py-1 rounded text-white text-xs`">{{ task.status }}</span>
          <Link :href="route('tasks.edit', [project.id, task.id])" class="text-blue-600 hover:underline">Edit</Link>
        </div>
    
      </div>
    </div>
  </div>
</template>

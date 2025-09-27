<script setup>
import { Link } from '@inertiajs/vue3';
import TaskCard from './TaskCard.vue';
import AuthenticatedLayout
 from '@/Layouts/AuthenticatedLayout.vue';
const props = defineProps({
  project: Object,
  tasks: Array
});
</script>

<template>
  <AuthenticatedLayout>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-800">
        Tasks for {{ project.name }}
      </h1>
      <Link
        :href="route('tasks.create', project.id)"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition"
      >
        + Add Task 
      </Link>
    </div>

   
    <div
      v-if="tasks.length === 0"
      class="p-6 border rounded-lg bg-gray-50 text-center text-gray-500"
    >
      No tasks yet for this project
    </div>

    
    <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <TaskCard
        v-for="task in tasks"
        :key="task.id"
        :task="task"
        :project="project"
      />
    </div>
  </div>
  </AuthenticatedLayout>
</template>

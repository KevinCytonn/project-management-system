<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  project: Object
});
</script>

<template>
  <div class="border rounded-lg shadow-sm bg-white p-4">
    <h2 class="font-semibold text-lg">{{ project.name }}</h2>
    <p class="text-sm text-gray-600">{{ project.description }}</p>

    <div class="mt-3 text-sm">
      <p>Stage: <span class="font-medium">{{ project.current_stage }}</span></p>
      <p>Created by: {{ project.creator.name }}</p>
      <p class="text-gray-500">Created {{ project.created_at }}</p>
    </div>

    <div class="mt-4 flex space-x-2">
      <Link
        :href="route('projects.edit', project.id)"
        class="text-blue-600 hover:underline"
      >View</Link>
    </div>
  </div>
</template>

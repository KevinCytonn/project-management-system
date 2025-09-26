<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  project: Object
});
</script>

<template>
  <div
    class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-5 flex flex-col justify-between"
  >
    <!-- Header -->
    <div>
      <h2 class="text-xl font-semibold text-gray-800 mb-1 truncate">
        {{ project.name }}
      </h2>
      <p class="text-gray-600 text-sm line-clamp-2">
        {{ project.description || 'No description provided.' }}
      </p>
    </div>

    <!-- Meta Info -->
    <div class="mt-4 space-y-1 text-sm">
      <p>
        <span class="text-gray-500">Stage:</span>
        <span
          class="ml-1 px-2 py-0.5 rounded-full text-xs font-medium"
          :class="{
            'bg-yellow-100 text-yellow-800': project.current_stage === 'product',
            'bg-blue-100 text-blue-800': project.current_stage === 'design',
            'bg-green-100 text-green-800': project.current_stage === 'development',
            'bg-gray-100 text-gray-600': !project.current_stage
          }"
        >
          {{ project.current_stage || 'N/A' }}
        </span>
      </p>
      <p>
        <span class="text-gray-500">Created by:</span>
        <span class="ml-1 font-medium">{{ project.creator.name }}</span>
      </p>
      <p class="text-gray-400">Created {{ project.created_at }}</p>
    </div>

    <!-- Actions -->
    <div class="mt-5 flex justify-end">
      <Link
        :href="route('projects.show', project.id)"
        class="px-3 py-1.5 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700 transition"
      >
        View
      </Link>
    </div>
  </div>
</template>

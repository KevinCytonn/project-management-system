<script setup>
import { Link } from '@inertiajs/vue3';
import ProjectCard from './ProjectCard.vue';
import ManagerLayout from '@/Layouts/ManagerLayout.vue';

const props = defineProps({
  projects: Object
});
</script>

<template>
  <ManagerLayout>
    <div class="space-y-8">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-3xl font-semibold">Projects</h1>

        <Link
          :href="route('projects.create')"
          class="inline-flex items-center px-4 py-2 rounded-lg shadow-sm transition"
        >
          + New Project
        </Link>
      </div>

      <!-- Projects grid -->
      <div v-if="projects.data.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <ProjectCard
          v-for="project in projects.data"
          :key="project.id"
          :project="project"
        />
      </div>

      <div v-else class="text-center py-12 text-gray-500">
        No projects found.
      </div>

      <!-- Pagination -->
      <div
        v-if="projects.last_page > 1"
        class="mt-6 flex justify-center gap-2 flex-wrap"
      >
        <Link
          v-if="projects.current_page > 1"
          :href="route('projects.index', { page: projects.current_page - 1 })"
          class="px-3 py-1 rounded border"
        >
          Prev
        </Link>

        <Link
          v-for="p in projects.last_page"
          :key="p"
          :href="route('projects.index', { page: p })"
          class="px-3 py-1 rounded border"
          :class="{ 'bg-gray-900 text-white': p === projects.current_page }"
        >
          {{ p }}
        </Link>

        <Link
          v-if="projects.current_page < projects.last_page"
          :href="route('projects.index', { page: projects.current_page + 1 })"
          class="px-3 py-1 rounded border"
        >
          Next
        </Link>
      </div>
    </div>
  </ManagerLayout>
</template>

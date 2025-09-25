<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import ProjectCard from './ProjectCard.vue';
import ManagerLayout from '@/Layouts/ManagerLayout.vue';

const props = defineProps({
  projects: Object
});
const page = usePage();
</script>

<template>
    <ManagerLayout>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold">Projects</h1>
      <Link
        :href="route('projects.create')"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        + New Project
      </Link>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <ProjectCard
        v-for="project in projects?.data"
        :key="project.id"
        :project="project"
      />
    </div>

    <!-- Pagination -->
    <!-- <div class="mt-6 flex justify-center space-x-2">
      <Link
        v-for="link in projects.links"
        :key="link.label"
        :href="link.url"
        v-html="link.label"
        class="px-3 py-1 border rounded"
        :class="{
          'bg-blue-600 text-white': link.active,
          'text-gray-500 cursor-not-allowed': !link.url
        }"
      />
    </div> -->
  </div>
  </ManagerLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import StageProgress from '../Stages/StageProgress.vue';
import StageSummary from '../Stages/StageSummary.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  project: Object
});
</script>

<template>
  <AuthenticatedLayout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">
      <!-- Header -->
      <header class="border-b pb-6">
        <!-- Back button (mobile only) -->
        <div class="mb-4 md:hidden">
          <Link
            :href="route('projects.index')"
            class="inline-flex items-center px-3 py-1.5 text-sm rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition"
          >
            ← Back
          </Link>
        </div>

        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
          <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
              {{ project.name }}
            </h1>
            <p
              class="mt-4 text-gray-600 text-base leading-relaxed max-w-2xl"
            >
              {{ project.description || "No description provided." }}
            </p>
          </div>

          <!-- Back button (desktop only) -->
          <div class="hidden md:block shrink-0">
            <Link
              :href="route('projects.index')"
              class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition"
            >
              ← Back to Projects
            </Link>
          </div>
        </div>
      </header>

      <!-- Stage Info -->
      <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white shadow rounded-2xl p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Progress</h2>
          <StageProgress :project="project" />
        </div>

        <div class="bg-white shadow rounded-2xl p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Summary</h2>
          <StageSummary :project="project" />
        </div>
      </section>

      <!-- Tasks -->
      <section class="bg-white shadow rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-semibold text-gray-800">Tasks</h2>
          <Link
            :href="route('tasks.index', { project: project.id })"
            class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition"
          >
            View Tasks
          </Link>
        </div>
        <p class="text-sm text-gray-500 leading-relaxed">
          Track all tasks related to this project and monitor their progress
          across stages.
        </p>
      </section>
    </div>
  </AuthenticatedLayout>
</template>

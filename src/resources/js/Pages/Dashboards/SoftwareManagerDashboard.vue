
<template>
  <Head title="Development Manager Dashboard" />
  <AuthenticatedLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Development Stage Dashboard</h1>
          <p class="text-gray-600">Manage software development tasks and projects</p>
        </div>
        <div class="flex gap-3">
          <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
            Assign Development Tasks
          </button>
        </div>
      </div>

    
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-green-200">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Dev Projects</p>
              <p class="text-2xl font-bold text-green-600">{{ projects.length }}</p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">In Development</p>
              <p class="text-2xl font-bold text-blue-600">{{ inDevelopmentCount }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">In Testing</p>
              <p class="text-2xl font-bold text-yellow-600">{{ inTestingCount }}</p>
            </div>
            <div class="p-3 bg-yellow-100 rounded-full">
              <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Completed</p>
              <p class="text-2xl font-bold text-green-600">{{ completedCount }}</p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Development Projects and Tasks -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Development Projects -->
        <div class="bg-white rounded-lg shadow-sm border">
          <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Development Stage Projects</h2>
            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
              {{ projects.length }} projects
            </span>
          </div>
          <div class="divide-y">
            <div v-for="project in projects" :key="project.id" class="p-4 hover:bg-gray-50">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-medium text-gray-900">{{ project.name }}</h3>
                  <p class="text-sm text-gray-600 mt-1">Current phase: Development</p>
                </div>
                <span class="text-xs text-gray-500">{{ formatDate(project.updated_at) }}</span>
              </div>
              <div class="mt-3">
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                  <span>Development Progress</span>
                  <span>{{ getDevProgress(project) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div class="bg-green-600 h-2 rounded-full" :style="`width: ${getDevProgress(project)}%`"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Development Tasks -->
        <div class="bg-white rounded-lg shadow-sm border">
          <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Development Tasks</h2>
            <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">
              {{ tasks.length }} tasks
            </span>
          </div>
          <div class="divide-y">
            <div v-for="task in tasks" :key="task.id" class="p-4 hover:bg-gray-50">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-medium text-gray-900">{{ task.title }}</h3>
                  <p class="text-sm text-gray-600 mt-1">Project: {{ task.project?.name }}</p>
                </div>
                <span :class="statusBadgeClass(task.status)" class="text-xs px-2 py-1 rounded-full">
                  {{ task.status }}
                </span>
              </div>
              <div class="mt-2 flex justify-between items-center">
                <span class="text-sm text-gray-600">
                  Developer: {{ task.assignee?.name || 'Unassigned' }}
                </span>
                <div class="flex items-center space-x-2">
                  <span v-if="task.due_date" class="text-xs text-gray-500">{{ formatDate(task.due_date) }}</span>
                  <button class="text-green-600 hover:text-green-800 text-sm font-medium">
                    View
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed } from 'vue';

const props = defineProps({
  projects: Array,
  tasks: Array
});

const inDevelopmentCount = computed(() => {
  return props.tasks.filter(task => task.status === 'in_progress').length;
});

const inTestingCount = computed(() => {
  return props.tasks.filter(task => task.status === 'testing').length;
});

const completedCount = computed(() => {
  return props.tasks.filter(task => task.status === 'complete').length;
});

const getDevProgress = (project) => {
  
  return Math.floor(Math.random() * 100); 
};

const statusBadgeClass = (status) => {
  const classes = {
    not_started: 'bg-gray-100 text-gray-800',
    in_progress: 'bg-yellow-100 text-yellow-800',
    testing: 'bg-blue-100 text-blue-800',
    complete: 'bg-green-100 text-green-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

import formatDate from '@/Utils/formatDate';
</script>
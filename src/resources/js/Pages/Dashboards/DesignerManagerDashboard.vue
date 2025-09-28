<!-- resources/js/Pages/Dashboards/DesignerManagerDashboard.vue -->
<template>
  <Head title="Design Manager Dashboard" />
  <AuthenticatedLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Design Stage Dashboard</h1>
          <p class="text-gray-600">Manage design tasks and projects</p>
        </div>
        <div class="flex gap-3">
          <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm">
            Assign Design Tasks
          </button>
        </div>
      </div>

      <!-- Design Stage Overview -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-purple-200">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Design Stage Projects</p>
              <p class="text-2xl font-bold text-purple-600">{{ projects.length }}</p>
            </div>
            <div class="p-3 bg-purple-100 rounded-full">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Pending Design Tasks</p>
              <p class="text-2xl font-bold text-yellow-600">{{ pendingTasksCount }}</p>
            </div>
            <div class="p-3 bg-yellow-100 rounded-full">
              <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Ready for Development</p>
              <p class="text-2xl font-bold text-green-600">{{ readyForDevelopmentCount }}</p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Design Projects and Tasks -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Design Projects -->
        <div class="bg-white rounded-lg shadow-sm border">
          <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Design Stage Projects</h2>
            <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">
              {{ projects.length }} projects
            </span>
          </div>
          <div class="divide-y">
            <div v-for="project in projects" :key="project.id" class="p-4 hover:bg-gray-50">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-medium text-gray-900">{{ project.name }}</h3>
                  <p class="text-sm text-gray-600 mt-1">From Product: {{ project.creator?.name }}</p>
                </div>
                <span class="text-xs text-gray-500">{{ formatDate(project.updated_at) }}</span>
              </div>
              <div class="mt-3">
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                  <span>Design Progress</span>
                  <span>{{ getDesignProgress(project) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div class="bg-purple-600 h-2 rounded-full" :style="`width: ${getDesignProgress(project)}%`"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Design Tasks -->
        <div class="bg-white rounded-lg shadow-sm border">
          <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Design Tasks</h2>
            <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">
              {{ tasks.length }} tasks
            </span>
          </div>
          <div class="divide-y">
            <div v-for="task in tasks" :key="task.id" class="p-4 hover:bg-gray-50">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-medium text-gray-900">{{ task.title }}</h3>
                  <p class="text-sm text-gray-600 mt-1 capitalize">
                    Type: {{ task.type || 'design' }} • Project: {{ task.project?.name }}
                  </p>
                </div>
                <span :class="statusBadgeClass(task.status)" class="text-xs px-2 py-1 rounded-full">
                  {{ task.status }}
                </span>
              </div>
              <div class="mt-2 flex justify-between items-center">
                <span class="text-sm text-gray-600">
                  Designer: {{ task.assignee?.name || 'Unassigned' }}
                </span>
                <span class="text-xs text-gray-500">{{ formatDate(task.due_date) }}</span>
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

const pendingTasksCount = computed(() => {
  return props.tasks.filter(task => 
    task.status === 'not_started' || task.status === 'in_progress'
  ).length;
});

const readyForDevelopmentCount = computed(() => {
  return props.projects.filter(project => {
  
    return getDesignProgress(project) === 100;
  }).length;
});

const getDesignProgress = (project) => {
  
  return Math.floor(Math.random() * 100); 
};

const statusBadgeClass = (status) => {
  const classes = {
    not_started: 'bg-gray-100 text-gray-800',
    in_progress: 'bg-yellow-100 text-yellow-800',
    complete: 'bg-green-100 text-green-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};
import formatDate from '@/Utils/formatDate';
</script>
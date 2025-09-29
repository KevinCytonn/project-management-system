<template>
  <Head title="Admin Dashboard" />
  <AuthenticatedLayout>
    <div class="space-y-6">
     
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
          <p class="text-gray-600">Complete oversight of all projects and workflows</p>
        </div>
        <div class="flex gap-3">
          <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
            Generate Report
          </button>
        </div>
      </div>

      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Total Projects</p>
              <p class="text-2xl font-bold text-gray-900">{{ projects.length }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600"> Users</p>
              <p class="text-2xl font-bold text-gray-900">{{ users.length }}</p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Overdue Tasks</p>
              <p class="text-2xl font-bold text-red-600">{{ overdueTasksCount }}</p>
            </div>
            <div class="p-3 bg-red-100 rounded-full">
              <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Bottlenecks</p>
              <p class="text-2xl font-bold text-orange-600">{{ bottlenecksCount }}</p>
            </div>
            <div class="p-3 bg-orange-100 rounded-full">
              <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Projects -->
        <div class="bg-white rounded-lg shadow-sm border">
          <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Recent Projects</h2>
          </div>
          <div class="divide-y">
            <div v-for="project in projects" :key="project.id" class="p-4 hover:bg-gray-50">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-medium text-gray-900">{{ project.name }}</h3>
                  <p class="text-sm text-gray-600 mt-1">Stage: 
                    <span :class="stageBadgeClass(project.current_stage)">
                      {{ project.current_stage }}
                    </span>
                  </p>
                </div>
                <span class="text-xs text-gray-500">{{ formatDate(project.created_at) }}</span>
              </div>
              <div class="mt-2 flex items-center text-sm text-gray-600">
                <span>Created by: {{ project.creator?.name }}</span>
              </div>
            </div>
          </div>
        </div>

      
        <div class="bg-white rounded-lg shadow-sm border">
          <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Recent Tasks</h2>
          </div>
          <div class="divide-y">
            <div v-for="task in tasks" :key="task.id" class="p-4 hover:bg-gray-50">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-medium text-gray-900">{{ task.title }}</h3>
                  <p class="text-sm text-gray-600 mt-1">
                    Project: {{ task.project?.name }} • 
                    <span :class="statusBadgeClass(task.status)">{{ task.status }}</span>
                  </p>
                </div>
                <span class="text-xs text-gray-500">{{ formatDate(task.created_at) }}</span>
              </div>
              <div class="mt-2 flex justify-between items-center">
                <span class="text-sm text-gray-600">Assigned to: {{ task.assignee?.name || 'Unassigned' }}</span>
                <span :class="stageBadgeClass(task.stage)" class="text-xs px-2 py-1 rounded-full">
                  {{ task.stage }}
                </span>
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
  tasks: Array,
  users: Array
});

const overdueTasksCount = computed(() => {
  return props.tasks.filter(task => {
    return task.due_date && new Date(task.due_date) < new Date() && task.status !== 'complete';
  }).length;
});

const bottlenecksCount = computed(() => {
 
  const weekAgo = new Date();
  weekAgo.setDate(weekAgo.getDate() - 7);
  
  return props.tasks.filter(task => {
    return task.status === 'in_progress' && new Date(task.updated_at) < weekAgo;
  }).length;
});

const stageBadgeClass = (stage) => {
  const classes = {
    product: 'bg-blue-100 text-blue-800',
    design: 'bg-purple-100 text-purple-800',
    development: 'bg-green-100 text-green-800'
  };
  return classes[stage] || 'bg-gray-100 text-gray-800';
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
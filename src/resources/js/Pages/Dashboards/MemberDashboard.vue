
<template>
  <Head title="My Dashboard" />
  <AuthenticatedLayout>
    <div class="space-y-6">
     
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">My Tasks Dashboard</h1>
          <p class="text-gray-600">Overview of your assigned tasks and progress</p>
        </div>
        <div class="flex gap-3">
          <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
            Update Status
          </button>
        </div>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
          <p class="text-2xl font-bold text-gray-900">{{ summary.total }}</p>
          <p class="text-sm text-gray-600">Total Tasks</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
          <p class="text-2xl font-bold text-yellow-600">{{ summary.not_started }}</p>
          <p class="text-sm text-gray-600">Not Started</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
          <p class="text-2xl font-bold text-blue-600">{{ summary.in_progress }}</p>
          <p class="text-sm text-gray-600">In Progress</p>
        </div>
      <div 
  class="bg-white p-4 rounded-lg shadow-sm border text-center"
  v-if="tasks.some(task => task.stage === 'development')"
>
  <p class="text-2xl font-bold text-purple-600">{{ summary.testing || 0 }}</p>
  <p class="text-sm text-gray-600">Testing</p>
</div>
       
        <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
          <p class="text-2xl font-bold text-green-600">{{ summary.complete }}</p>
          <p class="text-sm text-gray-600">Completed</p>
        </div>
      </div>

      
      <div v-if="summary.overdue > 0" class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <span class="text-red-800 font-medium">You have {{ summary.overdue }} overdue task(s)</span>
        </div>
      </div>
     
      <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b">
          <h2 class="text-lg font-semibold text-gray-900">My Assigned Tasks</h2>
        </div>
        <div class="divide-y">
          <div v-for="task in tasks" :key="task.id" class="p-6 hover:bg-gray-50">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
              <div class="flex-1">
                <h3 class="font-medium text-gray-900 text-lg">{{ task.title }}</h3>
                <p class="text-gray-600 mt-1">{{ task.description }}</p>
                
                <div class="mt-3 flex flex-wrap gap-2">
                  <span :class="stageBadgeClass(task.stage)" class="text-xs px-2 py-1 rounded-full">
                    {{ task.stage }} Stage
                  </span>
                 
                  <span v-if="task.due_date && isOverdue(task)" class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                    Overdue
                  </span>
                </div>
              </div>
              
              <div class="flex flex-col sm:items-end gap-3">
                <span :class="statusBadgeClass(task.status)" class="text-sm px-3 py-1 rounded-full">
                  {{ task.status }}
                </span>
                
                <div class="flex gap-2">
                  <button class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-sm hover:bg-blue-200">
                    Update
                  </button>
                  <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded text-sm hover:bg-gray-200">
                    Details
                  </button>
                </div>
                
                <div class="text-sm text-gray-500 text-right">
                  <div>Project: {{ task.project?.name }}</div>
                  <div v-if="task.due_date">Due: {{ formatDate(task.due_date) }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div v-if="tasks.length === 0" class="p-12 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks assigned</h3>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  tasks: Array,
  summary: Object
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


const isOverdue = (task) => {
  if (!task.due_date || task.status === 'complete') return false;
  return new Date(task.due_date) < new Date();
};

import formatDate from '@/Utils/formatDate';
</script>
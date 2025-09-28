<template>
  <Head title="My Tasks" />
  <AuthenticatedLayout>
    <div class="space-y-6">
     
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">My Task Board</h1>
          <p class="text-gray-600 mt-1">Manage your assigned tasks and track progress</p>
        </div>
        <div class="flex items-center gap-3">
          <div class="text-sm text-gray-500 bg-gray-50 px-3 py-1 rounded-full">
            <span class="font-medium text-gray-700">{{ summary.complete }}/{{ summary.total }}</span> completed
          </div>
        </div>
      </div>

    
      <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200 text-center">
          <div class="text-2xl font-bold text-blue-700">{{ summary.total }}</div>
          <div class="text-sm text-blue-600 font-medium">Total Tasks</div>
        </div>
        
        <div 
          v-if="summary.not_started > 0"
          class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-xl border border-yellow-200 text-center"
        >
          <div class="text-2xl font-bold text-yellow-700">{{ summary.not_started }}</div>
          <div class="text-sm text-yellow-600 font-medium">Not Started</div>
        </div>
        
        <div 
          v-if="summary.in_progress > 0"
          class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200 text-center"
        >
          <div class="text-2xl font-bold text-blue-700">{{ summary.in_progress }}</div>
          <div class="text-sm text-blue-600 font-medium">In Progress</div>
        </div>
        
      
        <div 
          v-if="hasDevelopmentTestingTasks"
          class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl border border-purple-200 text-center"
        >
          <div class="text-2xl font-bold text-purple-700">{{ summary.testing || 0 }}</div>
          <div class="text-sm text-purple-600 font-medium">Testing</div>
        </div>
        
        <div 
          v-if="summary.complete > 0"
          class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl border border-green-200 text-center"
        >
          <div class="text-2xl font-bold text-green-700">{{ summary.complete }}</div>
          <div class="text-sm text-green-600 font-medium">Completed</div>
        </div>
      </div>

      <!-- Overdue Alert with Action -->
      <div v-if="summary.overdue > 0" class="bg-red-50 border border-red-200 rounded-xl p-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
              <span class="text-red-800 font-medium">You have {{ summary.overdue }} overdue task(s)</span>
              <p class="text-red-600 text-sm mt-1">Please update these tasks as soon as possible.</p>
            </div>
          </div>
          <Link 
            :href="route('my.tasks', { status: 'overdue' })"
            class="text-red-700 hover:text-red-900 text-sm font-medium whitespace-nowrap"
          >
            View Overdue 
          </Link>
        </div>
      </div>

      <!-- Task Cards with Enhanced Design -->
      <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <div
          v-for="task in tasks.data"
          :key="task.id"
          class="bg-white rounded-xl border-2 hover:border-blue-300 hover:shadow-lg transition-all duration-200 overflow-hidden"
          :class="{
            'border-green-200': task.status === 'complete',
            'border-gray-200': task.status !== 'complete'
          }"
        >
          <!-- Status Indicator Bar -->
          <div 
            class="h-1"
            :class="{
              'bg-gray-400': task.status === 'not_started',
              'bg-yellow-400': task.status === 'in_progress',
              'bg-blue-400': task.status === 'testing',
              'bg-green-400': task.status === 'complete'
            }"
          ></div>

          <div class="p-5">
            <!-- Task Header -->
            <div class="flex justify-between items-start mb-3">
              <h3 class="font-semibold text-gray-900 text-lg leading-tight pr-2">{{ task.name }}</h3>
              <span
                class="px-2 py-1 rounded-full text-xs font-medium capitalize shrink-0"
                :class="{
                  'bg-gray-100 text-gray-700': task.status === 'not_started',
                  'bg-yellow-100 text-yellow-700': task.status === 'in_progress',
                  'bg-blue-100 text-blue-700': task.status === 'testing',
                  'bg-green-100 text-green-700': task.status === 'complete'
                }"
              >
                {{ task.status.replace('_', ' ') }}
              </span>
            </div>

            <!-- Project Info -->
            <div class="flex items-center text-sm text-gray-600 mb-4">
              <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
              {{ task.project.name }}
            </div>

            
            <div class="space-y-2 text-sm">
              <div class="flex justify-between items-center">
                <span class="text-gray-500">Due Date:</span>
                <span 
                  class="font-medium"
                  :class="isOverdue(task) ? 'text-red-600' : 'text-gray-700'"
                >
                  {{ task.due_date || 'No due date' }}
                </span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-gray-500">Files:</span>
                <span class="font-medium text-gray-700">{{ task.deliverables_count }}</span>
              </div>
            </div>

          
            <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-100">
              <Link
                :href="route('projects.show', task.project.id)"
                class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center"
              >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                View Project
              </Link>
              <Link
                :href="route('tasks.edit', [task.project.id, task.id])"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium"
              >
                {{ task.status === 'complete' ? 'View Details' : 'Work on Task' }}
              </Link>
            </div>
          </div>
        </div>
      </div>

      
      <div v-if="tasks.data.length === 0" class="text-center py-16 bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl border-2 border-dashed border-gray-300">
        <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <h3 class="text-xl font-medium text-gray-900 mb-2">No tasks assigned yet</h3>
        <p class="text-gray-600 max-w-md mx-auto mb-6">When tasks are assigned to you, they will appear here for you to work on.</p>
        <Link
          :href="route('projects.index')"
          class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
          </svg>
          Browse Projects
        </Link>
      </div>

      
      <div v-if="tasks.last_page > 1" class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-sm text-gray-600">
          Showing {{ tasks.from }} to {{ tasks.to }} of {{ tasks.total }} tasks
        </div>
        <div class="flex gap-1">
          <Link
            v-if="tasks.current_page > 1"
            :href="route('my.tasks', { page: tasks.current_page - 1 })"
            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center"
          >
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Previous
          </Link>

          <Link
            v-for="page in tasks.last_page"
            :key="page"
            :href="route('my.tasks', { page })"
            class="px-4 py-2 border rounded-lg transition min-w-[40px] text-center"
            :class="page === tasks.current_page 
              ? 'bg-blue-600 text-white border-blue-600' 
              : 'border-gray-300 hover:bg-gray-50'"
          >
            {{ page }}
          </Link>

          <Link
            v-if="tasks.current_page < tasks.last_page"
            :href="route('my.tasks', { page: tasks.current_page + 1 })"
            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center"
          >
            Next
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </Link>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed } from 'vue';

const props = defineProps({
  tasks: Object,
  summary: Object,
  filters: Object
});


const hasDevelopmentTestingTasks = computed(() => {
  return props.tasks.data.some(task => task.stage === 'development' && task.status === 'testing');
});

const isOverdue = (task) => {
  if (!task.due_date || task.status === 'complete') return false;
  return new Date(task.due_date) < new Date();
};
</script>
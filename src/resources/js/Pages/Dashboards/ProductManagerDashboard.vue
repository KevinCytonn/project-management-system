<template>
  <Head title="Product Manager Dashboard" />
  <AuthenticatedLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Product Development Dashboard</h1>
          <p class="text-gray-600">Manage product stage projects and tasks</p>
        </div>
        <div class="flex gap-3">
          <Link 
            v-if="role === 'product_manager'"
            :href="route('projects.create')"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Project
          </Link>
        </div>
      </div>

      <!-- Stats Cards - Only show if there are values -->
      <div v-if="hasStats" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Product Stage Projects Card -->
        <!-- <div v-if="projects?.length > 0" class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-blue-200">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Product Stage Projects</p>
              <p class="text-2xl font-bold text-blue-600">{{ projects.length }}</p>
            </div>
            <div class="p-2 sm:p-3 bg-blue-100 rounded-full">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
          </div>
        </div> -->

        <!-- Active Tasks Card -->
        <div v-if="activeTasksCount > 0" class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-yellow-200">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Active Tasks</p>
              <p class="text-2xl font-bold text-yellow-600">{{ activeTasksCount }}</p>
            </div>
            <div class="p-2 sm:p-3 bg-yellow-100 rounded-full">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
          </div>
        </div>

        <!-- Ready for Next Stage Card -->
        <div v-if="readyForNextStageCount > 0" class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-green-200">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Ready for Next Stage</p>
              <p class="text-2xl font-bold text-green-600">{{ readyForNextStageCount }}</p>
            </div>
            <div class="p-2 sm:p-3 bg-green-100 rounded-full">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Product Stage Projects Section -->
        <div v-if="projects.length > 0" class="bg-white rounded-lg shadow-sm border">
          <div class="p-4 sm:p-6 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Product Stage Projects</h2>
            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
              {{ projects.length }} projects
            </span>
          </div>
          <div class="divide-y">
            <div 
              v-for="project in projects" 
              :key="project.id" 
              class="p-4 sm:p-6 hover:bg-gray-50 transition-colors"
            >
              <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                <div class="flex-1">
                  <h3 class="font-medium text-gray-900 text-sm sm:text-base">{{ project.name }}</h3>
                  <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ project.description }}</p>
                </div>
                <span class="text-xs text-gray-500 whitespace-nowrap">{{ formatDate(project.created_at) }}</span>
              </div>
              <div class="mt-3 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                <div class="flex items-center space-x-2">
                  <span class="text-sm text-gray-600 hidden xs:inline">Progress:</span>
                  <div class="w-20 sm:w-24 bg-gray-200 rounded-full h-2">
                    <div 
                      class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                      :style="`width: ${getProjectProgress(project)}%`"
                    ></div>
                  </div>
                  <span class="text-xs text-gray-600 whitespace-nowrap">{{ getProjectProgress(project) }}%</span>
                </div>
                <div class="flex justify-end">
                  <span 
                    :class="getProjectStatusClass(project)" 
                    class="text-xs px-2 py-1 rounded-full capitalize"
                  >
                    {{ getProjectStatus(project) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Tasks Section -->
        <div v-if="tasks.length > 0" class="bg-white rounded-lg shadow-sm border">
          <div class="p-4 sm:p-6 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Recent Product Tasks</h2>
            <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">
              {{ tasks.length }} tasks
            </span>
          </div>
          <div class="divide-y">
            <div 
              v-for="task in tasks" 
              :key="task.id" 
              class="p-4 sm:p-6 hover:bg-gray-50 transition-colors"
            >
              <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                <div class="flex-1">
                  <h3 class="font-medium text-gray-900 text-sm sm:text-base">{{ task.title }}</h3>
                  <p class="text-sm text-gray-600 mt-1" v-if="task.project?.name">
                    Project: {{ task.project.name }}
                  </p>
                </div>
                <span 
                  :class="statusBadgeClass(task.status)" 
                  class="text-xs px-2 py-1 rounded-full whitespace-nowrap mt-1 sm:mt-0"
                >
                  {{ formatStatus(task.status) }}
                </span>
              </div>
              <div class="mt-2 flex flex-col xs:flex-row justify-between items-start xs:items-center gap-1">
                <span class="text-sm text-gray-600">
                  Assigned to: {{ task.assignee?.name || 'Unassigned' }}
                </span>
                <span class="text-xs text-gray-500 whitespace-nowrap" v-if="task.due_date">
                  Due: {{ formatDate(task.due_date) }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty States -->
        <div v-if="projects.length === 0 && tasks.length === 0" class="col-span-2">
          <div class="bg-white rounded-lg shadow-sm border p-8 text-center">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No projects or tasks yet</h3>
            <p class="text-gray-600 mb-4">Get started by creating your first project</p>
            <Link 
              v-if="role === 'product_manager'"
              :href="route('projects.create')"
              class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Create First Project
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, usePage, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed } from 'vue';
import formatDate from '@/Utils/formatDate';

const props = defineProps({
  projects: Array,
  tasks: Array
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const role = computed(() => user.value.role_name?.toLowerCase());

const activeTasksCount = computed(() => {
  return props.tasks.filter(task => 
    task.status === 'in_progress' || task.status === 'not_started'
  ).length;
});

const readyForNextStageCount = computed(() => {
  return props.projects.filter(project => getProjectProgress(project) === 100).length;
});

const hasStats = computed(() => {
  return props.projects.length > 0 || activeTasksCount.value > 0 || readyForNextStageCount.value > 0;
});

const getProjectProgress = (project) => {
  const currentStageTasks = project.tasks?.filter(task => task.stage === project.current_stage) || [];
  
  if (currentStageTasks.length === 0) return 0;
  
  const completedTasks = currentStageTasks.filter(task => task.status === 'complete').length;
  return Math.round((completedTasks / currentStageTasks.length) * 100);
};

const getProjectStatus = (project) => {
  const progress = getProjectProgress(project);
  if (progress === 100) return 'ready';
  if (progress > 0) return 'in progress';
  return 'not started';
};

const getProjectStatusClass = (project) => {
  const status = getProjectStatus(project);
  const classes = {
    'ready': 'bg-green-100 text-green-800',
    'in progress': 'bg-yellow-100 text-yellow-800',
    'not started': 'bg-gray-100 text-gray-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const statusBadgeClass = (status) => {
  const classes = {
    'not_started': 'bg-gray-100 text-gray-800',
    'in_progress': 'bg-yellow-100 text-yellow-800',
    'complete': 'bg-green-100 text-green-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const formatStatus = (status) => {
  const statusMap = {
    'not_started': 'Not Started',
    'in_progress': 'In Progress',
    'complete': 'Complete'
  };
  return statusMap[status] || status;
};
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Responsive breakpoints */
@media (max-width: 480px) {
  .xs\:inline {
    display: inline;
  }
}
</style>
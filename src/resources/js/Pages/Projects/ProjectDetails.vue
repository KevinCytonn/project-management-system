<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import StageProgress from '../Stages/StageProgress.vue';
import StageSummary from '../Stages/StageSummary.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const role = computed(() => user.value.role_name?.toLowerCase());

const props = defineProps({
  project: Object,
  tasks: Array
});

const activeTab = ref('overview');

// Check if current user can add tasks to this project stage
const canAddTasks = computed(() => {
  if (props.project.current_stage === 'completed') return false;

  return (role.value === 'product_manager' && props.project.current_stage === 'product') ||
    (role.value === 'design_manager' && props.project.current_stage === 'design') ||
    (role.value === 'development_manager' && props.project.current_stage === 'development');
});

// Filter tasks by current stage
const currentStageTasks = computed(() => {
  return props.tasks.filter(task => task.stage === props.project.current_stage);
});

// Check if user has tasks in this project
const userTasksInProject = computed(() => {
  return props.tasks.filter(task => task.assigned_to === user.value.id);
});
</script>

<template>
  <AuthenticatedLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
      <!-- Breadcrumb -->
      <nav class="flex items-center space-x-2 text-sm text-gray-500">
        <Link :href="route('dashboard')" class="hover:text-gray-700">Dashboard</Link>
        <span>→</span>
        <Link :href="route('projects.index')" class="hover:text-gray-700">Projects</Link>
        <span>→</span>
        <span class="text-gray-900 font-medium">{{ project.name }}</span>
      </nav>

      <!-- Project Header -->
      <div class="bg-white rounded-lg border p-6">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
          <div class="flex-1">
            <div class="flex items-center gap-3 mb-2">
              <h1 class="text-2xl font-bold text-gray-900">{{ project.name }}</h1>
              <span class="px-3 py-1 rounded-full text-sm font-medium capitalize" :class="{
                'bg-yellow-100 text-yellow-800': project.current_stage === 'product',
                'bg-purple-100 text-purple-800': project.current_stage === 'design',
                'bg-green-100 text-green-800': project.current_stage === 'development',
                'bg-gray-100 text-gray-800': project.current_stage === 'completed'
              }">
                {{ project.current_stage }} Stage
              </span>
            </div>
            <p class="text-gray-600">{{ project.description || "No description provided." }}</p>
          </div>

          <div class="flex flex-col sm:flex-row gap-3">
            <Link v-if="canAddTasks" :href="route('tasks.create', project.id)"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm text-center">
            Add Task to Project
            </Link>
            <Link :href="route('projects.index')"
              class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm text-center">
            Back to Projects
            </Link>
          </div>
        </div>
      </div>

      <!-- Stage Progress -->
      <StageProgress :project="project" />

      <!-- Tabs -->
      <div class="border-b border-gray-200">
        <nav class="flex space-x-8">
          <button @click="activeTab = 'overview'" class="py-4 px-1 border-b-2 font-medium text-sm transition-colors"
            :class="activeTab === 'overview'
              ? 'border-blue-500 text-blue-600'
              : 'border-transparent text-gray-500 hover:text-gray-700'">
            Overview
          </button>
          <button @click="activeTab = 'tasks'" class="py-4 px-1 border-b-2 font-medium text-sm transition-colors"
            :class="activeTab === 'tasks'
              ? 'border-blue-500 text-blue-600'
              : 'border-transparent text-gray-500 hover:text-gray-700'">
            Project Tasks ({{ tasks.length }})
          </button>
          <button v-if="userTasksInProject.length > 0" @click="activeTab = 'my-tasks'"
            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors" :class="activeTab === 'my-tasks'
              ? 'border-blue-500 text-blue-600'
              : 'border-transparent text-gray-500 hover:text-gray-700'">
            My Tasks ({{ userTasksInProject.length }})
          </button>
        </nav>
      </div>

      <!-- Tab Content -->
      <div>
        <!-- Overview Tab -->
        <div v-if="activeTab === 'overview'">
          <StageSummary :project="project" />
        </div>

        <!-- All Project Tasks Tab -->
        <div v-if="activeTab === 'tasks'" class="space-y-6">
          <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
              All Project Tasks ({{ tasks.length }})
            </h2>

            <div v-if="tasks.length" class="space-y-4">
              <div v-for="task in tasks" :key="task.id" class="border rounded-lg p-4 hover:bg-gray-50 transition">
                <div class="flex justify-between items-start mb-2">
                  <h3 class="font-semibold text-gray-900">{{ task.title }}</h3>
                  <div class="flex gap-2">
                    <span class="px-2 py-1 rounded text-xs font-medium capitalize" :class="{
                      'bg-gray-100 text-gray-800': task.status === 'not_started',
                      'bg-yellow-100 text-yellow-800': task.status === 'in_progress',
                      'bg-blue-100 text-blue-800': task.status === 'testing',
                      'bg-green-100 text-green-800': task.status === 'complete'
                    }">
                      {{ task.status.replace('_', ' ') }}
                    </span>
                    <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800 capitalize">
                      {{ task.stage }}
                    </span>
                  </div>
                </div>

                <p class="text-sm text-gray-600 mb-3">{{ task.description || 'No description' }}</p>

                <div class="flex justify-between items-center text-sm text-gray-500">
                  <span>Assigned to: {{ task.assignee?.name || 'Unassigned' }}</span>
                  <span v-if="task.due_date">Due: {{ task.due_date }}</span>
                </div>

                <div class="flex justify-between items-center mt-3 pt-3 border-t">
                  <Link v-if="task.assigned_to === user.id && task.status !== 'complete'"
                    :href="route('tasks.edit', [project.id, task.id])"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                  Update Status
                  </Link>
                  <Link :href="route('tasks.edit', [project.id, task.id])"
                    class="text-gray-600 hover:text-gray-800 text-sm">
                  View Details
                  </Link>
                </div>
              </div>
            </div>

            <div v-else class="text-center py-8 text-gray-500">
              <p>No tasks created for this project yet.</p>
              <Link v-if="canAddTasks" :href="route('tasks.create', project.id)"
                class="mt-2 inline-block text-blue-600 hover:text-blue-800 text-sm font-medium">
              Create first task
              </Link>
            </div>
          </div>
        </div>

       
        <div v-if="activeTab === 'my-tasks'" class="space-y-6">
          <div class="bg-white rounded-lg border p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
              My Tasks in This Project ({{ userTasksInProject.length }})
            </h2>

            <div class="space-y-4">
              <div v-for="task in userTasksInProject" :key="task.id"
                class="border rounded-lg p-4 hover:bg-gray-50 transition">
                <div class="flex justify-between items-start mb-2">
                  <h3 class="font-semibold text-gray-900">{{ task.title }}</h3>
                  <span class="px-2 py-1 rounded text-xs font-medium capitalize" :class="{
                    'bg-gray-100 text-gray-800': task.status === 'not_started',
                    'bg-yellow-100 text-yellow-800': task.status === 'in_progress',
                    'bg-blue-100 text-blue-800': task.status === 'testing',
                    'bg-green-100 text-green-800': task.status === 'complete'
                  }">
                    {{ task.status.replace('_', ' ') }}
                  </span>
                </div>

                <p class="text-sm text-gray-600 mb-3">{{ task.description || 'No description' }}</p>

                <div class="flex justify-between items-center text-sm text-gray-500">
                  <span>Stage: {{ task.stage }}</span>
                  <span v-if="task.due_date">Due: {{ task.due_date }}</span>
                </div>

                <!-- In the task actions section -->
                <div class="flex justify-between items-center mt-3 pt-3 border-t">
                  <Link v-if="task.assigned_to === user.id" :href="route('tasks.edit', [project.id, task.id])"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                  {{ task.status === 'complete' ? 'View Details' : 'Update Task' }}
                  </Link>
                  <Link v-else-if="['product_manager', 'design_manager', 'development_manager'].includes(role)"
                    :href="route('tasks.edit', [project.id, task.id])"
                    class="text-gray-600 hover:text-gray-800 text-sm">
                  Edit Task
                  </Link>
                  <span v-else class="text-gray-400 text-sm">Not assigned to you</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
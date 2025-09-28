<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import TaskStatusUpdater from './TaskStatusUpdater.vue';

const props = defineProps({
  task: Object,
  project: Object
});

const showModal = ref(false);


const isOverdue = (task) => {
  if (!task.due_date || task.status === 'complete') return false;
  return new Date(task.due_date) < new Date();
};
</script>

<template>
  <div class="border border-gray-200 rounded-xl bg-white shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
   
   

    <div class="p-5">
      
      <div class="flex justify-between items-start mb-3">
        <h2 class="font-semibold text-gray-900 text-lg leading-tight pr-2">
          {{ task.title }}
        </h2>
      
      </div>

     
      <p class="text-gray-600 text-sm line-clamp-2 mb-4 leading-relaxed">
        {{ task.description || 'No description provided.' }}
      </p>

      
      <div class="mb-4">
        <TaskStatusUpdater :task="task" :project="project" />
      </div>

    
      <div class="space-y-2 text-sm">
        <div class="flex justify-between items-center">
          <span class="text-gray-500">Stage:</span>
          <span class="font-medium capitalize text-gray-700">{{ task.stage }}</span>
        </div>
        
        <div class="flex justify-between items-center">
          <span class="text-gray-500">Assignee:</span>
          <span class="font-medium text-gray-700">{{ task.assignee?.name || 'Unassigned' }}</span>
        </div>

        <div class="flex justify-between items-center">
          <span class="text-gray-500">Due Date:</span>
          <span 
            class="font-medium"
            :class="isOverdue(task) ? 'text-red-600' : 'text-gray-700'"
          >
            {{ task.due_date ? new Date(task.due_date).toLocaleDateString() : 'No due date' }}
          </span>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-100">
        <button
          v-if="task.description && task.description.length > 100"
          @click="showModal = true"
          class="text-blue-600 hover:text-blue-800 text-sm font-medium transition"
        >
          View Details
        </button>
        <div class="flex gap-2 ml-auto">
          <Link
            :href="route('tasks.edit', [project.id, task.id])"
            class="px-3 py-1.5 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
          >
            Edit
          </Link>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click="showModal = false"
    >
      <div 
        class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
        @click.stop
      >
        <div class="p-6">
          <div class="flex justify-between items-start mb-4">
            <h3 class="text-xl font-bold text-gray-900">{{ task.title }}</h3>
            <button
              @click="showModal = false"
              class="text-gray-400 hover:text-gray-600 transition"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <div class="space-y-4">
            <div>
              <h4 class="font-medium text-gray-900 mb-2">Description</h4>
              <p class="text-gray-700 whitespace-pre-line">{{ task.description }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
              <div>
                <span class="text-gray-500">Status:</span>
                <span class="ml-2 font-medium capitalize">{{ task.status }}</span>
              </div>
            
              <div>
                <span class="text-gray-500">Stage:</span>
                <span class="ml-2 font-medium capitalize">{{ task.stage }}</span>
              </div>
              <div>
                <span class="text-gray-500">Assignee:</span>
                <span class="ml-2 font-medium">{{ task.assignee?.name || 'Unassigned' }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
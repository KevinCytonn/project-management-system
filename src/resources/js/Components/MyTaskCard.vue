<script setup>
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import TaskStatusSelect from './TaskStatusSelect.vue';
import FileUpload from './FileUpload.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  task: Object
});

const showDescription = ref(false);
const showStatusModal = ref(false);

function updateStatus(newStatus) {
  router.put(route('tasks.update', [props.task.project_id, props.task.id]), {
    status: newStatus
  }, { preserveScroll: true });
}
</script>

<template>
  <div class="border rounded-lg bg-white shadow hover:shadow-lg transition p-5 flex flex-col justify-between">
    <!-- Title -->
    <h2 class="font-semibold text-lg text-gray-800">Title: {{ task.title }}</h2>

    <p>Due: {{ task.due_date }}</p>
    <p class="text-sm text-gray-600 mt-1 line-clamp-2">
      Description: {{ task.description || 'No description provided.' }}
    </p>

    <button
      v-if="task.description && task.description.length > 100"
      @click="showDescription = true"
      class="text-xs text-blue-600 hover:underline mt-1"
    >
      View full description
    </button>

    <!-- Status -->
    <div class="mt-4 flex items-center gap-2">
      <label class="text-sm font-medium text-gray-700">Status:</label>
      <button
        @click="showStatusModal = true"
        class="ml-2 px-3 py-1 rounded-full text-xs font-semibold
               bg-blue-600 text-white shadow-sm hover:bg-blue-700
               focus:outline-none focus:ring-2 focus:ring-blue-400
               flex items-center gap-1 transition"
      >
        {{ task.status.replace('_', ' ') }}
        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
        </svg>
      </button>
    </div>

    
    <div v-if="['in_progress','testing'].includes(task.status)" class="mt-4">
      <FileUpload :taskId="task.id" />
    </div>

   
    <Modal v-model:show="showDescription" maxWidth="lg">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold"> {{ task.title }}</h3>
        <button @click="showDescription = false" class="text-gray-500 hover:text-gray-700">
          x
        </button>
      </div>
      <p class="whitespace-pre-line text-gray-700">{{ task.description }}</p>
    </Modal>

 
    <Modal v-model:show="showStatusModal" maxWidth="sm">
      <div class="flex justify-between items-center mb-4 px-4">
        <h3 class="text-lg font-semibold">Update Task Status</h3>
        <button @click="showStatusModal = false" class="text-gray-500 hover:text-gray-700">
          x
        </button>
      </div>
      <TaskStatusSelect :value="task.status" @change="updateStatus" />
    </Modal>
  </div>
</template>

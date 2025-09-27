<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  task: Object,
  project: Object
});

const showModal = ref(false);
</script>

<template>
  <div class="border rounded-lg bg-white shadow hover:shadow-md transition p-5 flex flex-col justify-between">
    
    <div>
      <h2 class="font-semibold text-lg text-gray-800 mb-1">
        {{ task.title }}
      </h2>

      <p class="text-sm text-gray-600 line-clamp-3">
        {{ task.description || 'No description provided.' }}
      </p>

    
      <button
        v-if="task.description && task.description.length > 100"
        @click="showModal = true"
        class="text-xs text-blue-600 hover:underline mt-1"
      >
        View full description
      </button>
    </div>

    
    <div class="mt-4 space-y-1 text-sm">
      <p>
        Status:
        <span
          class="ml-1 px-2 py-0.5 rounded-full text-xs font-medium"
          :class="{
            'bg-yellow-100 text-yellow-800': task.status === 'not_started',
            'bg-blue-100 text-blue-800': task.status === 'in_progress',
            'bg-purple-100 text-purple-800': task.status === 'testing',
            'bg-green-100 text-green-800': task.status === 'complete'
          }"
        >
          {{ task.status.replace('_', ' ') }}
        </span>
      </p>

      <p>
        Assigned to:
        <span class="font-medium">{{ task.assignee?.name || 'Unassigned' }}</span>
      </p>

      <p v-if="task.due_date" class="text-gray-500">
        Due: <span class="font-medium">{{ task.due_date }}</span>
      </p>
    </div>

    <!-- Actions -->
    <div class="mt-5 flex space-x-4 text-sm">
      <Link
        :href="route('tasks.edit', [project.id, task.id])"
        class="text-blue-600 hover:underline"
      >
        Edit
      </Link>
    </div>

    <!-- Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative">
        <!-- Close button -->
        <button
          @click="showModal = false"
          class="absolute top-3 right-3 text-gray-500 hover:text-gray-700"
        >
          x
        </button>

        <h3 class="text-lg font-semibold text-gray-800 mb-4">
          {{ task.title }}
        </h3>

        <p class="text-sm text-gray-700 whitespace-pre-line">
          {{ task.description }}
        </p>
      </div>
    </div>
  </div>
</template>

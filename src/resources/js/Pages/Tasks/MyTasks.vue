<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  tasks: Array,
  projectMap: Object
});

function updateStatus(task, newStatus) {
  const form = useForm({ status: newStatus });
  form.put(route('tasks.update', [task.project_id, task.id]), {
    preserveScroll: true,
  });
}

function countByStatus(status) {
  return props.tasks.filter(t => t.status === status).length;
}
</script>

<template>
  <div class="p-6 space-y-6">
    <!-- Header with summary -->
    <div class="bg-white border rounded-xl shadow-sm p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-xl font-semibold text-gray-900">My Tasks</h1>
        <p class="text-sm text-gray-600">Track and update the status of your assigned tasks.</p>
      </div>
      <div class="flex gap-4 mt-3 sm:mt-0 text-sm">
        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700">
          In Progress: {{ countByStatus('in_progress') }}
        </span>
        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">
          Completed: {{ countByStatus('complete') }}
        </span>
        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700">
          Not Started: {{ countByStatus('not_started') }}
        </span>
      </div>
    </div>

    <!-- Empty state -->
    <div v-if="!tasks.length" class="text-center py-12 text-gray-500 border rounded-lg bg-gray-50">
      <p>You currently have no assigned tasks.</p>
    </div>

    <!-- Table for tasks -->
    <div v-else class="overflow-x-auto bg-white border rounded-xl shadow-sm">
      <table class="w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-100 text-gray-800 text-sm">
          <tr>
            <th class="px-4 py-3">Task</th>
            <th class="px-4 py-3">Project</th>
            <th class="px-4 py-3">Due Date</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Update</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="task in tasks" :key="task.id" class="border-t hover:bg-gray-50">
            <td class="px-4 py-3">
              <div class="font-medium text-gray-900">{{ task.title }}</div>
              <div class="text-xs text-gray-500 truncate max-w-xs">{{ task.description }}</div>
            </td>
            <td class="px-4 py-3">
              {{ projectMap?.[task.project_id]?.name || task.project?.name }}
            </td>
            <td class="px-4 py-3">
              <span v-if="task.due_date">
                {{ new Date(task.due_date).toLocaleDateString() }}
              </span>
              <span v-else class="text-gray-400">No deadline</span>
            </td>
            <td class="px-4 py-3">
              <span
                class="px-2 py-1 rounded-full text-xs font-medium"
                :class="{
                  'bg-gray-100 text-gray-700': task.status === 'not_started',
                  'bg-blue-100 text-blue-700': task.status === 'in_progress',
                  'bg-purple-100 text-purple-700': task.status === 'testing',
                  'bg-green-100 text-green-700': task.status === 'complete',
                }"
              >
                {{ task.status.replace('_', ' ') }}
              </span>
            </td>
            <td class="px-4 py-3">
              <select
                class="w-full border rounded-md p-2 text-sm focus:ring-2 focus:ring-blue-500"
                :value="task.status"
                @change="e => updateStatus(task, e.target.value)"
              >
                <option value="not_started">Not Started</option>
                <option value="in_progress">In Progress</option>
                <option v-if="task.stage === 'development'" value="testing">Testing</option>
                <option value="complete">Complete</option>
              </select>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

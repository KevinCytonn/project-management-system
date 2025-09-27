<script setup>
import MyTaskCard  from '@/Components/MyTaskCard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  tasks: Array,
  summary: Object
});
</script>

<template>
  <AuthenticatedLayout>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-800">My Tasks</h1>
      <div class="text-sm text-gray-500">
        Total: {{ summary.total }} | Complete: {{ summary.complete }}
      </div>
    </div>

    <!-- Empty State -->
    <div
      v-if="tasks.length === 0"
      class="p-6 border rounded-lg bg-gray-50 text-center text-gray-500"
    >
      You don't have any tasks assigned yet.
    </div>

    <!-- Task List -->
    <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <MyTaskCard
        v-for="task in tasks"
        :key="task.id"
        :task="task"
      />
    </div>
  </div>
  </AuthenticatedLayout>
</template>

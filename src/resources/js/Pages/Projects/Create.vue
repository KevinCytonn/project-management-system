<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  description: '',
  requires_design: false
});

const submit = () => {
  form.post('/projects');
};
</script>

<template>
  <Head title="Create Project" />

  <div class="p-6 max-w-lg mx-auto bg-white shadow rounded-lg">
    <h2 class="text-xl font-semibold mb-4">Create Project</h2>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block text-sm font-medium">Name</label>
        <input v-model="form.name" type="text" class="mt-1 block w-full border rounded px-3 py-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium">Description</label>
        <textarea v-model="form.description" class="mt-1 block w-full border rounded px-3 py-2"></textarea>
      </div>

      <div class="flex items-center">
        <input v-model="form.requires_design" type="checkbox" class="mr-2">
        <label class="text-sm">Requires Design Stage</label>
      </div>

      <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Create</button>
    </form>
  </div>
</template>

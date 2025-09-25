<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  description: '',
  requires_design: false
});

function submit() {
  form.post(route('projects.store'));
}
</script>

<template>
  <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-4">Create Project</h1>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block text-sm font-medium">Name</label>
        <input v-model="form.name" type="text" class="w-full border rounded p-2" />
        <div v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</div>
      </div>

      <div>
        <label class="block text-sm font-medium">Description</label>
        <textarea v-model="form.description" class="w-full border rounded p-2" />
      </div>

      <div class="flex items-center space-x-2">
        <input type="checkbox" v-model="form.requires_design" id="requires_design" />
        <label for="requires_design">Requires Design Stage?</label>
      </div>

      <div class="flex justify-end space-x-3">
        <Link :href="route('projects.index')" class="px-4 py-2 text-gray-600">Cancel</Link>
        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >Save</button>
      </div>
    </form>
  </div>
</template>

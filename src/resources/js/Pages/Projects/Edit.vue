<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

const project = usePage().props.project;

const form = useForm({
  name: project.name,
  description: project.description,
  requires_design: project.requires_design,
  current_stage: project.current_stage,
});
</script>

<template>
  <Head title="Edit Project" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">Edit Project</h2>
    </template>

    <div class="max-w-xl mx-auto bg-white shadow rounded-lg p-6 mt-6">
      <form @submit.prevent="form.put(route('projects.update', project.id))" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Name</label>
          <input
            v-model="form.name"
            type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          />
          <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Description</label>
          <textarea
            v-model="form.description"
            rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          ></textarea>
          <span v-if="form.errors.description" class="text-red-500 text-sm">{{ form.errors.description }}</span>
        </div>

        <div class="flex items-center">
          <input
            type="checkbox"
            v-model="form.requires_design"
            id="requires_design"
            class="h-4 w-4 text-indigo-600 border-gray-300 rounded"
          />
          <label for="requires_design" class="ml-2 text-sm text-gray-700">Requires Design</label>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Stage</label>
          <select
            v-model="form.current_stage"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          >
            <option value="product">Product</option>
            <option value="design">Design</option>
            <option value="development">Development</option>
            <option value="completed">Completed</option>
          </select>
          <span v-if="form.errors.current_stage" class="text-red-500 text-sm">{{ form.errors.current_stage }}</span>
        </div>

        <button
          type="submit"
          class="w-full px-4 py-2 bg-green-600 text-white rounded-md shadow hover:bg-green-700"
          :disabled="form.processing"
        >
          Update Project
        </button>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

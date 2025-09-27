<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
const props = defineProps({
  project: Object,
  members: Array
});

const form = useForm({
  title: '',
  description: '',
  assigned_to: '',
  stage: props.project.current_stage,
  status: 'not_started',
  due_date: ''
});//bg-blue-600

function submit() {
  form.post(route('tasks.store', props.project.id));
}
</script>

<template>
  <AuthenticatedLayout>
  <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-lg font-bold mb-4">Create Task</h1>
  
    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block text-sm">Title</label>
        <input v-model="form.title" type="text" class="w-full border rounded p-2" />
        <div v-if="form.errors.title" class="text-red-600 text-sm">{{ form.errors.title }}</div>
      </div>

      <div>
        <label class="block text-sm">Description</label>
        <textarea v-model="form.description" class="w-full border rounded p-2"></textarea>
      </div>

      <div>
        <label class="block text-sm">Assign To</label>
        <select v-model="form.assigned_to" class="w-full border rounded p-2">
          <option disabled value="">Select Member</option>
          <option v-for="m in members" :key="m.id" :value="m.id">{{ m.name }}</option>
        </select>
      </div>

      <div>
        <label class="block text-sm">Due Date</label>
        <input type="date" v-model="form.due_date" class="w-full border rounded p-2" />
      </div>

      <div class="flex justify-end space-x-3">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-green-700">
          Save Task
        </button>
      </div>
    </form>
  </div>
  </AuthenticatedLayout>
</template>

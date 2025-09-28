<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
  task: Object,
  members: Array
});

const form = useForm({
  title: props.task.title,
  description: props.task.description,
  assigned_to: props.task.assigned_to,
  stage: props.task.stage,
  status: props.task.status,
  due_date: props.task.due_date
});

const submit = () => {
  form.put(
    route('tasks.update', { 
      project: props.task.project_id, 
      task: props.task.id 
    })
  );
};

</script>

<template>
  <Head :title="`Edit Task ${task.title}`" />

  <div class="p-6 max-w-lg mx-auto bg-white shadow rounded-lg">
    <h2 class="text-xl font-semibold mb-4">Edit Task</h2>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block text-sm font-medium">Title</label>
        <input v-model="form.title" type="text" class="mt-1 block w-full border rounded px-3 py-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium">Description</label>
        <textarea v-model="form.description" class="mt-1 block w-full border rounded px-3 py-2"></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium">Assign To</label>
        <select v-model="form.assigned_to" class="mt-1 block w-full border rounded px-3 py-2" required>
          <option value="">Select member</option>
          <option v-for="member in members" :key="member.id" :value="member.id">{{ member.name }}</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium">Stage</label>
        <select v-model="form.stage" class="mt-1 block w-full border rounded px-3 py-2" required>
          <option value="product">Product Development</option>
          <option v-if="task.stage === 'design'" value="design">Design</option>
          <option value="development">Development</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium">Status</label>
        <select v-model="form.status" class="mt-1 block w-full border rounded px-3 py-2" required>
          <option value="not_started">Not Started</option>
          <option value="in_progress">In Progress</option>
          <option value="complete">Complete</option>
          <option value="testing" v-if="form.stage === 'development'">Testing</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium">Due Date</label>
        <input v-model="form.due_date" type="date" class="mt-1 block w-full border rounded px-3 py-2">
      </div>

      <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update Task</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  task: Object,
  deliverables: Array
});

const form = useForm({
  file: null
});

const submit = () => {
  const data = new FormData();
  data.append('file', form.file);
  form.post(`/tasks/${props.task.id}/deliverables`, { data });
};
</script>

<template>
  <div class="p-4 bg-white shadow rounded-lg mt-4">
    <h3 class="font-medium mb-2">Deliverables</h3>

    <form @submit.prevent="submit" class="flex gap-2 items-center">
      <input type="file" @change="e => form.file = e.target.files[0]" class="border rounded px-2 py-1">
      <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Upload</button>
    </form>

    <ul class="mt-2 space-y-1">
      <li v-for="d in deliverables" :key="d.id">
        <a :href="`/deliverables/${d.id}/download`" class="text-blue-600 hover:underline">{{ d.file_path.split('/').pop() }}</a>
      </li>
    </ul>
  </div>
</template>

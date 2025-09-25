<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  task: Object,
  project: Object
});

const form = useForm({
  status: props.task.status
});

function update() {
    console.log(task)
  form.put(route('tasks.update', [props.project.id, props.task.id]), {
    preserveScroll: true
  });
}
</script>

<template>
  <select v-model="form.status" @change="update" class="border rounded p-1 text-sm">
    <option value="not_started">Not Started</option>
    <option value="in_progress">In Progress</option>
    <option value="complete">Complete</option>
    <option v-if="props.task.stage === 'development'" value="testing">Testing</option>
  </select>
</template>

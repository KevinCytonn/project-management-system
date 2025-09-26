<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  taskId: Number
});

const fileInput = ref(null);
const isDragging = ref(false);

function handleFiles(files) {
  const form = new FormData();
  form.append('file', files[0]);
  form.append('task_id', props.taskId);

router.post(route('deliverables.store', props.taskId), form, {
  forceFormData: true,
  preserveScroll: true
});
}
</script>

<template>
  <div
    class="border-2 border-dashed rounded-lg p-4 text-center cursor-pointer"
    :class="isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300'"
    @dragover.prevent="isDragging = true"
    @dragleave.prevent="isDragging = false"
    @drop.prevent="isDragging = false; handleFiles($event.dataTransfer.files)"
    @click="fileInput.click()"
  >
    <p class="text-sm text-gray-500">Drag & drop file here, or click to upload</p>
    <input
      type="file"
      class="hidden"
      ref="fileInput"
      @change="handleFiles($event.target.files)"
    />
  </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
  projects: Object, // paginated collection
});
const formatStage = (stage) => {
  switch(stage) {
    case 'product': return 'Product Development';
    case 'design': return 'Design';
    case 'development': return 'Development';
    case 'completed': return 'Completed';
    default: return stage;
  }
};
</script>

<template>
  <Head title="Projects" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Projects</h2>
        <Link
          :href="route('projects.create')"
          class="px-3 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700"
        >
          + New Project
        </Link>
      </div>
    </template>

    <div class="p-6">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold">Projects</h2>
      <Link href="/projects/create" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Create Project</Link>
    </div>

    <div class="space-y-4">
      <div v-for="project in projects.data" :key="project.id" class="p-4 bg-white shadow rounded-lg">
        <div class="flex justify-between items-center mb-2">
          <div>
            <h3 class="text-lg font-medium">{{ project.name }}</h3>
            <p class="text-sm text-gray-500">{{ project.description }}</p>
          </div>
          <Link :href="`/projects/${project.id}/edit`" class="text-blue-600 hover:underline">Edit</Link>
        </div>
     
        <!-- Progress bar -->
        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
          <div
            class="h-2 rounded-full bg-green-500"
            :style="{
              width: project.current_stage === 'product' ? '25%' :
                     project.current_stage === 'design' ? '50%' :
                     project.current_stage === 'development' ? '75%' :
                     project.current_stage === 'completed' ? '100%' : '0%'
            }"
          ></div>
        </div>
        <p class="text-xs text-gray-500">{{ formatStage(project.current_stage) }}</p>

        <Link :href="route('tasks.index', project.id)" class="mt-2 inline-block text-blue-600 hover:underline">View Tasks</Link>
      </div>
    </div>
  </div>
  </AuthenticatedLayout>
</template>

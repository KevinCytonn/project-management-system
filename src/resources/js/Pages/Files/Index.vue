<script setup>
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    files: Array
});

const viewFile = (fileId) => {
  
    window.open(route('deliverables.view', fileId), '_blank');
};

const downloadFile = (fileId) => {
  window.location.href = route('deliverables.download', fileId);
};

</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
           
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">All Uploaded Files</h1>
                <p class="text-gray-600">View all deliverables across all projects</p>
            </div>

          
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-semibold text-gray-900">Project Deliverables</h2>
                </div>

                <div v-if="files.length" class="divide-y">
                    <div
                        v-for="file in files"
                        :key="file.id"
                        class="p-4 sm:p-6 hover:bg-gray-50 transition"
                    >
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-start gap-3 sm:gap-4 flex-1">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-medium text-gray-900 text-sm sm:text-base truncate">
                                        {{ file.file_path.split('/').pop() }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Task: {{ file.task.title }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Project: {{ file.task.project.name }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Uploaded by: {{ file.uploader.name}} 
                                        {{ new Date(file.created_at).toLocaleDateString() }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2 sm:gap-3 flex-wrap sm:flex-nowrap">
                                <Link
                                    :href="route('projects.show', file.task.project.id)"
                                    class="text-blue-600 hover:text-blue-800 text-sm whitespace-nowrap"
                                >
                                    View Project
                                </Link>
                                <button
                                    @click="viewFile(file.id)"
                                    class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm whitespace-nowrap"
                                >
                                    View
                                </button>
                                <button
                                    @click="downloadFile(file.id)"
                                    class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm whitespace-nowrap"
                                >
                                    Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No files uploaded yet</h3>
                    <p class="text-gray-600">Files will appear here once team members start uploading deliverables.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    project: Object,
    task: Object,
    deliverables: Array
});

const fileForm = useForm({
    file: null
});

const statusForm = useForm({
    status: props.task.status
});

const uploadSuccess = ref(false);
import formatDate from '@/Utils/formatDate';

const updateStatus = () => {
    if (props.task.status === 'complete') {
        alert('Completed tasks cannot be modified.');
        return;
    }

    statusForm.put(route('tasks.update', [props.project.id, props.task.id]), {
        preserveScroll: true,
        onSuccess: () => {
            if (statusForm.status === 'complete') {
                window.location.reload();
            }
        }
    });
};

const uploadFile = () => {
    if (props.task.status === 'not_started') {
        alert('Please start the task before uploading files.');
        return;
    }
    
    if (props.task.status === 'complete') {
        alert('Cannot upload files to completed tasks.');
        return;
    }

    fileForm.post(route('deliverables.store', props.task.id), {
        preserveScroll: true,
        onSuccess: () => {
            fileForm.reset();
            uploadSuccess.value = true;
            setTimeout(() => uploadSuccess.value = false, 3000);
        }
    });
};

const availableStatuses = {
    product: ['not_started', 'in_progress', 'complete'],
    design: ['not_started', 'in_progress', 'complete'],
    development: ['not_started', 'in_progress', 'testing', 'complete']
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-8">
            <!-- Breadcrumb - Mobile optimized -->
            <nav class="flex items-center flex-wrap gap-1 sm:gap-2 text-xs sm:text-sm text-gray-500 mb-4 sm:mb-6">
                <Link :href="route('dashboard')" class="hover:text-gray-700 truncate">Dashboard</Link>
                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <Link :href="route('projects.index')" class="hover:text-gray-700 truncate">Projects</Link>
                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <Link :href="route('projects.show', project.id)" class="hover:text-gray-700 truncate max-w-[120px]">{{ project.name }}</Link>
                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-900 font-medium truncate">Update Task</span>
            </nav>

            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border p-4 sm:p-6">
                <!-- Task Info -->
                <div class="mb-4 sm:mb-6 pb-4 sm:pb-6 border-b">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">{{ task.title }}</h1>
                    <p class="text-gray-600 text-sm sm:text-base mb-4">{{ task.description || 'No description' }}</p>
                    
                    <div class="grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 text-sm">
                        <div class="min-w-0">
                            <span class="text-gray-500 text-xs sm:text-sm">Project:</span>
                            <p class="font-medium truncate">{{ project.name }}</p>
                        </div>
                        <div class="min-w-0">
                            <span class="text-gray-500 text-xs sm:text-sm">Stage:</span>
                            <p class="font-medium capitalize truncate">{{ task.stage }}</p>
                        </div>
                        <div class="min-w-0">
                            <span class="text-gray-500 text-xs sm:text-sm">Due Date:</span>
                            <p class="font-medium truncate">{{ formatDate(task.due_date) || 'Not set' }}</p>
                        </div>
                        <div class="min-w-0">
                            <span class="text-gray-500 text-xs sm:text-sm">Current Status:</span>
                            <p class="font-medium capitalize truncate">{{ task.status.replace('_', ' ') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Status Update Section -->
                <div class="mb-4 sm:mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Update Task Status</h2>
                    
                    <div class="flex flex-col xs:flex-row xs:items-center gap-3 sm:gap-4">
                        <select
                            v-model="statusForm.status"
                            @change="updateStatus"
                            :disabled="task.status === 'complete'"
                            class="px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 disabled:opacity-50 text-sm sm:text-base w-full xs:w-auto"
                        >
                            <option 
                                v-for="status in availableStatuses[task.stage]" 
                                :key="status" 
                                :value="status"
                            >
                                {{ status.replace('_', ' ') }}
                            </option>
                        </select>
                        
                        <div 
                            v-if="task.status === 'complete'"
                            class="flex items-center gap-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium w-fit"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Task Completed
                        </div>
                    </div>

                    <p class="text-xs sm:text-sm text-gray-600 mt-2">
                        Status progression: Not Started 
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 inline mx-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        In Progress 
                        <span v-if="task.stage === 'development'">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 inline mx-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            Testing 
                        </span>
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 inline mx-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        Complete
                    </p>
                </div>

                <!-- File Upload Section -->
                <div v-if="task.status !== 'not_started' && task.status !== 'complete'" class="mb-4 sm:mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Upload Deliverables</h2>
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 sm:p-6">
                        <form @submit.prevent="uploadFile" class="space-y-3 sm:space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload File (Max: 10MB)
                                </label>
                                <input
                                    type="file"
                                    @input="fileForm.file = $event.target.files[0]"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm sm:text-base"
                                    accept="*/*"
                                />
                                <div v-if="fileForm.errors.file" class="text-red-600 text-xs sm:text-sm mt-1">
                                    {{ fileForm.errors.file }}
                                </div>
                            </div>
                            
                            <button
                                type="submit"
                                :disabled="fileForm.processing || !fileForm.file"
                                class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition disabled:opacity-50 text-sm sm:text-base w-full sm:w-auto justify-center"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                Upload File
                            </button>
                        </form>
                    </div>

                    <div v-if="uploadSuccess" class="mt-3 sm:mt-4 p-3 bg-green-50 border border-green-200 rounded-lg flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-green-800 text-sm">File uploaded successfully!</p>
                    </div>
                </div>

                <!-- Uploaded Files List -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Uploaded Files</h2>
                    
                    <div v-if="deliverables.length" class="space-y-2 sm:space-y-3">
                        <div
                            v-for="file in deliverables"
                            :key="file.id"
                            class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition"
                        >
                            <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm text-gray-700 truncate">{{ file.file_path.split('/').pop() }}</span>
                            </div>
                            
                            <Link
                                :href="route('deliverables.download', file.id)"
                                class="flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm font-medium shrink-0 ml-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Download
                            </Link>
                        </div>
                    </div>
                    
                    <div v-else class="text-center py-6 sm:py-8 text-gray-500">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300 mx-auto mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm sm:text-base">No files uploaded yet.</p>
                        <p v-if="task.status === 'not_started'" class="text-xs sm:text-sm mt-1 text-gray-600">
                            Start the task to upload files.
                        </p>
                        <p v-if="task.status === 'complete'" class="text-xs sm:text-sm mt-1 text-gray-600">
                            Task completed - no more uploads allowed.
                        </p>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex flex-col xs:flex-row gap-2 sm:gap-3 pt-4 sm:pt-6 mt-4 sm:mt-6 border-t">
                    <Link
                        :href="route('projects.show', project.id)"
                        class="flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm sm:text-base order-2 xs:order-1"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Project
                    </Link>
                    <Link
                        :href="route('my.tasks')"
                        class="flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm sm:text-base order-1 xs:order-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        View My Tasks
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
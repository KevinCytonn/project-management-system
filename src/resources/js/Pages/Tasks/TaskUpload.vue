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
    due_date: '',
});

const submit = () => {
    form.post(route('tasks.store', props.project.id));
};

// Available statuses based on stage
const availableStatuses = {
    product: ['not_started', 'in_progress', 'complete'],
    design: ['not_started', 'in_progress', 'complete'],
    development: ['not_started', 'in_progress', 'testing', 'complete']
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Add Task to Project</h1>
                    <p class="text-gray-600 mt-1">Project: {{ project.name }} ({{ project.current_stage }} Stage)</p>
                </div>
                
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Task Title *
                        </label>
                        <input
                            id="title"
                            v-model="form.title"
                            type="text"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter task title"
                        >
                    </div>

                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Describe the task requirements..."
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">
                                Assign To
                            </label>
                            <select
                                id="assigned_to"
                                v-model="form.assigned_to"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">Unassigned</option>
                                <option v-for="member in members" :key="member.id" :value="member.id">
                                    {{ member.name }} ({{ member.email }})
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Due Date
                            </label>
                            <input
                                id="due_date"
                                v-model="form.due_date"
                                type="date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="stage" class="block text-sm font-medium text-gray-700 mb-2">
                                Stage *
                            </label>
                            <select
                                id="stage"
                                v-model="form.stage"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="product">Product Development</option>
                                <option v-if="project.requires_design" value="design">Design</option>
                                <option value="development">Development</option>
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status *
                            </label>
                            <select
                                id="status"
                                v-model="form.status"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option v-for="status in availableStatuses[form.stage]" :key="status" :value="status">
                                    {{ status.replace('_', ' ') }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
                        >
                            Create Task
                        </button>
                        <Link
                            :href="route('projects.show', project.id)"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
                        >
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
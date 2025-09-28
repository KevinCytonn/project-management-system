<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    project: Object,
    task: Object,
    members: Array
});

const form = useForm({
    title: props.task.title,
    description: props.task.description,
    assigned_to: props.task.assigned_to,
    stage: props.task.stage,
    status: props.task.status,
    due_date: props.task.due_date,
});

const submit = () => {
    form.put(route('tasks.update', [props.project.id, props.task.id]));
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Task</h1>
                
                <form @submit.prevent="submit" class="space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                      
                    </div>
                    
                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Update Task
                        </button>
                        <Link :href="route('projects.show', project.id)" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
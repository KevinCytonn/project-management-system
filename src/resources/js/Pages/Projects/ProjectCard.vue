<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    project: Object
});

const stageColors = {
    product: 'bg-yellow-100 text-yellow-800',
    design: 'bg-purple-100 text-purple-800',
    development: 'bg-green-100 text-green-800',
    completed: 'bg-gray-100 text-gray-800'
};
</script>

<template>
    <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex justify-between items-start mb-3">
                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ project.name }}</h3>
                <span 
                    class="px-2 py-1 rounded-full text-xs font-medium capitalize shrink-0"
                    :class="stageColors[project.current_stage] || 'bg-gray-100 text-gray-800'"
                >
                    {{ project.current_stage }}
                </span>
            </div>

            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                {{ project.description || 'No description provided' }}
            </p>

            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span>Created by:</span>
                    <span class="font-medium">{{ project.creator.name }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Design Required:</span>
                    <span class="font-medium">{{ project.requires_design ? 'Yes' : 'No' }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Created:</span>
                    <span>{{ new Date(project.created_at).toLocaleDateString() }}</span>
                </div>
            </div>

            <div class="flex justify-between items-center mt-4 pt-4 border-t">
                <Link
                    :href="route('projects.show', project.id)"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                >
                    View Project →
                </Link>
                
                <Link
                    v-if="project.current_stage !== 'completed'"
                    :href="route('tasks.index', { project: project.id })"
                    class="text-gray-600 hover:text-gray-800 text-sm"
                >
                    View Tasks
                </Link>
            </div>
        </div>
    </div>
</template>
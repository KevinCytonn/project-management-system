<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    task: Object,
    project: Object
});

const form = useForm({
    status: props.task.status
});

const updateStatus = () => {
    // Prevent updating completed tasks
    if (props.task.status === 'complete') {
        alert('Completed tasks cannot be modified.');
        return;
    }

    form.put(route('tasks.update', [props.project.id, props.task.id]), {
        preserveScroll: true,
        onSuccess: () => {
            
            if (form.status === 'complete') {
                window.location.reload();
            }
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
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-600">Status:</span>
        <select
            v-model="form.status"
            @change="updateStatus"
            :disabled="task.status === 'complete'"
            class="px-3 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
        >
            <option 
                v-for="status in availableStatuses[task.stage]" 
                :key="status" 
                :value="status"
            >
                {{ status.replace('_', ' ') }}
            </option>
        </select>
        
        <span 
            v-if="task.status === 'complete'"
            class="text-xs text-green-600 font-medium"
        >
            Completed
        </span>
    </div>
</template>
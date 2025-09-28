<script setup>
const props = defineProps({
    project: Object
});

const stages = [
    { id: 'product', name: 'Product Development', color: 'bg-yellow-500' },
    { id: 'design', name: 'Design', color: 'bg-purple-500', condition: props.project.requires_design },
    { id: 'development', name: 'Development', color: 'bg-green-500' },
    { id: 'completed', name: 'Completed', color: 'bg-gray-500' }
];

const getStageIndex = (stage) => {
    const activeStages = stages.filter(s => s.condition !== false);
    return activeStages.findIndex(s => s.id === stage);
};

const isStageCompleted = (stage) => {
    const currentIndex = getStageIndex(props.project.current_stage);
    const stageIndex = getStageIndex(stage);
    return stageIndex < currentIndex;
};

const isStageActive = (stage) => {
    return stage.id === props.project.current_stage;
};
</script>

<template>
    <div class="bg-white rounded-lg border p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Project Progress</h2>
        
        <div class="flex items-center justify-between mb-2">
            <div 
                v-for="stage in stages.filter(s => s.condition !== false)" 
                :key="stage.id"
                class="flex-1 text-center"
            >
                <div class="text-sm font-medium text-gray-700 mb-1">{{ stage.name }}</div>
            </div>
        </div>

        <div class="flex items-center">
            <div 
                v-for="(stage, index) in stages.filter(s => s.condition !== false)" 
                :key="stage.id"
                class="flex items-center flex-1"
            >
                <!-- Stage Circle -->
                <div class="relative">
                    <div 
                        class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-white text-xs font-medium"
                        :class="{
                            [stage.color]: isStageCompleted(stage.id) || isStageActive(stage.id),
                            'bg-gray-300 border-gray-300': !isStageCompleted(stage.id) && !isStageActive(stage.id),
                            'border-gray-200': !isStageCompleted(stage.id) && !isStageActive(stage.id)
                        }"
                    >
                        <span v-if="isStageCompleted(stage.id)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check"><path d="M20 6 9 17l-5-5"/></svg></span>
                        <span v-else-if="isStageActive(stage.id)">{{ index + 1 }}</span>
                        <span v-else>{{ index + 1 }}</span>
                    </div>
                </div>

              
                <div 
                    v-if="index < stages.filter(s => s.condition !== false).length - 1"
                    class="flex-1 h-1"
                    :class="isStageCompleted(stage.id) ? stage.color : 'bg-gray-200'"
                ></div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">
                Current Stage: <span class="font-medium capitalize">{{ project.current_stage }}</span>
            </p>
            <p v-if="project.current_stage !== 'completed'" class="text-xs text-gray-500 mt-1">
                Complete all {{ project.current_stage }} stage tasks to advance
            </p>
        </div>
    </div>
</template>
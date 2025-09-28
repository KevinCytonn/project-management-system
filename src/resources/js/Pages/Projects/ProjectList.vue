<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import ProjectCard from './ProjectCard.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, ref } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const role = computed(() => user.value.role_name?.toLowerCase());

const props = defineProps({
    projects: Object,
    filters: Object
});

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');


const performSearch = () => {
 
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">All Projects</h1>
                    <p class="text-gray-600">View and track all projects in the system</p>
                </div>

               
                <Link 
                    v-if="role === 'product_manager'"
                    :href="route('projects.create')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Project
                </Link>
            </div>

            <!-- Simple Filters -->
            <div class="bg-white rounded-lg border p-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search projects..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            @input="performSearch"
                        >
                    </div>
                    <select
                        v-model="statusFilter"
                        @change="performSearch"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">All Stages</option>
                        <option value="product">Product Stage</option>
                        <option value="design">Design Stage</option>
                        <option value="development">Development Stage</option>
                    </select>
                </div>
            </div>

            <!-- Projects Grid -->
            <div v-if="projects.data.length" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <ProjectCard
                    v-for="project in projects.data"
                    :key="project.id"
                    :project="project"
                />
            </div>

            <div v-else class="text-center py-12 bg-white rounded-lg border">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No projects found</h3>
                <p class="text-gray-600">Try adjusting your search criteria.</p>
            </div>

            <!-- Pagination -->
            <div v-if="projects.last_page > 1" class="flex justify-center">
                <div class="flex gap-2">
                    <Link
                        v-if="projects.current_page > 1"
                        :href="route('projects.index', { page: projects.current_page - 1, search, status: statusFilter })"
                        class="px-3 py-2 border rounded-lg hover:bg-gray-50"
                    >
                        Previous
                    </Link>

                    <Link
                        v-for="page in projects.last_page"
                        :key="page"
                        :href="route('projects.index', { page, search, status: statusFilter })"
                        class="px-3 py-2 border rounded-lg"
                        :class="page === projects.current_page ? 'bg-blue-600 text-white' : 'hover:bg-gray-50'"
                    >
                        {{ page }}
                    </Link>

                    <Link
                        v-if="projects.current_page < projects.last_page"
                        :href="route('projects.index', { page: projects.current_page + 1, search, status: statusFilter })"
                        class="px-3 py-2 border rounded-lg hover:bg-gray-50"
                    >
                        Next
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
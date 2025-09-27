<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

</script>


<template>
    <AuthenticatedLayout>
 <Head title="Admin Dashboard" />
        <div class="space-y-4">
          <h1 class="text-2xl font-bold">Admin Dashboard</h1>
          <p>Overview of all projects, team members, and tasks.</p>
        </div>
    </AuthenticatedLayout>
  
</template>

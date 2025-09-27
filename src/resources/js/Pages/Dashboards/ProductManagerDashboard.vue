<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <AuthenticatedLayout> 
  <Head title="Product Manager Dashboard" />
  <div class="space-y-4">
    <h1 class="text-2xl font-bold">Product Manager Dashboard</h1>
    <p>Manage projects, assign tasks, and track product development stages.</p>
  </div>
   </AuthenticatedLayout>
</template>

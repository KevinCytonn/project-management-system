<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>
<template>
    <AuthenticatedLayout>
  <Head title="Designer Manager Dashboard" />
  <div class="space-y-4">
    <h1 class="text-2xl font-bold">Designer Manager Dashboard</h1>
    <p>Monitor design tasks, assign designers, and ensure design approvals.</p>
  </div>
  </AuthenticatedLayout>
</template>

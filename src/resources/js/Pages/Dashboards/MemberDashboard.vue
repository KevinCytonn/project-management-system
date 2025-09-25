<script setup>
import { Head } from '@inertiajs/vue3';
import MemberLayout from '@/Layouts/MemberLayout.vue';
</script>

<template>
    <MemberLayout>
  <Head title="Member Dashboard" />
  <div class="space-y-4">
    <h1 class="text-2xl font-bold">Member Dashboard</h1>
    <p>View assigned tasks, update progress, and upload deliverables.</p>
  </div>
  </MemberLayout>
</template>

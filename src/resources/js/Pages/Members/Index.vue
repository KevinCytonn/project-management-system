<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UserTable from '@/Components/UserTable.vue';

const props = defineProps({
  teamMembers: Array, // passed from Laravel controller
});
</script>

<template>
  <Head title="My Team" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-lg sm:text-xl font-semibold text-gray-800">My Team</h2>
    </template>

   <div class="overflow-x-auto">
        <UserTable :users="teamMembers" :show-actions="true" />
   </div>
   
  </AuthenticatedLayout>
</template>

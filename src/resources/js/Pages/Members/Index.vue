<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  teamMembers: Array,
});
</script>

<template>
  <Head title="My Team" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-lg sm:text-xl font-semibold text-gray-800">My Team</h2>
    </template>

    <div class="p-4 sm:p-6 bg-white shadow sm:rounded-lg">
      <!-- Make table responsive -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                scope="col"
                class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Name
              </th>
              <th
                scope="col"
                class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Email
              </th>
              <th
                scope="col"
                class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Role
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-for="member in teamMembers" :key="member.id">
              <td class="px-4 sm:px-6 py-4 text-sm sm:text-base text-gray-700">
                {{ member.name }}
              </td>
              <td class="px-4 sm:px-6 py-4 text-sm sm:text-base text-gray-700">
                {{ member.email }}
              </td>
              <td class="px-4 sm:px-6 py-4 text-sm sm:text-base text-gray-700">
                {{ member.role?.name }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


const props = defineProps({
  users: Array,
});

const form = useForm({});

function verifyUser(userId) {
  form.post(route('users.verify', userId));
}
</script>

<template>
  <Head title="Users" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">Users</h2>
    </template>

    <div class="p-6 bg-white shadow sm:rounded-lg">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          <tr v-for="user in users" :key="user.id">
            <td class="px-6 py-4">{{ user.name }}</td>
            <td class="px-6 py-4">{{ user.email }}</td>
            <td class="px-6 py-4">{{ user.role?.name }}</td>
            <td class="px-6 py-4">
              <span v-if="user.is_approved" class="px-2 py-1 text-xs text-green-800 bg-green-100 rounded">Verified</span>
              <span v-else class="px-2 py-1 text-xs text-red-800 bg-red-100 rounded">Pending</span>
            </td>
            <td class="px-6 py-4">
              <button
                v-if="!user.is_approved"
                @click="verifyUser(user.id)"
                class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
              >
                Verify
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthenticatedLayout>
</template>

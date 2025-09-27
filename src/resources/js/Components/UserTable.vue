<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
  users: {
    type: Array,
    required: true,
  },
  showActions: {
    type: Boolean,
    default: false,
  },
});

function verifyUser(userId) {
  router.post(route('users.verify', userId), {}, {
    preserveScroll: true,
    onSuccess: () => {
      console.log(`User ${userId} verified successfully`);
    },
    onError: (errors) => {
      console.error(errors);
    },
  });
}
</script>

<template>
  <!-- Wrapper with horizontal scroll for medium screens -->
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50 sticky top-0">
        <tr>
          <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase">
            Name
          </th>
          <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase">
            Email
          </th>
          <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase">
            Role
          </th>
          <th
            v-if="showActions"
            class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase"
          >
            Actions
          </th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-200 bg-white">
        <tr
          v-for="user in users"
          :key="user.id"
          class="hover:bg-gray-50 transition-colors"
        >
          <!-- Name -->
          <td class="px-3 sm:px-4 md:px-6 py-3 text-xs sm:text-sm md:text-base text-gray-700">
            {{ user.name }}
          </td>

          <!-- Email -->
          <td class="px-3 sm:px-4 md:px-6 py-3 text-xs sm:text-sm md:text-base text-gray-700">
            {{ user.email }}
          </td>

          <!-- Role -->
          <td class="px-3 sm:px-4 md:px-6 py-3 text-xs sm:text-sm md:text-base text-gray-700">
            {{ user.role?.name || "N/A" }}
          </td>

          <!-- Actions -->
          <td
            v-if="showActions"
            class="px-3 sm:px-4 md:px-6 py-3 text-xs sm:text-sm md:text-base"
          >
            <button
              v-if="!user.is_approved"
              @click="verifyUser(user.id)"
              class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-xs sm:text-sm"
            >
              Verify
            </button>
            <span
              v-else
              class="px-2 py-1 text-xs sm:text-sm rounded-full bg-green-100 text-green-700 font-medium"
            >
              Verified
            </span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Mobile stacked cards (shown on xs only) -->
  <!-- <div class="space-y-3 sm:hidden mt-4">
    <div
      v-for="user in users"
      :key="'mobile-' + user.id"
      class="p-3 border rounded-lg shadow-sm bg-white"
    >
      <p class="text-sm font-medium text-gray-800">{{ user.name }}</p>
      <p class="text-xs text-gray-600">{{ user.email }}</p>
      <p class="text-xs text-gray-500">Role: {{ user.role?.name || "N/A" }}</p>

      <div v-if="showActions" class="mt-2">
        <button
          v-if="!user.is_approved"
          @click="verifyUser(user.id)"
          class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs"
        >
          Verify
        </button>
        <span
          v-else
          class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 font-medium"
        >
          Verified
        </span>
      </div>
    </div>
  </div> -->
</template>

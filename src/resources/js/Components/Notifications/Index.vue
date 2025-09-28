<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  notifications: Array
})
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Notifications</h1>

    <div v-if="notifications.length === 0" class="text-gray-500">
      No notifications yet.
    </div>

    <ul v-else class="space-y-3">
      <li v-for="n in notifications" :key="n.id" class="p-4 bg-white shadow rounded flex justify-between items-center">
        <span :class="{ 'font-bold': !n.is_read }">
          {{ n.message }}
        </span>
        <div>
          <!-- <Link
            :href="route('notifications.read', n.id)"
            method="patch"
            as="button"
            class="text-sm text-blue-600 hover:underline"
          >
            Mark as read
          </Link> -->
        </div>
      </li>
    </ul>
  </div>
</template>

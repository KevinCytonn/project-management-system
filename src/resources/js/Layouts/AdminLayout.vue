<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const showingSidebar = ref(false);
const page = usePage();

const user = computed(() => page.props.auth.user);
const role = computed(() => user.value.role_name?.toLowerCase() ?? '');

const notifications = ref(3);


const roleLinks = {
  admin: [
    { name: 'Dashboard', route: 'dashboard' },
    { name: 'Projects Overview', route: 'projects.index' },
    { name: 'Team Members', route: 'team' },
    { name: 'Reports', route: 'reports' },
    { name: 'Manage Users', route: 'users' },
    { name: 'Files', route: 'files' },
  ],
  product_manager: [
    { name: 'Dashboard', route: 'dashboard' },
    { name: 'My Projects', route: 'projects.index' },
    { name: 'Team Members', route: 'team' },
    { name: 'Files', route: 'files' },
  ],
  design_manager: [
    { name: 'Dashboard', route: 'dashboard' },
    { name: 'Design Projects', route: 'projects.index' },
    { name: 'Design Team', route: 'team' },
    { name: 'Files', route: 'files' },
  ],
  development_manager: [
    { name: 'Dashboard', route: 'dashboard' },
    { name: 'Development Projects', route: 'projects.index' },
    { name: 'Dev Team', route: 'team' },
    { name: 'Files', route: 'files' },
  ],
  analyst: [
    { name: 'Dashboard', route: 'dashboard' },
    { name: 'My Tasks', route: 'my.tasks' },
    { name: 'Files', route: 'files' },
  ],
  designer: [
    { name: 'Dashboard', route: 'dashboard' },
    { name: 'My Design Tasks', route: 'my.tasks' },
    { name: 'Files', route: 'files' },
  ],
  developer: [
    { name: 'Dashboard', route: 'dashboard' },
    { name: 'My Dev Tasks', route: 'my.tasks' },
    { name: 'Files', route: 'files' },
  ],
};

const menuItems = computed(() => roleLinks[role.value] || []);
</script>
<template>
  <div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
   <aside
  :class="[
    'fixed inset-y-0 left-0 z-50 w-64 h-screen bg-white border-r border-gray-200 flex flex-col justify-between transition-transform duration-300',
    showingSidebar ? 'translate-x-0' : '-translate-x-full',
    'lg:translate-x-0 lg:static lg:inset-0'
  ]"
>
  <!-- Top (Logo) -->
  <div>
    <div class="p-6 flex items-center justify-between lg:justify-start border-b">
      <Link :href="route('dashboard')">
        <ApplicationLogo class="h-10 w-auto" />
      </Link>
      <button
        @click="showingSidebar = false"
        class="lg:hidden text-gray-500 hover:text-gray-700"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <!-- Links (don’t scroll, just static) -->
    <nav class="px-3 py-4">
      <ul class="flex flex-col gap-2">
        <li v-for="item in menuItems" :key="item.route">
          <NavLink
            :href="route(item.route)"
            :active="route().current(item.route)"
            class="flex items-center px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100"
          >
            {{ item.name }}
          </NavLink>
        </li>
      </ul>
    </nav>
  </div>

  <!-- Bottom (User Info) -->
  <div class="p-4 border-t border-gray-200">
    <div class="text-xs text-gray-500 px-3">Signed in as</div>
    <div class="mt-2 px-3">
      <div class="text-sm font-medium text-gray-900 truncate">{{ user.name }}</div>
      <div class="text-xs text-gray-500 truncate">{{ user.email }}</div>
    </div>
  </div>
</aside>


    <!-- Overlay for mobile -->
    <div
      v-if="showingSidebar"
      class="fixed inset-0 bg-black/30 z-40 lg:hidden"
      @click="showingSidebar = false"
    ></div>

    <!-- Main content -->
    <div class="flex-1 flex flex-col">
      <!-- Topbar -->
      <nav class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="px-4 sm:px-6 lg:px-8 flex h-16 items-center justify-between">
          <button @click="showingSidebar = true" class="lg:hidden text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>

          <Link :href="route('dashboard')" class="hidden lg:inline-flex items-center">
            <ApplicationLogo class="h-9 w-auto" />
          </Link>

          <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <button class="relative text-gray-500 hover:text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V4a2 2 0 10-4 0v1.3A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z"
                />
              </svg>
              <span v-if="notifications > 0"
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5">
                {{ notifications }}
              </span>
            </button>

            <!-- User dropdown -->
            <Dropdown align="right" width="48">
              <template #trigger>
                <button class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                  {{ user.name }}
                  <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </button>
              </template>
              <template #content>
                <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
              </template>
            </Dropdown>
          </div>
        </div>
      </nav>

      <main class="flex-1 p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

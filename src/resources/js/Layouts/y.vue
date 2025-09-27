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
      :class="['fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 transform transition-transform lg:translate-x-0 lg:static lg:inset-0', showingSidebar ? 'translate-x-0' : '-translate-x-full']"
    >
      <div class="p-6 flex items-center justify-between lg:justify-start">
        <Link :href="route('dashboard')">
          <ApplicationLogo class="h-10 w-auto" />
        </Link>
        <button @click="showingSidebar = false" class="lg:hidden text-gray-500 hover:text-gray-700">
          ✕
        </button>
      </div>

      <nav class="flex-1 px-3 py-4 flex flex-col space-y-1">
        <NavLink
          v-for="item in menuItems"
          :key="item.route"
          :href="route(item.route)"
          :active="route().current(item.route)"
        >
          {{ item.name }}
        </NavLink>
      </nav>

      <div class="p-4 border-t border-gray-200">
        <div class="text-xs text-gray-500 px-3">Signed in as</div>
        <div class="mt-2 px-3">
          <div class="text-sm font-medium text-gray-900 truncate">{{ user.name }}</div>
          <div class="text-xs text-gray-500 truncate">{{ user.email }}</div>
        </div>
      </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">
      <!-- Top Nav -->
      <nav class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="px-4 sm:px-6 lg:px-8 flex h-16 items-center justify-between">
          <button @click="showingSidebar = true" class="lg:hidden text-gray-500 hover:text-gray-700">
            ☰
          </button>

          <Link :href="route('dashboard')" class="hidden lg:inline-flex items-center">
            <ApplicationLogo class="h-9 w-auto" />
          </Link>

          <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <button class="relative text-gray-500 hover:text-gray-700">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 
                     0118 14.158V11a6.002 6.002 0 
                     00-4-5.659V4a2 2 0 
                     10-4 0v1.341C8.67 
                     6.165 8 7.388 8 
                     8.75V11c0 .708-.293 
                     1.375-.768 1.837L6 14h5"
                />
              </svg>
              <span
                v-if="notifications > 0"
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5"
              >
                {{ notifications }}
              </span>
            </button>

            <!-- User Dropdown -->
            <Dropdown align="right" width="48">
              <template #trigger>
                <button
                  class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700"
                >
                  {{ user.name }}
                  <svg
                    class="ml-2 h-4 w-4 text-gray-400"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
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

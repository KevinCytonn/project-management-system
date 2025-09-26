<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const showingNavigationDropdown = ref(false);
const page = usePage();
const userRole = computed(() => page.props.auth.user.role_name ?? '');
</script>

<template>
  <div class="flex min-h-screen ">
    <aside class="hidden lg:flex lg:flex-col w-64 bg-white border-r border-gray-200">
      <div class="p-6 flex items-center">
        <Link :href="route('dashboard')">
          <ApplicationLogo class="h-10 w-auto" />
        </Link>
      </div>

      <nav class="flex-1 px-3 py-4 flex flex-col space-y-1">
        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</NavLink>
        <NavLink :href="route('projects.index')" :active="route().current('projects.index')">Projects</NavLink>
        <NavLink :href="route('team')" :active="route().current('team')">Team Members</NavLink>
        <NavLink :href="route('files')" :active="route().current('files')">Files</NavLink>
         <NavLink :href="route('my.tasks')" :active="route().current('my.tasks')">My Tasks</NavLink>
        <NavLink :href="route('users')" :active="route().current('users')" v-show="userRole==='admin'">Manage Users</NavLink>
      </nav>

      <div class="p-4 border-t border-gray-200">
        <div class="text-xs text-gray-500 px-3">Signed in as</div>
        <div class="mt-2 px-3">
          <div class="text-sm font-medium text-gray-900 truncate">{{ page.props.auth.user.name }}</div>
          <div class="text-xs text-gray-500 truncate">{{ page.props.auth.user.email }}</div>
        </div>
      </div>
    </aside>

    <div class="flex-1 flex flex-col">
      <nav class="bg-white border-b border-gray-200">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex h-16 items-center justify-between">
          <Link :href="route('dashboard')" class="inline-flex items-center">
            <ApplicationLogo class="h-9 w-auto" />
          </Link>
          <Dropdown align="right" width="48">
            <template #trigger>
              <button class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                {{ page.props.auth.user.name }}
                <svg class="ml-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
      </nav>

      <main class="flex-1 p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

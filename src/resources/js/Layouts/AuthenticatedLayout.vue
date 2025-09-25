<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import PendingApproval from '@/Components/PendingApproval.vue';

const showingNavigationDropdown = ref(false);
const page = usePage();

// safe role lookup: use role_name if provided, otherwise fallback to role.name
const userRole = computed(() => {
  const u = page.props?.auth?.user ?? {};
  return u.role_name ?? (u.role ? u.role.name : '') ?? '';
});
const isApproved = computed(() =>page.props.auth.user.is_approved);

const sidebarLinks = [
  { name: 'Dashboard', route: 'dashboard' },
  { name: 'Projects', route: 'projects.index' },
  { name: 'Team Members', route: 'team', roles: ['admin','product_manager'] },
  { name: 'Files', route: 'files' },
  { name: 'Manage Users', route: 'users', roles: ['admin'] },
];

const visibleLinks = computed(() => {
  return sidebarLinks.filter(link => !link.roles || link.roles.includes(userRole.value));
});
</script>

<template>
     <!-- <PendingApproval v-if="!isApproved" /> -->
  <div class="flex min-h-screen bg-gray-100" >
    <!-- Sidebar (large screens) -->
    <aside class="hidden lg:flex lg:flex-col w-64 bg-white border-r border-gray-200">
      <div class="p-6 flex items-center">
        <Link :href="route('dashboard')">
          <ApplicationLogo class="h-10 w-auto" />
        </Link>
      </div>

      <nav class="flex-1 px-3 py-4 flex flex-col space-y-1">
        <template v-for="link in visibleLinks" :key="link.route">
          <NavLink
            :href="route(link.route)"
            :active="route().current(link.route)"
            class="flex items-center gap-3 px-3 py-2 rounded-md w-full text-sm"
          >
            <!-- Icon switch -->
            <span class="h-5 w-5 flex-none" aria-hidden="true">
              <svg v-if="link.route === 'dashboard'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
              </svg>

              <svg v-else-if="link.route === 'projects'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 00-2-2H5m14 8V7a2 2 0 00-2-2h-2M7 21h10" />
              </svg>

              <svg v-else-if="link.route === 'team'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8z" />
              </svg>

              <svg v-else-if="link.route === 'files'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10v10H7z M7 3h7l5 5v9a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
              </svg>

              <svg v-else-if="link.route === 'users'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 10-8 0 4 4 0 008 0z M6 21v-2a4 4 0 014-4h4a4 4 0 014 4v2" />
              </svg>

              <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </span>

            <span class="truncate">{{ link.name }}</span>
          </NavLink>
        </template>
      </nav>

      <div class="p-4 border-t border-gray-200">
        <div class="text-xs text-gray-500 px-3">Signed in as</div>
        <div class="mt-2 px-3">
          <div class="text-sm font-medium text-gray-900 truncate">{{ page.props.auth.user.name }}</div>
          <div class="text-xs text-gray-500 truncate">{{ page.props.auth.user.email }}</div>
        </div>
      </div>
    </aside>

    <!-- Main content area -->
    <div class="flex-1 flex flex-col">
      <!-- Top Navbar -->
      <nav class="bg-white border-b border-gray-200">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex h-16 items-center">
            <!-- Left: logo and horizontal links -->
            <div class="flex items-center">
              <Link :href="route('dashboard')" class="inline-flex items-center">
                <ApplicationLogo class="h-9 w-auto" />
              </Link>

             
            </div>

            <!-- Right: user dropdown -->
            <div class="ml-auto hidden sm:flex sm:items-center">
              <Dropdown align="right" width="48">
                <template #trigger>
                  <button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                    
                    <span class="truncate">{{ page.props.auth.user.name }}</span>

                    <svg class="ml-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                </template>

                <template #content>
                  <DropdownLink :href="route('profile.edit')">
                    <svg class="h-4 w-4 mr-2 inline text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9.969 9.969 0 0112 15c2.21 0 4.236.716 5.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Profile
                  </DropdownLink>

                  <DropdownLink :href="route('logout')" method="post" as="button">
                    <svg class="h-4 w-4 mr-2 inline text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0v-1m4-10V5a2 2 0 10-4 0v1" />
                    </svg>
                    Log Out
                  </DropdownLink>
                </template>
              </Dropdown>
            </div>

            <!-- Mobile: hamburger on the far right -->
            <div class="-me-2 flex items-center sm:hidden ml-auto">
              <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none">
                <svg v-if="!showingNavigationDropdown" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg v-else class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </nav>

     
      <div v-show="showingNavigationDropdown" class="lg:hidden bg-white border-b border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1">
          <ResponsiveNavLink v-for="link in visibleLinks" :key="link.route" :href="route(link.route)" :active="route().current(link.route)">
            <span class="flex items-center gap-3">
              <!-- small icons for mobile -->
              <svg v-if="link.route === 'dashboard'" class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" /></svg>
              <svg v-else-if="link.route === 'projects'" class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 00-2-2H5m14 8V7a2 2 0 00-2-2h-2M7 21h10" /></svg>
              <span>{{ link.name }}</span>
            </span>
          </ResponsiveNavLink>
        </div>

        <div class="border-t border-gray-200 pb-3 pt-4">
          <div class="px-4">
            <div class="text-base font-medium text-gray-800">{{ page.props.auth.user.name }}</div>
            <div class="text-sm font-medium text-gray-500">{{ page.props.auth.user.email }}</div>
          </div>

          <div class="mt-3 space-y-1">
            <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
            <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
          </div>
        </div>
      </div>

      <!-- Page Header -->
      <header v-if="$slots.header" class="bg-white shadow">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

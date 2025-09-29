<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
  <Head title="Welcome to Our Platform" />
  <div class="min-h-screen bg-white dark:bg-gray-400">
    <!-- Header -->
    <header class="absolute top-0 w-full z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="flex justify-between items-center">
          <ApplicationLogo />
          <nav v-if="canLogin" class="flex items-center space-x-2 sm:space-x-4">
            <Link
              v-if="canLogin"
              :href="route('login')"
              class="px-4 py-2 sm:px-6 sm:py-2.5 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium text-sm sm:text-base"
            >
              Sign In
            </Link>
            <Link
              v-if="canRegister"
              :href="route('register')"
              class="px-4 py-2 sm:px-6 sm:py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm sm:text-base"
            >
              Get Started
            </Link>
          </nav>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 pt-16 sm:pt-20">
      <div class="text-center max-w-4xl mx-auto">
        <!-- Main Heading -->
        <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold text-gray-900 dark:text-white mb-4 sm:mb-6 leading-tight">
          Organize Your Work. 
          <span class="text-blue-600 block sm:inline">Achieve More.</span>
        </h1>
        
      
        <p class="text-lg sm:text-xl lg:text-2xl text-gray-600 dark:text-gray-300 mb-8 sm:mb-12 leading-relaxed max-w-3xl mx-auto px-4">
          Streamline your workflow and collaborate with your team efficiently.
        </p>
        
        
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
          <Link
            v-if="canRegister"
            :href="route('register')"
            class="w-full sm:w-auto px-6 py-3 sm:px-8 sm:py-4 bg-blue-600 text-white text-base sm:text-lg font-medium rounded-lg hover:bg-blue-700 transition-colors text-center"
          >
            Get Started Free
          </Link>
          <Link
            v-if="canLogin"
            :href="route('login')"
            class="w-full sm:w-auto px-6 py-3 sm:px-8 sm:py-4 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-base sm:text-lg font-medium rounded-lg hover:border-blue-600 hover:text-blue-600 dark:hover:text-blue-400 transition-colors text-center"
          >
            Sign In
          </Link>
        </div>
      </div>
    </main>
  </div>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
  <Head title="Welcome to Taskflow" />
  <div class="min-h-screen bg-white dark:bg-gray-900">
    <header class="absolute top-0 w-full z-50">
      <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
            <span class="text-white font-bold text-lg">T</span>
          </div>
          <span class="text-2xl font-bold text-gray-900 dark:text-white">Taskflow</span>
        </div>
        <nav v-if="canLogin" class="flex items-center space-x-4">
          <Link
            v-if="canLogin"
            :href="route('login')"
            class="px-6 py-2.5 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium"
          >
            Sign In
          </Link>
          <Link
            v-if="canRegister"
            :href="route('register')"
            class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
          >
            Get Started
          </Link>
        </nav>
      </div>
    </header>

    <main class="min-h-screen flex items-center justify-center px-6">
      <div class="text-center max-w-4xl">
        <h1 class="text-5xl lg:text-7xl font-bold text-gray-900 dark:text-white mb-6">
          Organize Your Work. <span class="text-blue-600">Achieve More.</span>
        </h1>
        <p class="text-xl lg:text-2xl text-gray-600 dark:text-gray-300 mb-12 leading-relaxed">
          Taskflow helps you streamline your workflow and collaborate with your team efficiently.
        </p>
        <div class="flex justify-center space-x-4">
          <Link
            v-if="canRegister"
            :href="route('register')"
            class="px-8 py-3 bg-blue-600 text-white text-lg font-medium rounded-lg hover:bg-blue-700 transition-colors"
          >
            Get Started 
          </Link>
          <Link
            v-if="canLogin"
            :href="route('login')"
            class="px-8 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-lg font-medium rounded-lg hover:border-blue-600 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
          >
            Sign In
          </Link>
        </div>
      </div>
    </main>
  </div>
</template>

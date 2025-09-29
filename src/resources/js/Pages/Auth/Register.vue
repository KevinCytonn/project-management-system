<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
  roles: Array, // coming from controller
});

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role_id: '', 
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Register" />

    <form @submit.prevent="submit">
      <!-- Name -->
      <div>
        <InputLabel for="name" value="Name" />
        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
        <InputError class="mt-2" :message="form.errors.name" />
      </div>

      <!-- Email -->
      <div class="mt-4">
        <InputLabel for="email" value="Email" />
        <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <!-- Role -->
      <div class="mt-4">
        <InputLabel for="role" value="Role" />
        <select
          id="role"
          v-model="form.role_id"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          required
        >
          <option value="" disabled>Select a role</option>
          <option v-for="role in roles" :key="role.id" :value="role.id">
            {{ role.name }}
          </option>
        </select>
        <InputError class="mt-2" :message="form.errors.role_id" />
      </div>

      
      <div class="mt-4">
        <InputLabel for="password" value="Password" />
        <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required />
        <InputError class="mt-2" :message="form.errors.password" />
      </div>

     
      <div class="mt-4">
        <InputLabel for="password_confirmation" value="Confirm Password" />
        <TextInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required />
        <InputError class="mt-2" :message="form.errors.password_confirmation" />
      </div>

      <div class="mt-4 flex items-center justify-end">
        <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">Already registered?</Link>
        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Register
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>

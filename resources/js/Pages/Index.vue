<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

// defineProps({
//   invoices: {
//     type: Array,
//   },
//   status: {
//     type: String,
//   },
// });
defineProps(['invoices', 'message'])
</script>

<template>

  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Invoices</h2>
        <Link :href="route('invoices.create')" class="bg-gray-800 text-white rounded py-1 px-4">New Invoice</Link>
      </div>

    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="" v-if="invoices">
            <div class="p-6 text-gray-900">{{ invoices }}</div>
          </div>
          <div class="p-6 text-gray-900">{{ message }}</div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

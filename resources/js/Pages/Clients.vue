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
defineProps(['clients'])
</script>

<template>

  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Clients</h2>
        <Link :href="route('clients.create')" class="bg-gray-800 text-white rounded py-1 px-4">New Client</Link>
      </div>

    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="" v-if="clients">
            <table class="w-full border">
              <thead class="bg-gray-200">
                <th />
                <th class="border">A/c no</th>
                <th class="border">Name</th>
                <th class="border">Email</th>
                <th class="border">Phone</th>
                <th class="border"></th>
              </thead>
              <tbody>
                <tr v-for="client in clients" class="">
                  <td><input type="checkbox" /></td>
                  <td class="border">{{ client.account_number }}</td>
                  <td class="border">{{ client.name }}</td>
                  <td class="border">{{ client.email }}</td>
                  <td class="border">{{ client.phone }}</td>
                  <td class="border">
                    <Link :href="route('clients.edit', client.id)">Edit</Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="" v-else>
            <div class="p-6 text-gray-900">Add new to view</div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

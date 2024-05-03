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
defineProps(['companies'])
</script>

<template>

  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Companies</h2>
      </div>

    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="" v-if="companies">
            <table class="w-full border">
              <thead class="bg-gray-200">
                <th class="border">Name</th>
                <th class="border">Tax Pin</th>
                <th class="border">Location</th>
                <th class="border"></th>
              </thead>
              <tbody>
                <tr v-for="company in companies" class="">
                  <td class="border">{{ company.name }}</td>
                  <td class="border">{{ company.tax_pin }}</td>
                  <td class="border">{{ company.town }}, {{ company.country }}</td>
                  <td class="border"><button>Edit</button></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

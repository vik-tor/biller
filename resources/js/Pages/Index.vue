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
defineProps(['invoices'])
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
            <table class="w-full border">
              <thead class="bg-gray-200">
                <th></th>
                <th class="border">Invoice no</th>
                <th class="border">Date</th>
                <th class="border">Client</th>
                <th class="border">Details</th>
                <th class="border">Amount</th>
                <th class="border">Due Date</th>
                <th>Actions</th>
              </thead>
              <tbody>
                <tr v-for="invoice in invoices" class="">
                  <td><input type="checkbox" /></td>
                  <td class="border">{{ invoice.invoice_no }}</td>
                  <td class="border">{{ invoice.date }}</td>
                  <td class="border">{{ invoice.client_id }}</td>
                  <td class="border">{{ invoice.details }}</td>
                  <td class="border">{{ invoice.amount }}</td>
                  <td class="border">{{ invoice.due_date }}</td>
                  <td class="border">
                    <Link :href="route('invoices.edit', invoice.id)">Edit</Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="" v-else>
            Add invoice to view
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

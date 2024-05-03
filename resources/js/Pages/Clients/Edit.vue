<script>
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime);

const formatDate = (
  dt,
  format = "DD/MM/YYYY"
) => {
  if (!dt) return null;
  return dayjs(dt).format(format);
};
</script>

<script setup>
import { reactive, toRefs } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'

let form = reactive({
  company_id: 1,
  name: "",
  account_number: "",
  email: "",
  phone: "",
  address: ""
});

const reset = () => {
  form.reset();
};

function submit() {
  router.post(route('clients.store'), form, {
    onError: (err) => {
      console.log(err);
    },
    onSuccess: (data) => {
      reset();
      router.visit(data.url);
    },
  });
};
</script>

<template>

  <Head title="Edit Client" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Client</h2>
    </template>

    <div class="h-full max-w-7xl mx-auto">

      <div class="flex flex-col gap-2 relative h-full mx-auto">
        <form @submit.prevent="submit()" class="flex flex-col gap-3 p-4 rounded-md">
          <div class="flex flex-col gap-3">
            <div class="flex gap-2">
              <div class="flex flex-col w-full">
                <label>Account</label>
                <input v-model="form.account_number" />
              </div>

              <div class="flex flex-col w-full">
                <label>Name <span class="text-red-600">*</span></label>
                <input v-model="form.name" required />
              </div>

              <div class="flex flex-col w-full">
                <label>Email</label>
                <input v-model="form.email" />
              </div>
            </div>
            <div class="flex gap-2">
              <div class="flex flex-col w-full">
                <label>Phone</label>
                <input v-model="form.phone" />
              </div>
              <div class="flex flex-col w-full">
                <label>Address</label>
                <textarea v-model="form.address" />
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-2 mt-6 p-4">
            <button class="bg-gray-200 px-4 py-2 rounded">
              Cancel
            </button>
            <button class="bg-gray-800 text-white px-4 py-2 rounded" type="submit">
              Save
            </button>
          </div>
        </form>
      </div>

    </div>
  </AuthenticatedLayout>

</template>
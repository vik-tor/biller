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
import { reactive } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'

let invoiceTotal = 0;

let draftItem = ({
  particulars: "",
  unit_cost: 0,
  quantity: 0,
})

function Item(particulars, unit_cost, quantity) {
  this.particulars = particulars;
  this.unit_cost = unit_cost;
  this.quantity = quantity;
  this.subtotal = quantity * unit_cost
  this.tax = 0
  this.total = quantity * unit_cost
}

const countries = [
  { value: 'us', name: 'United States' },
  { value: 'ca', name: 'Canada' },
  { value: 'fr', name: 'France' },
]

defineProps(['clients', 'companies'])

function addItem() {
  if (draftItem.particulars == "" || draftItem.unit_cost == 0) {
    alert('Item details required')
    return
  } else {
    if (draftItem.quantity == 0 || draftItem.quantity == null) draftItem.quantity = 1
    let item = new Item(draftItem.particulars, draftItem.unit_cost, draftItem.quantity)
    form.items = [...form.items, item];

    draftItem.particulars = ""
    draftItem.unit_cost = null
    draftItem.quantity = null

    let total = getTotalCost();
    console.log(total)

    return;
  }
};

const getTotalCost = () => {
  if (form.items.length > 0) {
    let total = 0, gross = 0;

    form.items.map((i) => {
      total = total + i.total;
    });
    invoiceTotal = total;
  }
  return invoiceTotal;
};

let form = reactive({
  date: formatDate(Date.now(), "YYYY-MM-DD"),
  due_date: formatDate(Date.now(), "YYYY-MM-DD"),
  client_id: 1,
  company_id: 1,
  invoice_no: "",
  details: "",
  subtotal: 0,
  tax: 0,
  discount: 0,
  total: 0,
  terms: "",
  payment_info: "",
  items: [],
});

const reset = () => {
  form.reset();
  form.items = [];
};

function submit() {
  router.post(route('invoices.store'), form, {
    onError: (err) => {
      console.log(err);
      toast.error("Error while saving");
    },
    onSuccess: (data) => {
      toast("Success", {
        action: {
          label: "OK",
          onClick: () => { },
        },
      });
      reset();
      router.visit(data.url);
    },
  });
};
</script>

<template>

  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Invoice</h2>
    </template>

    <div class="h-full max-w-7xl mx-auto">

      <div class="flex flex-col gap-2 relative h-full mx-auto">

        <div class="" v-if="clients">
          <option v-for="item in clients" :value="item.id">{{ item.name }}</option>
        </div>

        <form @submit.prevent="submit()" class="flex flex-col gap-3 p-4 rounded-md">
          <div class="flex flex-col gap-3">
            <div class="flex gap-2">
              <div class="flex flex-col w-full">
                <label>Client</label>
                <select v-model="form.client_id">
                  <option>Select</option>
                  <div class="" v-if="clients">
                    <option v-for="item in clients" :value="item.id">{{ item.name }}</option>
                  </div>
                </select>
              </div>

              <div class="flex flex-col w-full">
                <label>Date</label>
                <input v-model="form.date" label="Date" type="date" required />
              </div>

              <div class="flex flex-col w-full">
                <label>Due Date</label>
                <input v-model="form.due_date" type="date" required />
              </div>
            </div>
            <div class="flex gap-2">
              <div class="flex flex-col w-full">
                <label>Invoice no.</label>
                <input v-model="form.invoice_no" label="Invoice No." required />
              </div>
              <div class="flex flex-col w-full">
                <label>Details</label>
                <input v-model="form.details" label="Details" />
              </div>
            </div>
          </div>


          <div class="h-full overflow-auto flex flex-col gap-2 rounded-md">
            <div class="">
              Items
            </div>
            <table class="table border border-gray-300 bg-white rounded-lg rounded-b-md w-full">
              <thead class="p-4 text-xs bg-gray-200 font-bold">
                <th class="px-4 py-2 font-bold">Description</th>
                <th class="px-4 py-2 font-bold">Unit cost</th>
                <th class="px-4 py-2 font-bold">Quantity</th>
                <th class="px-4 py-2 font-bold">Total</th>
                <th class="px-4 py-2 font-bold" />
              </thead>
              <tbody>
                <tr class="h-14">
                  <td class="p-0 font-normal w-full">
                    <input v-model="draftItem.particulars" class="w-full h-8 mx-2" placeholder="Item" />
                  </td>
                  <td class="p-0 font-normal">
                    <input v-model="draftItem.unit_cost" type="number" class=" h-8 mx-2" placeholder="Cost" />
                  </td>
                  <td class="p-0 font-normal">
                    <input v-model="draftItem.quantity" type="number" class=" h-8 mx-2" placeholder="Quantity" />
                  </td>
                  <td class="p-0 font-normal">
                  </td>
                  <td class="p-0 mx-auto">
                    <a class="px-3 py-1 mr-1 block rounded bg-gray-300" @click="addItem()">
                      <span class="text-gray-800">
                        Save
                      </span>
                    </a>
                  </td>
                </tr>
                <tr v-if="form.items.length > 0" v-for="(item, index) in form.items">
                  <td class="p-0 font-normal">
                    <div class="flex px-4 justify-start">
                      {{ item.particulars }}
                    </div>
                  </td>
                  <td class="p-0 font-normal">
                    <div class="flex px-4">
                      {{ item.unit_cost }}
                    </div>
                  </td>
                  <td class="p-0 font-normal">
                    <div class="flex px-4">
                      {{ item.quantity }}
                    </div>
                  </td>
                  <td class="p-0 font-normal">
                    <div class="flex px-4">
                      {{ item.total }}
                    </div>
                  </td>
                </tr>

              </tbody>
              <tfoot v-if="form.items.length > 0">
                <tr class="font-semibold text-gray-900 dark:text-white">
                  <th scope="row" class="py-3 px-6 text-base" />
                  <td class=""></td>
                  <td class=""></td>
                  <td class="">{{ invoiceTotal }}</td>
                  <td class="py-3 px-2" />
                </tr>
              </tfoot>
            </table>
          </div>

          <div class="w-full">
            <div class="flex flex-col gap-2">
              <div class="flex flex-col w-full">
                <label>Invoice Terms</label>
                <textarea v-model="form.terms" label="Invoice No." />
              </div>
              <div class="flex flex-col w-full">
                <label>Payment information</label>
                <textarea v-model="form.payment_info" />
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
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
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  invoice: {
    type: Object,
    default: null,
    required: false
  }
})

let totalCost = 0,
  totalSelling = 0,
  totalMarkup = 0,
  totalAtHand = 0,
  totalAdjusted = 0,
  totalAmount = 0;
let invoiceGross = 0;

let method;
let actionUrl;
let draftItem = {
  particulars: "",
  unit_cost: "",
  quantity: "",
  total: "",
}

const addItem = (item) => {
  form.items = [...form.items, item];

  getTotalCost();

  return;
};

const getTotalCost = () => {
  if (form.items.length > 0) {
    form.items = form.items;
    let total = 0;
    totalCost = 0;
    totalSelling = 0;
    totalMarkup = 0;
    totalAtHand = 0;
    totalAdjusted = 0;
    totalAmount = 0;
    $form.items.map((i) => {
      total = total + i.total;

      i.markup == null ? (i.markup = 0) : i.markup;
      totalCost = totalCost + Number(i.purchase_price);
      totalSelling = totalSelling + Number(i.selling_price);
      totalMarkup = totalMarkup + Number(i.markup);
      totalAtHand = totalAtHand + Number(i.quantity_at_hand);
      totalAdjusted = totalAdjusted + Number(i.quantity_adjusted);
      totalAmount = totalAmount + Number(i.total);
    });
    invoiceGross = total;
  }
  return invoiceGross;
};

const { invoice } = toRefs(props);

let form = reactive({
  date: invoice
    ? formatDate(invoice.date, "YYYY-MM-DD")
    : formatDate(Date.now(), "YYYY-MM-DD"),
  due_date: invoice
    ? formatDate(invoice.due_date, "YYYY-MM-DD")
    : formatDate(Date.now(), "YYYY-MM-DD"),
  client_id: invoice ? invoice.client_id : null,
  invoice_no: invoice ? invoice.invoice_no : "",
  details: invoice ? invoice.details : "",
  subtotal: invoice ? invoice.subtotal : 0,
  tax: invoice ? invoice.tax : 0,
  discount: invoice ? invoice.discount : 0,
  total: invoice ? invoice.total : 0,
  terms: invoice ? invoice.terms : "",
  payment_info: invoice ? invoice.payment_info : "",
  items: [],
});

const reset = () => {
  form.reset();
  form.items = [];
};

function submit() {
  router.post(route('invoices.update', invoice.id), form, {
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
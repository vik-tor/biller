<script setup>
import { FwbInput, FwbSelect, FwbButton } from 'flowbite-vue'

import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'

defineProps({
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

const countries = [
  { value: 'us', name: 'United States' },
  { value: 'ca', name: 'Canada' },
  { value: 'fr', name: 'France' },
]

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

let form = reactive({
  date: invoice
    ? formatDate(invoice.date, "YYYY-MM-DD")
    : formatDate(Date.now(), "YYYY-MM-DD"),
  due_date: invoice
    ? formatDate(invoice.due_date, "YYYY-MM-DD")
    : formatDate(Date.now(), "YYYY-MM-DD"),
  client_id: invoice ? invoice.client.id : null,
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

// Updating
if (invoice && update_url) {
  console.log("updating");
  method = "patch";
  actionUrl = update_url;
  invoice.items.map((item) => {
    $form.items = [...$form.items, item];
  });
}
// Creating
else if (store_url) {
  console.log("Creating");
  method = "post";
  actionUrl = store_url;
}

const reset = () => {
  form.reset();
  form.items = [];
};

function submit() {
  router.submit(method, actionUrl, {
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
  <div class="h-full page-header">
    <div class="my-2 flex items-center w-full">
      <div class="flex items-center justify-between gap-4 md:gap-4 w-full">
        <div class="flex items-center gap-6">
          <div class="flex items-baseline gap-3">
            <h1 class="font-mono font-semibold text-lg text-gray-800">
              New Invoice
            </h1>
          </div>
        </div>
      </div>
    </div>
    <div class="flex flex-col gap-2 relative h-full">
      <div class="flex flex-col w-full bg-white border rounded-md">

        <form @submit.prevent="submit" class="flex flex-col gap-3 w-full p-4 rounded-md">
          <div class="flex flex-col">
            <div class="flex">
              <fwb-select v-model="form.client_id" :options="countries" label="Customer" />
              <fwb-input v-model="form.date" label="Date" type="date" required />
              <fwb-input v-model="form.due_date" label="Due Date" type="date" required />
            </div>
            <div class="flex">
              <fwb-input v-model="form.invoice_no" label="Invoice No." required />
              <fwb-input v-model="form.details" label="Details" required />
            </div>
          </div>
        </form>
      </div>

      <div class="w-[99vw] h-full overflow-auto bg-white mx-auto rounded-md">
        <fwb-button color="default">Add</fwb-button>
        <table class="table border border-gray-300 rounded-lg rounded-b-md h-full">
          <thead class="p-4 text-xs bg-gray-200 font-bold">
            <th class="px-4 py-2 font-bold">Description</th>
            <th class="px-4 py-2 font-bold">Unit cost</th>
            <th class="px-4 py-2 font-bold">Quantity</th>
            <th class="px-4 py-2 font-bold">Total</th>
            <th class="px-4 py-2 font-bold" />
          </thead>
          <tbody>
            <tr v-if="form.items.length > 0" v-for="(item, index) in form.items">
              <td class="p-0 font-normal">
                <div class="flex px-4">
                  {(item.particulars)}
                </div>
              </td>
              <td class="p-0 font-normal">
                <div class="flex px-4">
                  {(item.unit_cost)}
                </div>
              </td>
              <td class="p-0 font-normal">
                <div class="flex px-4">
                  {(item.quantity)}
                </div>
              </td>
              <td class="p-0 font-normal">
                <div class="flex px-4">
                  {(item.total)}
                </div>
              </td>
            </tr>
            <tr v-if="draftItem">
              <td class="p-0 font-normal">
                <fwb-input v-model="draftItem.particulars" required />
              </td>
              <td class="p-0 font-normal">
                <fwb-input v-model="draftItem.unit_cost" onchange="calculate(draftItem)" required />
              </td>
              <td class="p-0 font-normal">
                <fwb-input v-model="draftItem.quantity" required />
              </td>
              <td class="p-0 font-normal">
                <fwb-input v-model="draftItem.total" required />
              </td>
              <td class="p-0 mx-auto">
                <Button class="p-1 mx-auto px-4" color="none" onClick="addItem()">
                  <span class="text-red-600">
                    <SvgIcon type="mdi" path={mdiDeleteOutline} />
                  </span>
                </Button>
              </td>
            </tr>
            {/each}
            {:else}
            <tr class="text-center py-4">
              <td colspan="7">Select an item</td>
            </tr>
            {/if}
          </tbody>
          {#if items.length > 0}
          <tfoot>
            <tr class="font-semibold text-gray-900 dark:text-white">
              <th scope="row" class="py-3 px-6 text-base" />
              <td class="py-3 px-2 m-0 text-right border">{formatCurrencyRaw(totalCost)}</td>
              <td class="py-3 px-2 m-0 text-right border">{formatCurrencyRaw(totalSelling)}</td>
              <td class="py-3 px-2 m-0 text-right border">{formatCurrencyRaw(totalMarkup)}</td>
              <td class="py-3 px-2 m-0 text-right border">{totalQty} items</td>
              <!-- <td class="py-3 px-2" /> -->
              <td class="py-3 px-2 m-0 text-right border">{formatCurrencyRaw(purchaseTotal)}</td>
              <td class="py-3 px-2" />
            </tr>
          </tfoot>
          {/if}
        </table>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <fwb-button color="light">
        Reset
      </fwb-button>
      <fwb-button>
        Save
      </fwb-button>
    </div>
  </div>
</template>
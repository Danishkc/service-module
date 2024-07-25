<template>
  <Head title="Dashboards" />
  <NavLink>
    <div class="row">
      <div class="pl-4">
        <a href="/services" class="btn btn-light mb-2 ml-2">Orders</a>
      </div>
    </div>

    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <button
            class="btn btn-dark ml-3 mt-3"
            @click="goToServiceInfo(service.id)"
            v-if="[1, 2, 3].includes(service.status)"
          >Go Back</button>

          <button class="btn btn-dark ml-3 mt-3" @click="editQuotation(serviceQuotation.id)">Edit</button>
          <button class="btn btn-dark ml-3 mt-3" @click="handlePrint">Print</button>
          <button
            
            class="btn btn-dark ml-3 mt-3"
            @click="quotationView"
          >View All Quotation</button>
          <button
            
            class="btn btn-dark ml-3 mt-3"
            @click="createInvoice"
          >Create Invoice</button>
          
          <div class="container my-5" ref="contentToPrint">
            <div class="row">
              <div class="col-md-6">
                <h1 class="text-3xl">
                  <b>Quotation -</b>

                  <span class="text-3xl">
                    <b>
                      <em>{{serviceQuotation.quotation_reference}}</em>
                    </b>
                  </span>
                </h1>
                <h2 class="text-lg mt-3">
                  <b>Sale Information</b>
                </h2>
                <hr />
                <p class="mt-3">
                  <strong>Date:</strong>
                  {{ currentDate }}
                </p>
                <p>
                  <strong>Expiration Date:</strong>
                  {{ formattedExpiration }}
                </p>
                <p v-if="serviceQuotation.customer_reference">
                  <strong>Your Reference:</strong>
                  {{serviceQuotation.customer_reference}}
                </p>
                <p v-if="serviceQuotation.incoterm">
                  <strong>Incoterm:</strong>
                  {{serviceQuotation.incoterm}}
                </p>
                <p v-if="serviceQuotation.incoterm_location">
                  <strong>Location:</strong>
                  {{serviceQuotation.incoterm_location}}
                </p>
              </div>
              <div class="col-md-6 mt-5">
                <h2 class="text-lg">
                  <b>Invoicing and Shipping Address</b>
                </h2>
                <hr />
                <p class="mt-3">
                  <strong>{{customer.name}}</strong>
                </p>
                <p>{{ customer.address }}</p>
                <p>{{ customer.phone_1 }}</p>
              </div>
            </div>
            <div class="table-responsive mt-5">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Products</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Taxes</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="line in serviceQuotationLines" :key="line.id">
                    <template v-if="line.pro_id !=null">
                      <td>{{ getProductById(line.pro_id).name }}</td>
                      <td>{{ line.quantity }}</td>
                      <td>{{ line.unit_price }}</td>
                      <td>{{ getTaxNames(line.taxes) }}</td>
                      <td>₹ {{ calculateTaxExcl(line.quantity, line.unit_price).toFixed(2) }}</td>
                    </template>
                    <template v-else-if="line.section || line.note !=null">
                      <td colspan="5" class="bg-light">
                        <div>
                          <strong>{{ line.section }}</strong>
                        </div>
                        <div>
                          <em>{{ line.note }}</em>
                        </div>
                      </td>
                    </template>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="mt-5">
              <div class="d-flex justify-content-end">
                <div>
                  <p class="mb-2">
                    <strong class="mr-5">Untaxed Amount:</strong>
                    ₹ {{ serviceQuotation.untax_amt || '0.00' }}
                  </p>
                  <hr />
                  <p class="mb-2 mt-2">
                    <strong style="margin-right:63px">Taxed Amount:</strong>
                    ₹ {{ serviceQuotation.tax_amt }}
                  </p>
                  <hr />
                  <p class="mb-2 mt-2">
                    <strong style="margin-right:123px">Total:</strong>
                    ₹ {{ serviceQuotation.total }}
                  </p>
                  <hr />
                </div>
              </div>
            </div>
            <div class="mt-3" v-if="hasOptionProId">
              <h1>
                <b class="text-2xl">Options</b>
              </h1>
              <div class="table-responsive">
              <table class="table table-bordered mt-3">
                <thead>
                  <tr>
                    <th>Products</th>
                    <th>Unit Price</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="line in serviceQuotationLines" :key="line.id">
                    <td v-if="line.option_pro_id">{{ getProductById(line.option_pro_id).name }}</td>
                    <td v-if="line.option_unit_price">₹ {{ line.option_unit_price }}</td>
                  </tr>
                </tbody>
              </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </NavLink>
</template>


<script setup>
import { router } from "@inertiajs/vue3";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import axios from "axios";
import { ref, onMounted, computed, watch } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
  customer: {
    type: Object,
    required: true
  },
  products: {
    type: Array,
    required: true
  },
  quotation_id: {
    type: Number,
    required: true
  },
  serviceQuotation: {
    type: Array,
    required: true
  },
  serviceQuotationLines: {
    type: Array,
    required: true
  },
  users: {
    type: Array,
    required: true
  },
  currentUserId: {
    type: Number,
    default: null
  },
  existingInvoice: Object,
  serviceStatus: Object,
  errors: Object
});

const customer = props.customer.customer;
const service = props.serviceStatus.service;

const hasOptionProId = computed(() => {
  return props.serviceQuotationLines.some(line => line.option_pro_id !== null);
});

// Watcher for props.serviceQuotation.service_id
const serviceId = ref(props.serviceQuotation.service_id);

watch(
  () => props.serviceQuotation.service_id,
  newServiceId => {
    serviceId.value = newServiceId;
  }
);

// Function to handle popstate event
const handlePopstate = () => {
  router.push({
    name: "services.getRepaired",
    params: { id: serviceId.value }
  });
};

const form = useForm({});

const createInvoice = () => {
  form.get(route("invoice.create", { id: props.quotation_id }));
};

const quotationView = () => {
  form.get(route("quotation.show", { id: props.serviceQuotation.service_id }));
};

async function removeLine(lineId) {
  try {
    await axios.delete(`/quotation/delete-line/${lineId}`);
  } catch (error) {
    console.error("Error deleting line:", error);
  }
}

const currentDate = ref("");

onMounted(() => {
  const today = new Date();
  const options = { day: "2-digit", month: "2-digit", year: "numeric" };
  const formattedDate = today.toLocaleDateString("en-GB", options);
  currentDate.value = formattedDate;
});

const formattedExpiration = computed(() => {
  const date = new Date(props.serviceQuotation.expiration);
  const day = String(date.getDate()).padStart(2, "0");
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
});

const calculateTaxExcl = (quantity, unitPrice) => {
  return quantity && unitPrice ? quantity * unitPrice : 0;
};

function getTaxNames(taxes) {
  // Ensure taxes is an array
  if (typeof taxes === "string") {
    try {
      taxes = JSON.parse(taxes);
    } catch (e) {
      console.error("Invalid JSON string for taxes:", taxes);
      taxes = [];
    }
  }
  return Array.isArray(taxes) ? taxes.join(", ") : "";
}

function getProductById(id) {
  return props.products.find(product => product.id === id) || {};
}

dayjs.extend(relativeTime);

function formattedScheduledDate(date) {
  return dayjs(date).fromNow();
}

function editQuotation(id) {
  router.get("/quotation/" + id + "/edit");
}

function goToServiceInfo(id) {
  window.location.href = `/services/${id}/edit`;
}

const contentToPrint = ref(null);

const handlePrint = () => {
  const content = contentToPrint.value;
  if (content) {
    const printableContent = content.innerHTML;
    const originalBody = document.body.innerHTML;

    // Create a temporary style element
    const style = document.createElement("style");
    style.innerHTML = `
      @media print {
        body {
          padding: 50px !important;
        }
      }
    `;
    document.head.appendChild(style);

    // Temporarily replace body content with the content to print
    document.body.innerHTML = printableContent;

    // Print the content
    window.print();

    // Restore original body content and remove the temporary style
    document.body.innerHTML = originalBody;
    document.head.removeChild(style);
  }
};
</script>

<style scoped>
.pl-5:hover .btn,
.pl-2:hover .btn {
  background-color: #f8f9fa;
}
</style>
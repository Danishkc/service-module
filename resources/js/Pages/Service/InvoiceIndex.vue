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
          <button class="btn btn-dark ml-3 mt-3" @click="editInvoice(serviceInvoice.id)">Edit</button>
          <button type="button" class="btn btn-dark ml-3 mt-3" @click="handlePrint">Print</button>
          <button class="btn btn-dark ml-3 mt-3" @click="quotationView">View All Invoices</button>

          <button
            type="button"
            class="btn btn-dark ml-3 mt-3"
            v-if="props.serviceInvoice.pay_amt==null || props.serviceInvoice.pay_amt>0"
            @click="showCustomerModal = true"
          >Register Payment</button>
          
          <Modal :maxWidth="'2xl'"   :show="showCustomerModal" @close="showCustomerModal = false">
            <template #default>
              <div class="p-6">
                <button type="button" class="btn-close btn-md float-right" @click="showCustomerModal = false"></button>
                <div>
                  <h2 class="text-xl font-bold flex justify-center">Register Payment</h2>
                </div>

                <hr class="mb-3 mt-3" />
                <div class="row">
                  <div class="col-md-6 mt-3">
                    <span
                      class="text-danger text-xs"
                      style="margin-left: 80px;"
                      v-if="customerErrors.journal "
                    >{{ customerErrors.journal }}</span>
                    <div class="form-group d-flex align-items-center">
                      <label for="journal" style="width: 100px;">Journal :</label>
                      <select
                        id="journal"
                        v-model="modalCustomerForm.journal"
                        class="form-control form-control-sm"
                        style="width: 100%;"
                      >
                      <option value="bank">Bank</option>
                      <option value="cash">Cash</option>
                      </select>
                    </div>
<span
                      class="text-danger text-xs"
                      style="margin-left: 80px;"
                      v-if="customerErrors.pay_method "
                    >{{ customerErrors.pay_method }}</span>
                    <div class="form-group d-flex align-items-center">
                      <label for="paymentMethod" style="width: 100px;">Payment Method :</label>
                      <input
                        id="paymentMethod"
                        v-model="modalCustomerForm.pay_method"
                        class="form-control form-control-sm"
                        style="width: 100%;"
                      />
                    </div>
                    <div class="form-group d-flex align-items-center">
                      <label for="recipientBank" style="width: 100px;">Recipient Bank :</label>
                      <input
                        id="recipientBank"
                        v-model="props.serviceInvoice.recipient_bank"
                        class="form-control form-control-sm"
                        style="width: 100%;"
                        disabled
                      />
                    </div>
                  </div>
                  <div class="col-md-6 mt-3">
                    <span
                      class="text-danger text-xs"
                      style="margin-left: 80px;"
                      v-if="customerErrors.pay_amt "
                    >{{ customerErrors.pay_amt }}</span>
                    <div class="form-group d-flex align-items-center">
                      <label for="amount" style="width: 100px;">Amount :</label>
                      <input
                        id="amount"
                        type="number"
                        step="0.01"
                        v-model="modalCustomerForm.pay_amt"
                        class="form-control form-control-sm"
                        style="width: 100%;"
                        autocomplete="off"
                      />
                    </div>
                    <span
                      class="text-danger text-xs"
                      style="margin-left: 80px;"
                      v-if="customerErrors.pay_date "
                    >{{ customerErrors.pay_date }}</span>
                    <div class="form-group d-flex align-items-center">
                      <label for="paymentDate" style="width: 100px;">Payment Date :</label>
                      <input
                      type="date"
                            v-model="modalCustomerForm.pay_date"
                            id="delivery_date"
                            style="width: 100%;"
                            class="form-control form-control-sm rounded"
                          />
                    </div>
                    <div class="form-group d-flex align-items-center">
                      <label for="memo" style="width: 100px;">Memo :</label>
                      <input
                        id="memo"
                        v-model="props.serviceInvoice.invoice_reference"
                        class="form-control form-control-sm"
                        style="width: 100%;"
                        disabled
                      />
                    </div>
                  </div>
                </div>
                <hr />
                <div class="flex justify-center mt-3 mb-3">
                  <button class="btn btn-dark"   @click.prevent="submitCustomerModalForm">Create Payment</button>
                </div>
              </div>
            </template>
          </Modal>

          <div class="container my-5" ref="contentToPrint">
            <hr />
            <div class="row">
              <div class="col-md-4 offset-md-8">
                <p class="mt-3">{{customer.name}}</p>
                <p>{{ customer.address }}</p>
                <p>{{ customer.phone_1 }}</p>
              </div>
            </div>
            <h5 class="text-4xl">Customer Invoices {{serviceInvoice.invoice_reference}}</h5>
            <div class="row mt-4">
              <div v-if="serviceInvoice.invoice_date" class="col-md-3">
                <h1>
                  <strong class="text-xl">Invoice Date:</strong>
                </h1>
                <h1 class="text-md">{{formattedInvoice }}</h1>
              </div>
              <div v-if="serviceInvoice.delivery_date" class="col-md-3">
                <h1>
                  <strong class="text-xl">Delivery Date:</strong>
                </h1>
                <h1 class="text-md">{{serviceInvoice.delivery_date}}</h1>
              </div>
              <div v-if="serviceInvoice.due_date" class="col-md-3">
                <h1>
                  <strong class="text-xl">Due Date:</strong>
                </h1>
                <h1 class="text-md">{{formattedDueDate }}</h1>
              </div>
              <div v-if="quotationReference" class="col-md-3">
                <h1>
                  <strong class="text-xl">Source:</strong>
                </h1>
                <h1 class="text-md">{{quotationReference}}</h1>
              </div>
              <div class="col-md-3">
                <h1>
                  <strong v-if="serviceInvoice.customer_reference" class="text-xl">Reference:</strong>
                </h1>
                <h1 class="text-md">{{serviceInvoice.customer_reference}}</h1>
              </div>
            </div>

            <div class="table-responsive mt-5">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Description</th>
                    <th>HSN/SAC</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Taxes</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="line in serviceInvoiceLines" :key="line.id">
                    <template v-if="line.pro_id !=null">
                      <td>{{ line.label }}</td>
                      <td></td>
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
            <div class="row mt-5 mr-5 mb-5">
              <div class="col-md-4 offset-md-8">
                <div class="row mb-2">
                  <div class="col-sm-5 col-md-6">
                    <strong>Untaxed Amount:</strong>
                  </div>
                  <div
                    class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 text-end"
                  >₹ {{ serviceInvoice.untax_amt || '0.00' }}</div>
                </div>
                <hr />
                <div class="row mt-2 mb-2">
                  <div class="col-sm-5 col-md-6">
                    <strong>Taxed Amount:</strong>
                  </div>
                  <div
                    class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 text-end"
                  >₹ {{ serviceInvoice.tax_amt || '0.00' }}</div>
                </div>
                <hr />
                <div class="row mt-2 mb-2">
                  <div class="col-sm-5 col-md-6">
                    <strong>Total:</strong>
                  </div>
                  <div
                    class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 text-end"
                  >₹ {{ serviceInvoice.total }}</div>
                </div>
                <div v-if="serviceInvoice.pay_amt!==null">
                <div  class="row mt-2 mb-2 mt-4">
                  <div class="col-sm-5 col-md-6">
                    <span>Paid on: {{formattedPayOn}} </span>
                  </div>
                  <div v-if="serviceInvoice.pay_amt==0"
                    class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 text-end"
                  >₹ {{ serviceInvoice.total }}</div>
                  <div v-if="serviceInvoice.pay_amt > 0"
                    class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 text-end"
                  >₹ {{ serviceInvoice.total-serviceInvoice.pay_amt }}</div>
                </div>
                <hr />
                <div class="row mt-2 mb-2">
                  <div class="col-sm-5 col-md-6">
                    <strong>Amount Due:</strong>
                  </div>
                  <div
                    class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 text-end"
                  ><b>₹ {{ serviceInvoice.pay_amt }}</b></div>
                </div>
                </div>
                <p class="text-end mt-3">Total amount in words:</p>
                <p class="text-end">{{ getTotalToWords(serviceInvoice.total) }}</p>
              </div>
            </div>
            <hr />
            <h1 class="text-2xl mt-2">HSN Summary</h1>
            <div class="table-responsive mt-3 mb-4">
              <table class="table">
                <thead>
                  <tr>
                    <th>HSN/SAC</th>
                    <th>Quantity</th>
                    <th>Rate %</th>
                    <th>Taxable Value</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
            <p
              v-if="serviceInvoice.payment_reference"
              class="text-start"
            >Payment Communication: {{serviceInvoice.payment_reference}}</p>
            <p
              v-if="serviceInvoice.recipient_bank"
              class="text-start mb-4"
            >on this account: {{serviceInvoice.recipient_bank}}</p>

            <hr />
            <div class="row">
              <div class="col-md-6 offset-md-3">{{customer.email}}</div>
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
import num2words from "num2words";
import { ref, onMounted, computed, watch } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";
import Modal from "@/Components/Modal.vue";


const props = defineProps({
  customer: {
    type: Object,
    required: true
  },
  products: {
    type: Array,
    required: true
  },
  invoice_id: {
    type: Number,
    required: true
  },
  serviceInvoice: {
    type: Array,
    required: true
  },
  serviceInvoiceLines: {
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
  quotationReference: Array,
  serviceStatus: Object,
  errors: Object
});
const customer = props.customer.customer;
const service = props.serviceStatus.service;

const showCustomerModal = ref(false);

const today = new Date().toISOString().split('T')[0];



const modalCustomerForm = useForm({
journal:'',
pay_method:'Manual',
pay_date: today,
pay_amt: props.serviceInvoice.pay_amt !== null ? props.serviceInvoice.pay_amt : props.serviceInvoice.total
});



const customerErrors = ref({});
function submitCustomerModalForm() {
  const payAmt=modalCustomerForm.pay_amt;

  if (payAmt > props.serviceInvoice.total) {
    modalCustomerForm.pay_amt = 0;
  }else if(props.serviceInvoice.pay_amt !== null){
    if (payAmt > props.serviceInvoice.pay_amt) {
      modalCustomerForm.pay_amt = 0;
    }else{
      const totalBalanceAmt=props.serviceInvoice.pay_amt-payAmt;
      modalCustomerForm.pay_amt = totalBalanceAmt;
    }
  }else if(props.serviceInvoice.pay_amt == null){
    const totalBalanceAmt=props.serviceInvoice.total-payAmt;
    modalCustomerForm.pay_amt = totalBalanceAmt;
  }

  router.post(`/invoice/register/${props.serviceInvoice.id}`, modalCustomerForm, {
    onSuccess: () => {
      console.log("Form submitted successfully");
      showCustomerModal.value = false;   
      if (typeof modalCustomerForm.reset === 'function') {
        modalCustomerForm.reset();      
      }
      customerErrors.value = {};
      window.location.reload();         
    },
    onError: (formErrors) => {
      console.log("Form submission failed", formErrors);
      customerErrors.value = formErrors || {};  
      window.location.reload(); 
    }
  });
}



const currentDate = ref("");

function getTotalToWords(amount) {
  const [integerPart, decimalPart] = amount.toString().split(".");
  const integerWords = num2words(integerPart);
  const decimalWords = decimalPart ? num2words(decimalPart) : "zero";
  return `${integerWords} rupees and ${decimalWords} paise only`;
}

onMounted(() => {
  const today = new Date();
  const options = { day: "2-digit", month: "2-digit", year: "numeric" };
  const formattedDate = today.toLocaleDateString("en-GB", options);
  currentDate.value = formattedDate;
});

const formatDate = dateString => {
  const date = new Date(dateString);
  const day = String(date.getDate()).padStart(2, "0");
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
};

const formattedPayOn = computed(() =>
  formatDate(props.serviceInvoice.pay_date)
);

const formattedInvoice = computed(() =>
  formatDate(props.serviceInvoice.invoice_date)
);
const formattedDueDate = computed(() =>
  formatDate(props.serviceInvoice.due_date)
);

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

function editInvoice(id) {
  router.get("/invoice/" + id + "/edit");
}

function goToServiceInfo(id) {
  window.location.href = `/services/${id}/edit`;
}
const forms = useForm({});
const quotationView = () => {
  forms.get(route("invoice.show", { id: props.serviceInvoice.quotation_id }));
};

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
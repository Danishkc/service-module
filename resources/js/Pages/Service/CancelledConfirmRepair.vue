<template>
  <Head title="Dashboards" />
  <NavLink>
    <!-- <div class="alert alert-success" v-if="$page.props.flash.message">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong>{{$page.props.flash.message}}</strong>
    </div>
    <div class="alert alert-danger" v-if="$page.props.flash.error">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong>{{$page.props.flash.error}}</strong>
    </div>-->
    <div class="row">
      <div class="pl-4">
        <a href="/services" class="btn btn-light mb-2 ml-2">Orders</a>
      </div>
    </div>

    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <form disabled>
            <div class="d-flex justify-content-end">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <span class="nav-link">New</span>
                </li>
                <li class="nav-item">
                  <span class="nav-link">Confirmed</span>
                </li>
                <li class="nav-item">
                  <span class="nav-link active">Cancelled</span>
                </li>
                <li class="nav-item">
                  <span class="nav-link">Under Repair</span>
                </li>
                <li class="nav-item">
                  <span class="nav-link">Repaired</span>
                </li>
              </ul>
            </div>
            <button
              
              @click.prevent="goToIndex"
              class="btn btn-dark btn-md mb-5 ml-3"
            >Go Back</button>
            <button
              @click.prevent="setToDraft(service.id)"
              class="btn btn-dark btn-md mb-5 ml-3"
            >Set to Draft</button>

            <div class="container" >
              
              <div class="row mb-5">
                <h1><b class="text-4xl text-blue-500">{{props.service.repair_reference}}</b></h1>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-6">
                      <span
                        class="text-danger text-xs"
                        style="margin-left:130px;"
                        v-if="errors.cus_id"
                      >{{ errors.cus_id }}</span>
                      <div class="form-group d-flex align-items-start">
                        <label for="customer" class="mr-2" style="width: 150px;">Customer</label>
                        <v-select
                          v-model="form.cus_id"
                          id="customer"
                          style="width: 100%;"
                          :options="customers"
                          label="name"
                          :reduce="(customers) => customers.id"
                          disabled
                        ></v-select>
                      </div>
                      <span
                        class="text-danger text-xs"
                        style="margin-left:130px;"
                        v-if="errors.pro_id"
                      >{{ errors.pro_id }}</span>
                      <div class="form-group d-flex align-items-center">
                        <label
                          for="product_to_repair"
                          class="mr-2"
                          style="width: 150px;"
                        >Product to Repair</label>
                        <v-select
                          v-model="form.pro_id"
                          id="product_to_repair"
                          :options="products"
                          label="name"
                          :reduce="(products) => products.id"
                          placeholder="search"
                          style="width: 100%;"
                          disabled
                        ></v-select>
                      </div>
                      <span
                        class="text-danger text-xs"
                        v-if="errors.product_quantity"
                        style="margin-left:130px;"
                      >{{ errors.product_quantity }}</span>
                      <div class="form-group d-flex align-items-center" v-if="form.pro_id">
                        <label
                          for="product_quantity"
                          class="mr-2"
                          style="width: 120px;"
                        >Product quantity</label>
                        <input
                          type="number"
                          step="0.01"
                          v-model="form.product_quantity"
                          id="product_quantity"
                          class="form-control form-control-sm rounded"
                          style="width: 15%;"
                          disabled
                        />
                      </div>
                      <div class="form-group d-flex align-items-center">
                        <label for="return" class="mr-2" style="width: 150px;">Return</label>
                        <v-select v-model="form.return" id="return" style="width: 100%;" disabled></v-select>
                        <div class="text-danger text-xs" v-if="errors.return">
                          <span>{{ errors.return }}</span>
                        </div>
                      </div>

                      <div class="form-group d-flex align-items-center">
                        <label for="warranty" class="mr-2" style="width: 100px;">Under Warranty</label>
                        <input
                          type="checkbox"
                          v-model="form.warranty"
                          id="warranty"
                          class="ml-4 mt-1 form-control  rounded"
                          disabled
                        />
                        <div class="text-danger text-xs" v-if="errors.warranty">
                          <span>{{ errors.warranty }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <span
                        class="text-danger text-xs"
                        style="margin-left:130px;"
                        v-if="errors.scheduled_date"
                      >{{ errors.scheduled_date }}</span>
                      <div class="form-group d-flex align-items-center">
                        <label
                          for="scheduled_date"
                          class="mr-2"
                          style="width: 150px;"
                          disabled
                        >Scheduled Date</label>
                        <VueDatePicker
                          v-model="form.scheduled_date"
                          id="scheduled_date"
                          :is-24="false"
                          style="width: 100%;"
                          @input="formatDate"
                          disabled
                        />
                      </div>
                      <div class="form-group d-flex align-items-center">
                        <label for="responsible" class="mr-2" style="width: 150px;">Responsible</label>
                        <v-select
                          v-model="form.responsible"
                          id="responsible"
                          style="width: 100%;"
                          :options="users"
                          label="name"
                          :reduce="(users) => users.id"
                          disabled
                        ></v-select>
                        <div class="text-danger text-xs" v-if="errors.responsible">
                          <span>{{ errors.responsible }}</span>
                        </div>
                      </div>
                      <div class="form-group d-flex align-items-center">
                        <label for="tags" class="mr-2" style="width: 150px;">Tags</label>
                        <input
                          type="text"
                          v-model="form.tags"
                          id="tags"
                          class="form-control form-control-sm rounded"
                          style="width: 100%;"
                          disabled
                        />
                        <div class="text-danger text-xs" v-if="errors.tags">
                          <span>{{ errors.tags }}</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row mt-3">
                    <div class="col-md-12">
                      <div class="btn-group" role="group" aria-label="Basic example">
                        <button
                          type="button"
                          class="btn btn-sm"
                          :class="{ 'btn-dark': currentTab === 'parts', 'btn-light': currentTab!== 'parts' }"
                          @click="changeTab('parts')"
                        >Parts</button>
                        <button
                          type="button"
                          class="btn btn-sm"
                          :class="{ 'btn-dark': currentTab === 'repairNotes', 'btn-light': currentTab!== 'repairNotes' }"
                          @click="changeTab('repairNotes')"
                        >Repair Notes</button>
                        <button
                          type="button"
                          class="btn btn-sm"
                          :class="{ 'btn-dark': currentTab === 'miscellaneous', 'btn-light': currentTab!== 'miscellaneous' }"
                          @click="changeTab('miscellaneous')"
                        >Miscellaneous</button>
                      </div>
                    </div>
                  </div>
                  <!-- Parts Tab -->
                  <div v-if="currentTab === 'parts'" class="table-responsive mt-3">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Type</th>
                          <th>Product</th>
                          <th>Demand</th>
                          <th>Done</th>
                          <th>Used</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(line, index) in form.lines" :key="index">
                          <td>
                            <div class="form-group">
                              <select v-model="line.type" class="form-control form-control-sm" disabled>
                                <option value="add" selected>Add</option>
                                <option value="remove">Remove</option>
                                <option value="recycle">Recycle</option>
                              </select>
                              <div
                                class="text-danger text-xs"
                                v-if="errors && errors[`lines.${index}.type`]"
                              >
                                <span>{{ errors[`lines.${index}.type`] }}</span>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <v-select
                                v-model="line.product_id"
                                :options="products"
                                label="name"
                                placeholder="search"
                                :reduce="(products) => products.id"
                                disabled
                              ></v-select>
                              <!-- General lines error for custom validation rule -->
                              <div
                                class="text-danger text-xs"
                                v-if="errors && errors['lines'] && index === form.lines.length - 1"
                              >
                                <span v-for="error in errors['lines']" :key="error">{{ error }}</span>
                              </div>
                              <!-- Specific product_id error -->
                              <div
                                class="text-danger text-xs"
                                v-if="errors && errors[`lines.${index}.product_id`]"
                              >
                                <span>{{ errors[`lines.${index}.product_id`] }}</span>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input
                                type="number"
                                v-model="line.demand"
                                class="form-control form-control-sm rounded"
                                disabled
                              />
                              <div
                                class="text-danger text-xs"
                                v-if="errors && errors[`lines.${index}.demand`]"
                              >
                                <span>{{ errors[`lines.${index}.demand`] }}</span>
                              </div>
                            </div>
                          </td>
                          
                          <td>
                            <div class="form-group">
                              <input
                                type="number"
                                v-model="line.done"
                                class="form-control form-control-sm rounded"
                                disabled
                              />
                              <div
                                class="text-danger text-xs"
                                v-if="errors && errors[`lines.${index}.done`]"
                              >
                                <span>{{ errors[`lines.${index}.done`] }}</span>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input
                                type="checkbox"
                                class="ml-2 form-control rounded"
                                v-model="line.used"
                                disabled
                              />
                              <div
                                class="text-danger text-xs"
                                v-if="errors && errors[`lines.${index}.used`]"
                              >
                                <span>{{ errors[`lines.${index}.used`] }}</span>
                              </div>
                            </div>
                          </td>
                          
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <!-- Repair Notes Tab -->
                  <div v-if="currentTab === 'repairNotes'" class="row mt-3">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="repair_notes">Repair Notes</label>
                        <textarea
                          id="repair_notes"
                          v-model="form.repair_notes"
                          class="form-control form-control-sm"
                          disabled
                        ></textarea>
                        <div class="text-danger text-xs" v-if="errors.repair_notes">
                          <span>{{ errors.repair_notes }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Miscellaneous Tab -->
                  <div v-if="currentTab === 'miscellaneous'" class="row mt-3">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="operation_type">Operation Type</label>
                        <select
                          id="operation_type"
                          v-model="form.operation_type"
                          class="form-control form-control-sm"
                          disabled
                        >
                          <option>Your Company: Repairs</option>
                        </select>
                        <div class="text-danger text-xs" v-if="errors.operation_type">
                          <span>{{ errors.operation_type }}</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <input v-model="form.component_status" type="hidden" />
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </NavLink>
</template>


<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";
import Modal from "@/Components/Modal.vue";
import axios from "axios";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

const props = defineProps({
  customers: {
    type: Array,
    required: true
  },
  products: {
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
  service: {
    type: Array,
    required: true
  },
  serviceLines: {
    type: Array,
    required: true
  },
  existingQuotationId: Number,
  existingInvoiceId: Number,
  errors: Object
});

const currentTab = ref("parts");
const showAddLineField = ref(false);
const line = ref([]);

function changeTab(tab) {
  currentTab.value = tab;
}
const showCustomerModal = ref(false);
const showProductModal = ref(false);
const showRespModal = ref(false);

const modalCustomerForm = useForm({
  name: "",
  email: "",
  phone_1: "",
  address: ""
});

const modalProductForm = useForm({
  name: "",
  serial_no: ""
});

const modalRespForm = useForm({
  name: "",
  email: ""
});

const formatDecimal = value => {
  return parseFloat(value).toFixed(2);
};

const form = useForm({
  cus_id: props.service.cus_id,
  pro_id: props.service.pro_id,
  product_quantity: props.service.product_quantity || formatDecimal(1),
  return: props.service.return,
  warranty: !!props.service.warranty,
  scheduled_date: props.service.scheduled_date,
  responsible: props.service.responsible,
  tags: props.service.tags,
  component_status: props.service.component_status,

  lines: props.serviceLines
    .filter(line => line.product !== null) // Filter out lines where product is null or undefined
    .map(line => ({
      id: line.id,
      type: line.type,
      product_id: line.product?.id,
      demand: line.demand,
      done: line.done,
      used: !!line.used
    })),

  repair_notes: props.service.repair_notes,
  operation_type: props.service.operation_type
});

const forms = useForm({});

function createQuotation() {
  forms.get(route("quotation.create", { id: props.service.id }));
}

function quotationIndex() {
  forms.get(route("quotation.index", { id: props.existingQuotationId }));
}

function invoiceIndex() {
  forms.get(route("invoice.index", { id: props.existingInvoiceId }));
}

function setToDraft(id) {
  window.location.href = '/services/start-repair/' + id;
}

function goToIndex() {
  window.location.href = '/services';
}
// const modalFormErrors = ref({});
const errors = ref({});

function addLine() {
  showAddLineField.value = true;
  if (!form.lines.some(line => !line.product_id)) {
    form.lines.push({
      type: "add",
      product_id: "",
      demand: formatDecimal(1),
      done: formatDecimal(1),
      used: ""
    });
  } else {
    errors.value = {
      [`lines.${form.lines.length - 1}.product_id`]: "Product is required."
    };
    setTimeout(() => {
      errors.value = "";
    }, 2000);
  }
}

async function removeLine(index, lineId) {
  if (lineId === undefined) {
    form.lines.splice(index, 1);
  } else {
    try {
      console.log("line id", lineId);
      await axios.delete(`/services/delete-line-endRepair/${lineId}`);
      form.lines.splice(index, 1);
    } catch (error) {
      console.error("Error deleting line:", error);
    }
  }
}

// Set minimum date and time to current date and time
// const now = new Date();
// const minDateTime = ref(now.toISOString().slice(0, 16));

function formatDate() {
  if (form.scheduled_date) {
    // Format the date to YYYY-MM-DD HH:MM:SS
    const dateObj = new Date(form.scheduled_date);
    const formattedDate = `${dateObj.getFullYear()}-${padNumber(
      dateObj.getMonth() + 1
    )}-${padNumber(dateObj.getDate())} ${padNumber(
      dateObj.getHours()
    )}:${padNumber(dateObj.getMinutes())}:${padNumber(dateObj.getSeconds())}`;
    form.scheduled_date = formattedDate;
  }
}

function padNumber(num) {
  return num.toString().padStart(2, "0");
}

function submit(id) {
  form.put("/services/end-repair/" + id, {
    onSuccess: () => {
      console.log("Form submitted successfully");
    },
    onError: formErrors => {
      console.log("Form submission failed", formErrors);
      errors.value = formErrors || {};
    }
  });
}

const customerErrors = ref({});
function submitCustomerModalForm() {
  modalCustomerForm.post("/services/customer", {
    onSuccess: () => {
      console.log("Form submitted successfully");
      showCustomerModal.value = false;
      modalCustomerForm.reset();
      customerErrors.value = {};
    },
    onError: formErrors => {
      console.log("Form submission failed", formErrors);
      customerErrors.value = formErrors || {};
    }
  });
}

const proErrors = ref({});
function submitProductModalForm() {
  modalProductForm.post("/services/product", {
    onSuccess: () => {
      console.log("Form submitted successfully");
      showProductModal.value = false;
      modalProductForm.reset();
      proErrors.value = {};
    },
    onError: formErrors => {
      console.log("Form submission failed", formErrors);
      proErrors.value = formErrors || {};
    }
  });
}
const respErrors = ref({});
function submitRespModalForm() {
  modalRespForm.post("/services/responsible", {
    onSuccess: () => {
      console.log("Form submitted successfully");
      showRespModal.value = false;
      modalRespForm.reset();
      respErrors.value = {};
    },
    onError: formErrors => {
      console.log("Form submission failed", formErrors);
      respErrors.value = formErrors || {};
    }
  });
}
</script>

<style scoped>
.pl-5:hover .btn,
.pl-2:hover .btn {
  background-color: #f8f9fa;
}
.nav-link.active {
  background-color: #666b70; /* or any other color you prefer */
  color: #ffffff; /* or any other color you prefer */
}
</style>
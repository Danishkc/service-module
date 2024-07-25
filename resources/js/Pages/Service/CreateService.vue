<template>
  <Head title="Dashboards" />
  <NavLink>
    <!-- <div class="alert alert-success" v-if="$page.props.flash.message">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong>{{$page.props.flash.message}}</strong>
    </div>-->
    <div
      v-if="$page.props.errors && Object.values($page.props.errors).length > 0"
      class="alert alert-danger"
    >
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <ul>
        <li v-for="(error, index) in $page.props.errors" :key="index">{{ error }}</li>
      </ul>
    </div>
    <div class="row">
      <div class="pl-4">
        <a href="/services" class="btn btn-light mb-2 ml-2">Orders</a>
      </div>
      <!-- <div class="pl-2">
        <a href="#" class="btn mb-2">Reporting</a>
      </div>
      <div class="pl-2">
        <a href="#" class="btn mb-2">Configuration</a>
      </div>-->
    </div>

    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="submit">
            <div class="d-flex justify-content-end mb-3">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <span class="nav-link active">New</span>
                </li>
                <li class="nav-item">
                  <span class="nav-link">Confirmed</span>
                </li>
                <li class="nav-item">
                  <span class="nav-link">Under Repair</span>
                </li>
                <li class="nav-item">
                  <span class="nav-link">Repaired</span>
                </li>
              </ul>
            </div>

            <div class="ml-3">
              <button class="btn btn-dark btn-md mb-5" type="submit">Confirm Repair</button>
              <button
                type="button"
                class="btn btn-dark btn-md mb-5 dropdown-toggle ml-3"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <span class="visually-hidden"></span>Create
              </button>
              <ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item" @click="showCustomerModal = true" href="#">New Customer</a>
                </li>
                <li>
                  <a class="dropdown-item" @click="showProductModal = true" href="#">New Product</a>
                </li>
                <li>
                  <a class="dropdown-item" @click="showRespModal = true" href="#">New Responsible</a>
                </li>
              </ul>
            </div>

            <Modal :show="showCustomerModal" @close="showCustomerModal = false">
              <template #default>
                <div class="p-6">
                  <button type="button" class="btn-close btn-md float-right" @click="showCustomerModal = false"></button>
                  <div>
                    <h2 class="text-xl font-bold flex justify-center">Create New Customer</h2>
                  </div>

                  <hr class="mb-3" />
                  <span
                    class="text-danger text-xs"
                    style="margin-left: 80px;"
                    v-if="customerErrors.name"
                  >{{ customerErrors.name}}</span>
                  <div class="form-group d-flex align-items-center">
                    <label for="name" style="width: 100px;">Full Name:</label>
                    <input
                      id="name"
                      v-model="modalCustomerForm.name"
                      class="form-control form-control-sm"
                      style="width: 100%;"
                    />
                  </div>
                  <span
                    class="text-danger text-xs"
                    style="margin-left: 80px;"
                    v-if="customerErrors.email"
                  >{{ customerErrors.email}}</span>
                  <div class="form-group d-flex align-items-center">
                    <label for="email" style="width: 100px;">Email:</label>
                    <input
                      id="email"
                      v-model="modalCustomerForm.email"
                      class="form-control form-control-sm"
                      style="width: 100%;"
                    />
                  </div>
                  <span
                    class="text-danger text-xs"
                    style="margin-left: 80px;"
                    v-if="customerErrors.phone_1"
                  >{{ customerErrors.phone_1}}</span>
                  <div class="form-group d-flex align-items-center">
                    <label for="number" style="width: 100px;">Phone:</label>
                    <input
                      id="number"
                      v-model="modalCustomerForm.phone_1"
                      class="form-control form-control-sm"
                      style="width: 100%;"
                    />
                  </div>
                  <span
                    class="text-danger text-xs"
                    style="margin-left: 80px;"
                    v-if="customerErrors.address"
                  >{{ customerErrors.address}}</span>
                  <div class="form-group d-flex align-items-center">
                    <label for="address" style="width: 100px;">Address:</label>
                    <input
                      id="address"
                      v-model="modalCustomerForm.address"
                      class="form-control form-control-sm"
                      style="width: 100%;"
                    />
                  </div>
                  <hr />
                  <div class="flex justify-center mt-3">
                    <button class="btn btn-dark" @click="submitCustomerModalForm">Save</button>
                  </div>
                </div>
              </template>
            </Modal>

            <Modal :show="showProductModal" @close="showProductModal = false">
              <template #default>
                <div class="p-6">
                  <button type="button" class="btn-close btn-md float-right" @click="showProductModal = false"></button>
                  <div>
                    <h2 class="text-xl font-bold flex justify-center">Create New Product</h2>
                  </div>

                  <hr class="mb-3" />
                  <span
                    class="text-danger text-xs"
                    style="margin-left: 115px;"
                    v-if="proErrors.name"
                  >{{ proErrors.name}}</span>
                  <div class="form-group d-flex align-items-center">
                    <label for="name" style="width: 150px;">Product Name:</label>
                    <input
                      id="name"
                      v-model="modalProductForm.name"
                      class="form-control form-control-sm"
                      style="width: 100%;"
                    />
                  </div>
                  <span
                    class="text-danger text-xs"
                    style="margin-left: 115px;"
                    v-if="proErrors.serial_no"
                  >{{ proErrors.serial_no}}</span>
                  <div class="form-group d-flex align-items-center">
                    <label for="serial_no" style="width: 150px;">Serial no:</label>
                    <input
                      type="text"
                      id="serial_no"
                      v-model="modalProductForm.serial_no"
                      class="form-control form-control-sm"
                      style="width: 100%;"
                    />
                  </div>

                  <hr />
                  <div class="flex justify-center mt-3">
                    <button class="btn btn-dark" @click="submitProductModalForm">Save</button>
                  </div>
                </div>
              </template>
            </Modal>

            <Modal :show="showRespModal" @close="showRespModal = false">
              <template #default>
                <div class="p-6">
                  <button type="button" class="btn-close btn-md float-right" @click="showRespModal = false"></button>
                  <div>
                    <h2 class="text-xl font-bold flex justify-center">Create New Responsible</h2>
                  </div>

                  <hr class="mb-3" />
                  <span
                    class="text-danger text-xs"
                    style="margin-left: 115px;"
                    v-if="respErrors.name"
                  >{{ respErrors.name}}</span>
                  <div class="form-group d-flex align-items-center">
                    <label for="name" style="width: 150px;">Name:</label>
                    <input
                      id="name"
                      v-model="modalRespForm.name"
                      class="form-control form-control-sm"
                      style="width: 100%;"
                    />
                  </div>
                  <span
                    class="text-danger text-xs"
                    style="margin-left: 115px;"
                    v-if="respErrors.email"
                  >{{ respErrors.email}}</span>
                  <div class="form-group d-flex align-items-center mt-2">
                    <label for="email" style="width: 150px;">Email:</label>
                    <input
                      id="email"
                      v-model="modalRespForm.email"
                      class="form-control form-control-sm"
                      style="width: 100%;"
                    />
                  </div>
                  <hr />
                  <div class="flex justify-center mt-3">
                    <button class="btn btn-dark" @click="submitRespModalForm">Save</button>
                  </div>
                </div>
              </template>
            </Modal>

            <div class="container">
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
                          value="1.00"
                        />
                      </div>
                      <div class="form-group d-flex align-items-center">
                        <label for="return" class="mr-2" style="width: 150px;">Return</label>
                        <v-select v-model="form.return" id="return" style="width: 100%;"></v-select>
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
                          class="ml-4 mt-1 form-control rounded"
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
                        >Scheduled Date</label>
                        <VueDatePicker
                          v-model="form.scheduled_date"
                          id="scheduled_date"
                          :is-24="false"
                          style="width: 100%;"
                          @input="formatDate"
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
                  <div v-if="currentTab === 'parts' && showAddLineField" class="table-responsive mt-3">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Type</th>
                          <th>Product Type</th>
                          <th>Demand</th>
                          <th>Done</th>
                          <th>Used</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(line, index) in form.lines" :key="index">
                          <td>
                            <div class="form-group">
                              <select v-model="line.type" class="form-control form-control-sm">
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
                              ></v-select>
                              <!-- General lines error for custom validation rule -->
                              <div class="text-danger text-xs" v-if="errors && errors['lines']">
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
                                class="ml-5 form-control rounded"
                                v-model="line.used"
                              />
                              <div
                                class="text-danger text-xs"
                                v-if="errors && errors[`lines.${index}.used`]"
                              >
                                <span>{{ errors[`lines.${index}.used`] }}</span>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <button @click="removeLine(index)" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                              </button>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div v-if="currentTab === 'parts'" class="mt-3">
                    <button
                      type="button"
                      class="btn btn-outline-primary btn-sm"
                      @click="addLine"
                    >Add a line</button>
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
import { Head, useForm } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";
import Modal from "@/Components/Modal.vue";

import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

// Vue.component("v-select", vSelect);

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
  cus_id: "",
  pro_id: "",
  product_quantity: "",
  return: "",
  warranty: false,
  scheduled_date: new Date().toLocaleString(),
  responsible: props.currentUserId || null,
  tags: "",
  component_status: true,
  lines: [
    {
      type: "add",
      product_id: "",
      demand: "",
      done: "",
      used: ""
    }
  ],
  repair_notes: "",
  operation_type: "sample:Repairs"
});

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

function removeLine(index) {
  form.lines.splice(index, 1);
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

function submit() {
  formatDate();
  form.post("/services", {
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
  background-color: #337ab7; /* or any other color you prefer */
  color: #ffffff; /* or any other color you prefer */
}
</style>
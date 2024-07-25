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
          <form @submit.prevent="submit(serviceInvoice.id)">
            <!-- <div class="d-flex justify-content-end align-items-center mb-3">
              <ul class="status-tabs d-flex align-items-center mb-0 me-3">
                <li class="status-tab status-tab-selected bg-secondary">New</li>
                <li class="status-tab">Confirmed</li>
                <li class="status-tab">Under Repair</li>
                <li class="status-tab">Repaired</li>
              </ul>
            </div>-->
            <button class="btn btn-dark btn-md mb-5" onclick="window.history.back();">
  Go Back
</button>
            <button type="submit" class="btn btn-dark btn-md mb-5 ml-3">Update</button>
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
                        <label for="customer" class="mr-5" style="width: 150px;">Customer</label>
                        <v-select
                          v-model="form.cus_id"
                          id="customer"
                          style="width:100%;"
                          :options="customers"
                          label="name"
                          :reduce="(customers) => customers.id"
                        ></v-select>
                        <div class="ml-4" v-if="form.cus_id">
                          <p>{{ getCustomerAddressAndPhone(form.cus_id) }}</p>
                        </div>
                      </div>

                      <span
                        class="text-danger text-xs"
                        style="margin-left:130px;"
                        v-if="errors.place_of_supply"
                      >{{ errors.place_of_supply }}</span>
                      <div class="form-group d-flex align-items-start">
                        <label
                          for="place_of_supply"
                          class="mr-2"
                          style="width: 150px;"
                        >Place of Supply</label>
                        <v-select
                          v-model="form.place_of_supply"
                          :options="statesOptions"
                          label="label"
                          placeholder="Select a state"
                          style="width: 100%;"
                        ></v-select>
                      </div>
                      <div class="form-group d-flex align-items-start">
                        <label for="gst" class="mr-2" style="width: 150px;">GST Treatment</label>
                        <select v-model="form.gst" id="gst" class="form-control form-control-sm">
                          <option value="consumer">consumer</option>
                          <option value>Registered Business - Regular</option>
                          <option value>Registered Business - Composition</option>
                          <option value>Unregistered Business</option>
                          <option value>Overseas</option>
                          <option value>Special Economic Zone</option>
                          <option value>Deemed Export</option>
                          <option value>UIN Holders</option>
                        </select>
                        <div class="text-danger text-xs" v-if="errors.gst">
                          <span>{{ errors.gst }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <span
                        class="text-danger text-xs"
                        style="margin-left:130px;"
                        v-if="errors.invoice_date"
                      >{{ errors.invoice_date }}</span>
                      <div class="form-group d-flex align-items-start">
                        <label for="invoice_date" class="mr-2" style="width: 150px;">Invoice Date</label>
                        <VueDatePicker
                          v-model="form.invoice_date"
                          id="invoice_date"
                          :enable-time-picker="false"
                          style="width: 100%; background-color:white;"
                          :max-date="new Date()"
                        />
                      </div>
                      <div class="form-group d-flex align-items-start">
                        <label
                          for="payment_reference"
                          class="mr-2"
                          style="width: 150px;"
                        >Payment Reference</label>
                        <input
                          type="text"
                          v-model="form.payment_reference"
                          id="payment_reference"
                          class="form-control form-control-sm rounded"
                        />
                        <div class="text-danger text-xs" v-if="errors.payment_reference">
                          <span>{{ errors.payment_reference }}</span>
                        </div>
                      </div>
                      <span
                        class="text-danger text-xs"
                        style="margin-left:130px;"
                        v-if="errors.invoice_date"
                      >{{ errors.invoice_date }}</span>
                      <div v-if="!form.due_date && !form.payment_terms" class="form-group d-flex align-items-start">
                        <label  for="due_date" class="mr-2" style="width: 150px;">Due Date</label>
                        <span  style="width:200px;"><VueDatePicker
                          
                          v-model="form.due_date"
                          id="due_date"
                          :enable-time-picker="false"
                          
                          @input="formatDate"
                        /></span>
                        <p class="ml-2 mr-2" >or</p>
                        <span  style="width: 60%;"><v-select
                        v-model="form.payment_terms"
                          :options="paymentOptions"
                          placeholder="Select Payment Terms"
                          
                        ></v-select></span>
                        <div class="text-danger text-xs" v-if="errors.payment_terms">
                          <span>{{ errors.payment_terms }}</span>
                        </div>
                      </div>
                      <div v-if="form.due_date" class="form-group d-flex align-items-start">
                        <label  for="due_date" class="mr-2" style="width: 150px;">Due Date</label>
                        <VueDatePicker
                          v-model="form.due_date"
                          id="due_date"
                          :enable-time-picker="false"
                          style="width:100%"
                          @input="formatDate"
                        />
                        <div class="text-danger text-xs" v-if="errors.payment_terms">
                          <span>{{ errors.payment_terms }}</span>
                        </div>
                      </div>
                      <div v-if="form.payment_terms" class="form-group d-flex align-items-start">
                        <label for="due_date" class="mr-2"
                          style="width: 150px;">Payment Terms</label>
                        
                        <v-select
                          v-model="form.payment_terms"
                          :options="paymentOptions"
                          placeholder="Select Payment Terms"
                          style="width: 100%;"
                        ></v-select>
                        <div class="text-danger text-xs" v-if="errors.payment_terms">
                          <span>{{ errors.payment_terms }}</span>
                        </div>
                      </div>
                      <div v-if="form.delivery_date" class="form-group d-flex align-items-center">
                        <label for="delivery_date" class="mr-2" style="width: 120px;">Delivery Date</label>
                        <VueDatePicker
                          v-model="form.delivery_date"
                          :enable-time-picker="false"
                          id="delivery_date"
                          style="width: 80%;"
                        />
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
                        >Invoice Lines</button>
                        <button
                          type="button"
                          class="btn btn-sm"
                          :class="{ 'btn-dark': currentTab === 'miscellaneous', 'btn-light': currentTab!== 'miscellaneous' }"
                          @click="changeTab('miscellaneous')"
                        >Other Info</button>
                      </div>
                    </div>
                  </div>
                  <!-- Parts Tab -->
                  <div v-if="currentTab === 'parts'" class="table-responsive mt-3">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Product</th>
                          <th>Description</th>
                          <th>Quantity</th>
                          <th>Unit Price</th>
                          <th>Taxes</th>
                          <th>Tax excl.</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <template v-for="(line, index) in form.lines" :key="index">
                          <tr v-if="line.type === 'product'">
                            <td>
                              <div class="form-group">
                                <v-select
                                  v-model="line.pro_id"
                                  id="product_type"
                                  :options="products"
                                  label="name"
                                  :reduce="(products) => products.id"
                                  placeholder="search"
                                  style="width: 100%;"
                                ></v-select>
                                <div
                                  class="text-danger text-xs"
                                  v-if="errors && errors['lines'] && index === form.lines.length - 1"
                                >
                                  <span v-for="error in errors['lines']" :key="error">{{ error }}</span>
                                </div>
                                <div
                                  class="text-danger text-xs"
                                  v-if="errors && errors[`lines.${index}.pro_id`]"
                                >
                                  <span>{{ errors[`lines.${index}.pro_id`] }}</span>
                                </div>
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <input
                                  type="text"
                                  v-model="line.label"
                                  class="form-control form-control-sm rounded"
                                />
                                <div
                                  class="text-danger text-xs"
                                  v-if="errors && errors[`lines.${index}.label`]"
                                >
                                  <span>{{ errors[`lines.${index}.label`] }}</span>
                                </div>
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <input
                                  type="number"
                                  step="0.01"
                                  v-model="line.quantity"
                                  class="form-control form-control-sm rounded"
                                />
                                <div
                                  class="text-danger text-xs"
                                  v-if="errors && errors[`lines.${index}.quantity`]"
                                >
                                  <span>{{ errors[`lines.${index}.quantity`] }}</span>
                                </div>
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <input
                                  type="number"
                                  step="0.01"
                                  class="form-control form-control-sm rounded"
                                  v-model="line.unit_price"
                                />
                                <div
                                  class="text-danger text-xs"
                                  v-if="errors && errors[`lines.${index}.unit_price`]"
                                >
                                  <span>{{ errors[`lines.${index}.unit_price`] }}</span>
                                </div>
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <multiselect
                                  v-model="line.taxes"
                                  :options="taxOptions"
                                  :hide-selected="true"
                                  :multiple="true"
                                  :searchable="false"
                                  :close-on-select="false"
                                  :clear-on-select="false"
                                  :preserve-search="true"
                                  label="name"
                                  track-by="name"
                                ></multiselect>
                                <div
                                  class="text-danger text-xs"
                                  v-if="errors && errors[`lines.${index}.taxes`]"
                                >
                                  <span>{{ errors[`lines.${index}.taxes`] }}</span>
                                </div>
                              </div>
                            </td>
                            <td>
                              <div
                                class="form-group"
                              >₹ {{ calculateTaxExcl(line.quantity, line.unit_price).toFixed(2) || '0.00' }}</div>
                            </td>
                            <td>
                              <div class="form-group">
                                <button
                                  type="button"
                                  @click="removeLine(index,line.id)"
                                  class="btn btn-danger btn-sm"
                                >
                                  <i class="bi bi-trash"></i>
                                </button>
                              </div>
                            </td>
                          </tr>

                          <tr v-if="line.type === 'section'">
                            <td colspan="7">
                              <div class="row">
                                <div class="col-md-11">
                                  <div class="form-group">
                                    <input
                                      type="text"
                                      placeholder="Section"
                                      class="form-control form-control-sm rounded"
                                      v-model="line.section"
                                    />
                                    <div class="text-danger text-xs" v-if="errors.section">
                                      <span>{{ errors.section }}</span>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-1">
                                  <div class="form-group">
                                    <button
                                      type="button"
                                      @click="removeLine(index,line.id)"
                                      class="btn btn-danger btn-sm ml-4"
                                    >
                                      <i class="bi bi-trash"></i>
                                    </button>
                                  </div>
                                </div>
                              </div>
                            </td>
                          </tr>

                          <tr v-if="line.type === 'note'">
                            <td colspan="7">
                              <div class="row">
                                <div class="col-md-11">
                                  <div class="form-group">
                                    <input
                                      type="text"
                                      placeholder="Notes"
                                      class="form-control form-control-sm rounded"
                                      v-model="line.note"
                                    />
                                    <div class="text-danger text-xs" v-if="errors.note">
                                      <span>{{ errors.note }}</span>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-1">
                                  <div class="form-group">
                                    <button
                                      type="button"
                                      @click="removeLine(index,line.id)"
                                      class="btn btn-danger btn-sm ml-4"
                                    >
                                      <i class="bi bi-trash"></i>
                                    </button>
                                  </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        </template>
                      </tbody>
                    </table>
                  </div>
                  <div class="row mt-4" v-if="currentTab === 'parts'">
                    <div class="col-auto">
                      <button
                        type="button"
                        class="btn btn-outline-primary btn-sm"
                        @click="addLine"
                      >Add a line</button>
                    </div>
                    <div class="col-auto ml-1">
                      <button
                        type="button"
                        class="btn btn-outline-primary btn-sm"
                        @click="addSection"
                      >Add a Section</button>
                    </div>
                    <div class="col-auto ml-1">
                      <button
                        type="button"
                        class="btn btn-outline-primary btn-sm"
                        @click="addNote"
                      >Add a Note</button>
                    </div>
                  </div>
                  <div class="row mt-5 mr-5 mb-5" v-if="currentTab === 'parts'">
                    <div class="col-md-4 offset-md-8">
                      <div v-if="hasSelectedTaxes" class="row mb-2">
                        <hr />
                        <div class="col-sm-5 col-md-6 mt-2">
                          <h1>Untaxed Amount:</h1>
                        </div>
                        <div
                          class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 text-end"
                        >₹ {{ totalUntaxedAmount.toFixed(2) }}</div>
                      </div>
                      <div v-if="hasSelectedTaxes" class="row mt-2 mb-2">
                        <div class="col-sm-5 col-md-6">
                          <h1>TaxedAmount:</h1>
                        </div>
                        <div
                          class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 text-end"
                        >₹ {{ totalTaxedAmount.toFixed(2) }}</div>
                      </div>
                      <hr />
                      <div class="row mt-2 mb-2">
                        <div class="col-sm-5 col-md-6">
                          <strong>Total:</strong>
                        </div>
                        <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 text-end">
                          <b class="text-xl">₹ {{ totalAmount.toFixed(2) }}</b>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Repair Notes Tab -->

                  <!-- Miscellaneous Tab -->
                  <div v-if="currentTab === 'miscellaneous'" class="mt-5">
                    <div class="row">
                      <div class="col-md-6">
                        <h4 class="mb-1" style="font-weight:bold;">INVOICE</h4>
                        <hr />
                        <div class="form-group d-flex align-items-center mt-3">
                          <label
                            for="customer_reference"
                            class="mr-2"
                            style="width: 150px;"
                          >Customer Reference</label>
                          <input
                            id="customer_reference"
                            v-model="form.customer_reference"
                            class="form-control form-control-sm"
                            style="width: 100%;"
                          />
                        </div>
                        <div class="form-group d-flex align-items-center mt-3">
                          <label for="sales_person" class="mr-2" style="width: 150px;">Salesperson</label>
                          <v-select
                            v-model="form.sales_person"
                            id="sales_person"
                            :options="users"
                            label="name"
                            :reduce="(users) => users.id"
                            placeholder="search"
                            style="width: 100%;"
                          ></v-select>
                        </div>
                        <div class="form-group d-flex align-items-center">
                          <label for="sales_team" class="mr-2" style="width: 150px;">Sales Team</label>
                          <input
                            id="sales_team"
                            v-model="form.sales_team"
                            class="form-control form-control-sm"
                            style="width: 100%;"
                          />
                        </div>
                        <div class="form-group d-flex align-items-center">
                          <label
                            for="recipient_bank"
                            class="mr-2"
                            style="width: 150px;"
                          >Recipient Bank</label>
                          <input
                            id="recipient_bank"
                            v-model="form.recipient_bank"
                            class="form-control form-control-sm"
                            style="width: 100%;"
                          />
                        </div>
                        <div class="form-group d-flex align-items-center">
                          <label
                            for="delivery_date"
                            class="mr-2"
                            style="width: 120px;"
                          >Delivery Date</label>
                          <VueDatePicker
                            v-model="form.delivery_date"
                            :enable-time-picker="false"
                            id="delivery_date"
                            style="width: 83%;"
                          />
                        </div>
                      </div>
                      <div class="col-md-6">
                        <h4 class="mb-1" style=" font-weight:bold;">ACCOUNTING</h4>
                        <hr />
                        <div class="form-group d-flex align-items-center mt-3">
                          <label for="incoterm" class="mr-2" style="width: 150px;">Incoterm</label>
                          <select
                            v-model="form.incoterm"
                            id="incoterm"
                            class="form-control form-control-sm"
                          >
                            <option value>Select Incoterm</option>
                            <option value="EXW">EXW - Ex Works</option>
                            <option value="FCA">FCA - Free Carrier</option>
                            <option value="FAS">FAS - Free Alongside Ship</option>
                            <option value="FOB">FOB - Free On Board</option>
                            <option value="CFR">CFR - Cost and Freight</option>
                            <option value="CIF">CIF - Cost, Insurance and Freight</option>
                            <option value="CPT">CPT - Carriage Paid To</option>
                            <option value="CIP">CIP - Carriage and Insurance Paid To</option>
                            <option value="DPU">DPU - Delivered at Place Unloaded</option>
                            <option value="DAP">DAP - Delivered at Place</option>
                            <option value="DDP">DDP - Delivered Duty Paid</option>
                          </select>
                        </div>
                        <div class="form-group d-flex align-items-center">
                          <label
                            for="incoterm_location"
                            class="mr-2"
                            style="width: 150px;"
                          >Incoterm Location</label>
                          <input
                            id="incoterm_location"
                            v-model="form.incoterm_location"
                            class="form-control form-control-sm"
                            style="width: 100%;"
                          />
                        </div>
                        <div class="form-group d-flex align-items-center">
                          <label
                            for="fiscal_position"
                            class="mr-2"
                            style="width: 150px;"
                          >Fiscal Position</label>
                          <select
                            id="fiscal_position"
                            v-model="form.fiscal_position"
                            class="form-control form-control-sm"
                          >
                            <option value>Select Fiscal Position</option>
                            <option value="Within Kerala">Within Kerala</option>
                            <option value="Inter State">Inter State</option>
                            <option value="Export/SEZ">Export/SEZ</option>
                            <option value="LUT - Export/SEZ">LUT - Export/SEZ</option>
                            <option value="Export">Export</option>
                            <option value="Reverse charge Intra State">Reverse charge Intra State</option>
                            <option value="Reverse charge Inter State">Reverse charge Inter State</option>
                          </select>
                        </div>
                        <div class="form-group d-flex align-items-center">
                          <label
                            for="cash_round_method"
                            class="mr-2"
                            style="width: 150px;"
                          >Cash Rounding Method</label>
                          <select
                            id="cash_round_method"
                            v-model="form.source_document"
                            class="form-control form-control-sm"
                            style="width: 100%;"
                          >
                            <option value>Select method</option>
                            <option value="half-up">Half-Up</option>
                          </select>
                        </div>
                        <div class="form-group d-flex align-items-center">
                          <label for="auto_post" class="mr-2" style="width: 150px;">Auto-post</label>
                          <select
                            id="auto_post"
                            v-model="form.auto_post"
                            class="form-control form-control-sm"
                            style="width: 100%;"
                          >
                            <option value>Select Auto-post</option>
                            <option value="No">No</option>
                            <option value="At Date">At Date</option>
                            <option value="Monthly">Monthly</option>
                            <option value>Quarterly</option>
                            <option value>Yearly</option>
                          </select>
                        </div>
                        <div class="form-group d-flex align-items-center">
                          <label for="to_check" class="mr-2" style="width: 150px;">To Check</label>
                          <input type="checkbox" id="to_check" v-model="form.to_check" />
                        </div>
                      </div>
                    </div>
                  </div>

                  <input v-model="form.total" type="hidden" />
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
import { ref, computed, watch } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.css";

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
  invoice_id: {
    type: Number,
    required: true
  },
  serviceInvoice: {
    type: Object,
    required: true
  },
  serviceInvoiceLine: {
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
  taxes: {
    type: Array,
    default: () => []
  },
  serviceInvoiceSection: Array,
  serviceInvoiceNote: Array,
  errors: Object
});

const formatDecimal = value => {
  return parseFloat(value).toFixed(2);
};
const taxOptions = ref(props.taxes.map(tax => ({ name: tax.name })));
// const productIds = props.serviceLines.map(line => line.product.id);

const getTax = taxes => {
  if (typeof taxes === "string") {
    try {
      taxes = JSON.parse(taxes);
    } catch (e) {
      console.error("Invalid JSON string for taxes:", taxes);
      taxes = [];
    }
  }
  return Array.isArray(taxes) ? taxes.map(tax => ({ name: tax })) : "";
};

const part1 = props.serviceInvoiceLine
  .filter(line => line.product !== null && line.product !== undefined)
  .map(line => ({
    type: "product",
    id: line.id,
    pro_id: line.product?.id,
    label: line.description,
    quantity: line.quantity,
    unit_price: line.unit_price,
    taxes: getTax(line.taxes)
  }));

const part2 = props.serviceInvoiceSection.map(line => ({
  type: "section",
  id: line.id,
  section: line.section
}));

const part3 = props.serviceInvoiceNote.map(line => ({
  type: "note",
  id: line.id,
  note: line.note
}));

const {
  cus_id,
  place_of_supply,
  gst,
  invoice_date,
  payment_reference,
  due_date,
  payment_terms,
  total,
  tax_amt,
  untax_amt,
  customer_reference,
  sales_person,
  sales_team,
  recipient_bank,
  delivery_date,
  incoterm,
  incoterm_location,
  fiscal_position,
  cash_round_method,
  auto_post
} = props.serviceInvoice;

const form = useForm({
  service_id: props.serviceInvoice.service_id,
  quotation_id: props.invoice_id,
  cus_id,
  place_of_supply,
  gst,
  invoice_date,
  payment_reference,
  due_date,
  payment_terms,
  total,
  tax_amt,
  untax_amt,
  customer_reference,
  sales_person,
  sales_team,
  recipient_bank,
  delivery_date,
  incoterm,
  incoterm_location,
  fiscal_position,
  cash_round_method,
  auto_post,
  to_check:!!props.serviceInvoice.to_check,
  lines: [...part1, ...part2, ...part3].sort((a, b) => a.id - b.id)
});

const paymentOptions = [
  "Immediate Payment",
  "15 Days",
  "21 Days",
  "30 Days",
  "45 Days",
  "End of Following Month",
  "10 Days after End of Next Month",
  "30% Now, Balance 60 Days"
];

async function removeLine(index, lineId) {
  if (lineId === undefined) {
    form.lines.splice(index, 1);
  } else {
    try {
      console.log("line id", lineId);
      await axios.delete(`/invoice/${lineId}`);
      form.lines.splice(index, 1);
    } catch (error) {
      console.error("Error deleting line:", error);
    }
  }
}

const selectedTaxes = computed(() => {
  return form.lines.flatMap(line => line.taxes);
});

const hasSelectedTaxes = computed(() => selectedTaxes.value.length > 0);

const calculateTaxExcl = (quantity, unitPrice) => {
  return quantity && unitPrice ? quantity * unitPrice : 0;
};

const totalUntaxedAmount = computed(() => {
  return form.lines.reduce((total, line) => {
    return total + calculateTaxExcl(line.quantity, line.unit_price);
  }, 0);
});
watch(
  totalUntaxedAmount,
  newUnTax => {
    form.untax_amt = newUnTax;
  },
  { immediate: true }
);

// Compute total taxed amount
const totalTaxedAmount = computed(() => {
  return form.lines.reduce((total, line) => {
    const taxes = Array.isArray(line.taxes) ? line.taxes : [];
    const lineTaxAmount = taxes.reduce((taxTotal, tax) => {
      if (!tax || typeof tax.name !== "string") {
        return taxTotal; // Skip invalid tax values
      }
      const taxPercentage = parseFloat(tax.name.replace("%", ""));
      if (isNaN(taxPercentage)) {
        return taxTotal; // Skip invalid tax values
      }
      return (
        taxTotal +
        calculateTaxExcl(line.quantity, line.unit_price) * (taxPercentage / 100)
      );
    }, 0);
    return total + lineTaxAmount;
  }, 0);
});

watch(
  totalTaxedAmount,
  newTax => {
    form.tax_amt = newTax;
  },
  { immediate: true }
);

const totalAmount = computed(() => {
  return totalUntaxedAmount.value + totalTaxedAmount.value;
});
watch(
  totalAmount,
  newTotal => {
    form.total = newTotal;
  },
  { immediate: true }
);

const getCustomerAddressAndPhone = cus_id => {
  const customer = props.customers.find(c => c.id === cus_id);
  if (customer) {
    return `${customer.address}, ${customer.phone_1}`;
  } else {
    return "";
  }
};

const currentTab = ref("parts");

const line = ref([]);

function changeTab(tab) {
  currentTab.value = tab;
}

const errors = ref({});

function formatDate() {
  if (form.delivery_date) {
    // Format the date to YYYY-MM-DD HH:MM:SS
    const dateObj = new Date(form.delivery_date);
    const formattedDate = `${dateObj.getFullYear()}-${padNumber(
      dateObj.getMonth() + 1
    )}-${padNumber(dateObj.getDate())} ${padNumber(
      dateObj.getHours()
    )}:${padNumber(dateObj.getMinutes())}:${padNumber(dateObj.getSeconds())}`;
    form.delivery_date = formattedDate;
  }
  if (form.invoice_date) {
    // Format the date to YYYY-MM-DD HH:MM:SS
    const dateObj = new Date(form.invoice_date);
    const formattedDate = `${dateObj.getFullYear()}-${padNumber(
      dateObj.getMonth() + 1
    )}-${padNumber(dateObj.getDate())} ${padNumber(
      dateObj.getHours()
    )}:${padNumber(dateObj.getMinutes())}:${padNumber(dateObj.getSeconds())}`;
    form.invoice_date = formattedDate;
  }
  if (form.due_date) {
    // Format the date to YYYY-MM-DD HH:MM:SS
    const dateObj = new Date(form.due_date);
    const formattedDate = `${dateObj.getFullYear()}-${padNumber(
      dateObj.getMonth() + 1
    )}-${padNumber(dateObj.getDate())} ${padNumber(
      dateObj.getHours()
    )}:${padNumber(dateObj.getMinutes())}:${padNumber(dateObj.getSeconds())}`;
    form.due_date = formattedDate;
  }
}
function padNumber(num) {
  return num.toString().padStart(2, "0");
}

function submit(id) {
  formatDate();
  form.lines.forEach(line => {
    if (Array.isArray(line.taxes)) {
      line.taxes = JSON.stringify(line.taxes.map(tax => tax.name));
    }
  });
  form.put("/invoice/" + id, {
    onSuccess: () => {
      console.log("Form submitted successfully");
    },
    onError: formErrors => {
      console.log("Form submission failed", formErrors);
      errors.value = formErrors || {};
    }
  });
}

function addLine() {
  const hasEmptySection = form.lines.some(
    line => line.type === "section" && !line.section
  );
  const hasEmptyNote = form.lines.some(
    line => line.type === "note" && !line.note
  );

  if (
    !hasEmptySection &&
    !hasEmptyNote &&
    !form.lines.some(line => line.type === "product" && !line.pro_id)
  ) {
    form.lines.push({
      type: "product",
      pro_id: null,
      label: "",
      quantity: 1 || null,
      unit_price: 1 || null,
      taxes: []
    });
  } else {
    if (hasEmptySection) {
      errors.value.section = "Please fill in the section.";
      setTimeout(() => {
        errors.value.section = "";
      }, 3000);
    } else if (hasEmptyNote) {
      errors.value.note = "Please fill in the note.";
      setTimeout(() => {
        errors.value.note = "";
      }, 3000);
    } else {
      errors.value = {
        [`lines.${form.lines.length - 1}.pro_id`]: "Product is required."
      };
      setTimeout(() => {
        errors.value = "";
      }, 3000);
    }
  }
}

function addSection() {
  const hasEmptyProduct = form.lines.some(
    line => line.type === "product" && !line.pro_id
  );
  const hasEmptyNote = form.lines.some(
    line => line.type === "note" && !line.note
  );

  if (
    !hasEmptyProduct &&
    !hasEmptyNote &&
    !form.lines.some(line => line.type === "section" && !line.section)
  ) {
    form.lines.push({
      type: "section",
      section: ""
    });
  } else {
    if (hasEmptyProduct) {
      errors.value = {
        [`lines.${form.lines.length - 1}.pro_id`]: "Product is required."
      };
      setTimeout(() => {
        errors.value = "";
      }, 3000);
    } else if (hasEmptyNote) {
      errors.value.note = "Please fill in the note.";
      setTimeout(() => {
        errors.value.note = "";
      }, 3000);
    } else {
      errors.value.section = "Please fill in the section.";
      setTimeout(() => {
        errors.value.section = "";
      }, 3000);
    }
  }
}

function addNote() {
  const hasEmptyProduct = form.lines.some(
    line => line.type === "product" && !line.pro_id
  );
  const hasEmptySection = form.lines.some(
    line => line.type === "section" && !line.section
  );

  if (
    !hasEmptyProduct &&
    !hasEmptySection &&
    !form.lines.some(line => line.type === "note" && !line.note)
  ) {
    form.lines.push({
      type: "note",
      note: ""
    });
  } else {
    if (hasEmptyProduct) {
      errors.value = {
        [`lines.${form.lines.length - 1}.pro_id`]: "Product is required."
      };
      setTimeout(() => {
        errors.value = "";
      }, 3000);
    } else if (hasEmptySection) {
      errors.value.section = "Please fill in the section.";
      setTimeout(() => {
        errors.value.section = "";
      }, 3000);
    } else {
      errors.value.note = "Please fill in the note.";
      setTimeout(() => {
        errors.value.note = "";
      }, 3000);
    }
  }
}

// Set minimum date and time to current date and time
const now = new Date();
const minDateTime = ref(now.toISOString().slice(0, 16));

// Define an array of options for v-select
const statesOptions = [
  { label: "Andaman and Nicobar", abbreviation: "AN", country: "India" },
  { label: "Andhra Pradesh", abbreviation: "AP", country: "India" },
  { label: "Arunachal Pradesh", abbreviation: "AR", country: "India" },
  { label: "Assam", abbreviation: "AS", country: "India" },
  { label: "Bihar", abbreviation: "BR", country: "India" },
  { label: "Chattisgarh", abbreviation: "CG", country: "India" },
  { label: "Chandigarh", abbreviation: "CH", country: "India" },
  { label: "Daman and Diu", abbreviation: "DD", country: "India" },
  { label: "Delhi", abbreviation: "DL", country: "India" },
  {
    label: "Dadra and Nagar Haveli and Daman and Diu",
    abbreviation: "DN",
    country: "India"
  },
  { label: "Goa", abbreviation: "GA", country: "India" },
  { label: "Gujarat", abbreviation: "GJ", country: "India" },
  { label: "Himachal Pradesh", abbreviation: "HP", country: "India" },
  { label: "Haryana", abbreviation: "HR", country: "India" },
  { label: "Foreign Country", abbreviation: "IN_OC", country: "India" },
  { label: "Other Territory", abbreviation: "IN_OT", country: "India" },
  { label: "Jharkhand", abbreviation: "JH", country: "India" },
  { label: "Jammu and Kashmir", abbreviation: "JK", country: "India" },
  { label: "Karnataka", abbreviation: "KA", country: "India" },
  { label: "Kerala", abbreviation: "KL", country: "India" },
  { label: "Ladakh", abbreviation: "LA", country: "India" },
  { label: "Lakshadweep", abbreviation: "LD", country: "India" },
  { label: "Maharashtra", abbreviation: "MH", country: "India" },
  { label: "Meghalaya", abbreviation: "ML", country: "India" },
  { label: "Manipur", abbreviation: "MN", country: "India" },
  { label: "Madhya Pradesh", abbreviation: "MP", country: "India" },
  { label: "Mizoram", abbreviation: "MZ", country: "India" },
  { label: "Nagaland", abbreviation: "NL", country: "India" },
  { label: "Orissa", abbreviation: "OR", country: "India" },
  { label: "Punjab", abbreviation: "PB", country: "India" },
  { label: "Puducherry", abbreviation: "PY", country: "India" },
  { label: "Rajasthan", abbreviation: "RJ", country: "India" },
  { label: "Sikkim", abbreviation: "SK", country: "India" },
  { label: "Tamil Nadu", abbreviation: "TN", country: "India" },
  { label: "Tripura", abbreviation: "TR", country: "India" },
  { label: "Telangana", abbreviation: "TS", country: "India" },
  { label: "Uttarakhand", abbreviation: "UK", country: "India" },
  { label: "Uttar Pradesh", abbreviation: "UP", country: "India" },
  { label: "West Bengal", abbreviation: "WB", country: "India" }
];
</script>

<style scoped>
.pl-5:hover .btn,
.pl-2:hover .btn {
  background-color: #f8f9fa;
}
.text-danger {
  color: red;
}

.text-xs {
  font-size: 0.75rem;
}

.card-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.status-tabs {
  display: flex;
  list-style-type: none;
  padding: 0;
  margin: 0;
}

.status-tab {
  padding: 10px 20px;
  border: 1px solid #ccc;
  margin-right: 5px;
  cursor: pointer;
  background-color: #f9f9f9;
}

.status-tab:last-child {
  margin-right: 0;
}

.status-tab-selected {
  color: white;
}
</style>
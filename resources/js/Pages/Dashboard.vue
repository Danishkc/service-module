<template>
  <Head title="Dashboards" />
  <NavLink>
    <div class="alert alert-success" v-if="$page.props.flash.message">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong>{{$page.props.flash.message}}</strong>
    </div>
    <div class="alert alert-danger" v-if="$page.props.flash.error">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong>{{$page.props.flash.error}}</strong>
    </div>

    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="submit">
            <div>
              <label for="customer">Customer</label>
              <select v-model="form.customer" id="customer">
                <option v-for="customer in customers" :key="customer.id">{{ customer.name }}</option>
              </select>
              <div class="text-danger text-xs" v-if="errors.customer">
                <strong>{{errors.customer}}</strong>
              </div>
            </div>
            <div>
              <label for="product_to_repair">Product to Repair</label>
              <multiselect
                v-model="selectedProductRepair"
                :options="products"
                :multiple="true"
                :searchable="true"
                label="name"
                track-by="id"
                placeholder="Select products"
                id="product_to_repair"
              ></multiselect>
              <div class="text-danger text-xs" v-if="errors.product_to_repair">
                <strong>{{errors.product_to_repair}}</strong>
              </div>
            </div>
            <div>
              <label for="product_qty">Product Quantity</label>
              <input type="number" v-model="form.product_qty" id="product_qty" />
              <div class="text-danger text-xs" v-if="errors.product_qty">
                <strong>{{errors.product_qty}}</strong>
              </div>
            </div>
            <div>
              <label for="return">Return</label>
              <input type="text" v-model="form.return" id="return" />
              <div class="text-danger text-xs" v-if="errors.return">
                <strong>{{errors.return}}</strong>
              </div>
            </div>
            <div>
              <label for="warranty">Under Warranty</label>
              <input type="checkbox" v-model="form.warranty" id="warranty" />
              <div class="text-danger text-xs" v-if="errors.warranty">
                <strong>{{errors.warranty}}</strong>
              </div>
            </div>
            <div>
              <label for="scheduled_date">Scheduled Date</label>
              <input
                type="datetime-local"
                :min="minDateTime"
                v-model="form.scheduled_date"
                id="scheduled_date"
              />
              <div class="text-danger text-xs" v-if="errors.scheduled_date">
                <strong>{{errors.scheduled_date}}</strong>
              </div>
            </div>
            <div>
              <label for="responsible">Responsible</label>
              <input type="text" v-model="form.responsible" id="responsible" />
              <div class="text-danger text-xs" v-if="errors.responsible">
                <strong>{{errors.responsible}}</strong>
              </div>
            </div>
            <div>
              <label for="company">Company</label>
              <input type="text" v-model="form.company" id="company" />
              <div class="text-danger text-xs" v-if="errors.company">
                <strong>{{errors.company}}</strong>
              </div>
            </div>
            <div>
              <label for="tags">Tags</label>
              <input type="text" v-model="form.tags" id="tags" />
              <div class="text-danger text-xs" v-if="errors.tags">
                <strong>{{errors.tags}}</strong>
              </div>
            </div>
            <div>
              <label for="type">Type</label>
              <select id="type" v-model="form.type">
                <option>Add</option>
                <option>Remove</option>
                <option>Recycle</option>
              </select>
              <div class="text-danger text-xs" v-if="errors.type">
                <strong>{{errors.type}}</strong>
              </div>
            </div>
            <div>
              <label for="product_type">Product</label>
              <multiselect
                v-model="selectedProductType"
                :options="products"
                :multiple="true"
                :searchable="true"
                label="name"
                track-by="id"
                placeholder="Select products"
                id="product_type"
              ></multiselect>
              <div class="text-danger text-xs" v-if="errors.product_type">
                <strong>{{errors.product_type}}</strong>
              </div>
            </div>
            <div>
              <label for="demand">Demand</label>
              <input type="number" v-model="form.demand" id="demand" />
              <div class="text-danger text-xs" v-if="errors.demand">
                <strong>{{errors.demand}}</strong>
              </div>
            </div>
            <div>
              <label for="done">Done</label>
              <input type="number" v-model="form.done" id="done" />
              <div class="text-danger text-xs" v-if="errors.done">
                <strong>{{errors.done}}</strong>
              </div>
            </div>
            <div>
              <label for="unit_of_measure">Unit of Measure</label>
              <select id="unit_of_measure" v-model="form.unit_of_measure">
                <option>Units</option>
                <option>Gram</option>
              </select>
              <div class="text-danger text-xs" v-if="errors.unit_of_measure">
                <strong>{{errors.unit_of_measure}}</strong>
              </div>
            </div>
            <div>
              <label for="used">Used</label>
              <input type="checkbox" v-model="form.used" id="used" />
              <div class="text-danger text-xs" v-if="errors.used">
                <strong>{{errors.used}}</strong>
              </div>
            </div>
            <div>
              <label for="repair_notes">Repair Notes</label>
              <textarea id="repair_notes" v-model="form.repair_notes"></textarea>
              <div class="text-danger text-xs" v-if="errors.repair_notes">
                <strong>{{errors.repair_notes}}</strong>
              </div>
            </div>
            <div>
              <label for="operation_type">Operation Type</label>
              <select id="operation_type" v-model="form.operation_type">
                <option>Your Company: Repairs</option>
              </select>
              <div class="text-danger text-xs" v-if="errors.operation_type">
                <strong>{{errors.operation_type}}</strong>
              </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
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
import InputLabel from "@/Components/InputLabel.vue";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.css";

const props = defineProps({
  customers: {
    type: Array,
    required: true
  },
  products: {
    type: Array,
    required: true
  },
  errors: Object
});
// console.log("customers: ", props.customers);
const form = useForm({
  customer: "",
  product_to_repair: "",
  product_qty: 1,
  return: "",
  warranty: false,
  scheduled_date: "",
  responsible: "",
  company: "",
  tags: "",
  type: "",
  product_type: "",
  demand: "1",
  done: "",
  unit_of_measure: "Units",
  used: false,
  repair_notes: "",
  operation_type: "Company Repair"
});

const selectedProductType = ref([]);
const selectedProductRepair = ref([]);

// Set minimum date and time to current date and time
const now = new Date();
const minDateTime = ref(now.toISOString().slice(0, 16));

function submit() {
  const productRepairNames = selectedProductRepair.value
    .map(product => product.name)
    .join(", ");
  form.product_to_repair = productRepairNames;

  const productTypeNames = selectedProductType.value
    .map(product => product.name)
    .join(", ");
  form.product_type = productTypeNames;

  console.log(form.product_type);
  form.post("/services", {
    onSuccess: () => {
      console.log("Form submitted successfully");
      form.reset({
        customer: "",
        product_to_repair: "", // Reset this field
        product_qty: 1,
        return: "",
        warranty: false,
        scheduled_date: "",
        responsible: "",
        company: "",
        tags: "",
        type: "",
        product_type: "", // Reset this field
        demand: "1",
        done: "",
        unit_of_measure: "Units",
        used: false,
        repair_notes: "",
        operation_type: "Company Repair"
      });
      selectedProductType.value = [];
      selectedProductRepair.value = [];
    },
    onError: errors => {
      console.log("Form submission failed", errors);
    }
  });
}
</script>

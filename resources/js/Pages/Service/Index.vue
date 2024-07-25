<template>
  <Head title="Dashboards" />
  <NavLink>
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
          <div class="container mt-4">
            <div class="row">
              <div class="col-md-6">
                <button class="btn btn-dark mb-3" @click="createNewService">New</button>
                <button class="btn btn-red mb-3 ml-3" @click="deleteSelectedServices">Delete</button>
              </div>
              <div class="col-md-6">
                <div class="input-group mb-3">
                  <input
                    type="text"
                    v-model="searchQuery"
                    class="form-control"
                    placeholder="Search..."
                    aria-label="Search bar"
                    aria-describedby="searchButton"
                  />
                  <button class="btn btn-outline-secondary" type="button" id="searchButton">Search</button>
                </div>
              </div>
            </div>

            <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead class="thead-light">
                <tr>
                  <th>
                    <input type="checkbox" id="selectAll" @click="selectAll" />
                  </th>
                  <th>
                    Repair Reference
                    <button
                      @click="sortBy('repair_reference')"
                    >{{ filters.sort_field === 'repair_reference' ? (filters.sort_order === 'asc' ? '⬆️' : '⬇️') : '' }}</button>
                  </th>
                  <th>Scheduled Date</th>
                  <th>Product to Repair</th>
                  <th>Component Status</th>
                  <th>Customer</th>
                  <th>Sale Order</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="service in filteredServices"
                  :key="service.id"
                  @click="goToServiceInfo(service.id)"
                  style="cursor: pointer;"
                >
                  <td>
                    <input
                      type="checkbox"
                      v-model="selectedServices"
                      :value="service.id"
                      @click.stop
                    />
                  </td>
                  <td>{{ service.repair_reference }}</td>
                  <td>{{ formattedScheduledDate(service.scheduled_date) }}</td>
                  <td>{{ service.pro_id? getProductName(service.pro_id) : '' }}</td>
                  <td v-if="service.component_status==1" style="color: green;">Available</td>
                  <td v-else class="text-muted"></td>
                  <td>{{ getCustomerName(service.cus_id) }}</td>
                  <td>{{ getQuotationReference(service.id) }}</td>
                  <td>
                    <span
                      class="badge badge-success rounded-pill d-inline"
                      v-if="service.status==0"
                    >New</span>
                    <span
                      class="badge badge-info rounded-pill d-inline"
                      v-if="service.status==1"
                    >Confirmed</span>
                    <span
                      class="badge badge-warning rounded-pill d-inline"
                      v-if="service.status==2"
                    >Under Repair</span>
                    <span
                      class="badge badge-green rounded-pill d-inline"
                      v-if="service.status==3"
                    >Repaired</span>
                    <span
                      class="badge badge-secondary rounded-pill d-inline"
                      v-if="service.status==4"
                    >Cancelled</span>
                    <span
                      class="badge badge-secondary rounded-pill d-inline"
                      v-if="service.status==5"
                    >Cancelled</span>
                  </td>
                </tr>
              </tbody>
            </table>
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
import { ref, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";

const props = defineProps({
  services: {
    type: Array,
    required: true
  },
  customers: {
    type: Array,
    required: true
  },
  products: {
    type: Array,
    required: true
  },
  quotations: {
    type: Array,
    required: true
  }
});

const filters = ref({
  sort_field: "repair_reference",
  sort_order: "desc"
});

const sortBy = field => {
  if (filters.value.sort_field === field) {
    filters.value.sort_order =
      filters.value.sort_order === "asc" ? "desc" : "asc";
  } else {
    filters.value.sort_field = field;
    filters.value.sort_order = "desc";
  }
};

const searchQuery = ref("");

const filteredServices = computed(() => {
  let result = props.services;

  if (searchQuery.value) {
    result = result.filter(service => {
      return (
        service.repair_reference
          .toLowerCase()
          .includes(searchQuery.value.toLowerCase()) ||
        getProductName(service.pro_id)
          .toLowerCase()
          .includes(searchQuery.value.toLowerCase()) ||
        getCustomerName(service.cus_id)
          .toLowerCase()
          .includes(searchQuery.value.toLowerCase()) ||
        getQuotationReference(service.id)
          .toLowerCase()
          .includes(searchQuery.value.toLowerCase())
      );
    });
  }

  return result.sort((a, b) => {
    let modifier = filters.value.sort_order === "asc" ? 1 : -1;
    if (a[filters.value.sort_field] < b[filters.value.sort_field])
      return -1 * modifier;
    if (a[filters.value.sort_field] > b[filters.value.sort_field])
      return 1 * modifier;
    return 0;
  });
});

function getProductName(productId) {
  const product = props.products.find(product => product.id === productId);
  return product ? product.name : "Unknown Customer";
}

function getCustomerName(customerId) {
  const customer = props.customers.find(customer => customer.id === customerId);
  return customer ? customer.name : "Unknown Customer";
}

function getQuotationReference(serviceId) {
  const quotation = props.quotations.find(
    quotation => quotation.service_id === serviceId
  );
  return quotation ? quotation.quotation_reference : "";
}

dayjs.extend(relativeTime);

function formattedScheduledDate(date) {
  return dayjs(date).fromNow();
}

const selectedServices = ref([]);

function selectAll(event) {
  if (event.target.checked) {
    selectedServices.value = props.services.map(service => service.id);
  } else {
    selectedServices.value = [];
  }
}

function createNewService() {
  router.get("/services/create");
}

async function deleteSelectedServices() {
  if (selectedServices.value.length === 0) {
    alert("No services selected for deletion.");
    return;
  }

  if (
    confirm(
      "Are you sure you want to delete selected service(s)? All quotation and invoice under this service(s) also will be deleted!."
    )
  ) {
    try {
      const response = await router.delete(
        `/services/delete-selected/${selectedServices.value.join(",")}`,
        {}
      );
      console.log("Services deleted successfully:", response.data);
      // Optionally, refresh the list of services or provide feedback
    } catch (error) {
      console.error(
        "There was an error deleting the services:",
        error.response ? error.response.data : error.message
      );
    }
  }
}

function goToServiceInfo(id) {
  window.location.href = `/services/${id}/edit`;
}
</script>

<style scoped>
.table-hover tbody tr:hover {
  background-color: #f5f5f5;
}
.badge-success {
  background-color: #00bfa5;
}
.table th,
.table td {
  vertical-align: middle;
}
.table td input[type="checkbox"] {
  margin: 0;
}
.pl-5:hover .btn,
.pl-2:hover .btn {
  background-color: #f8f9fa;
}
.badge-green {
  background-color: green;
  color: white;
}
.btn-red {
  background-color: rgb(228, 7, 7);
  color: white;
}
</style>
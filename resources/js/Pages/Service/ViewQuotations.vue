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
                <button class="btn btn-dark mb-3" onclick="window.history.back();">Go Back</button>
                <button class="btn btn-dark mb-3 ml-3" @click="createQuotation">New</button>
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
                      Quotation Reference
                      <button
                        @click="sortBy('quotation_reference')"
                      >{{ filters.sort_field === 'quotation_reference' ? (filters.sort_order === 'asc' ? '⬆️' : '⬇️') : '' }}</button>
                    </th>
                    <th>Customer</th>
                    <th>Sales Person</th>
                    <th>Tax Excluded</th>
                    <th>Total</th>
                    <th>Invoice Order</th>
                    <th>Quotation Date</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="quotations in filteredServices"
                    :key="quotations.id"
                    @click="goToServiceInfo(quotations.id)"
                    style="cursor: pointer;"
                  >
                    <td>
                      <input
                        type="checkbox"
                        v-model="selectedServices"
                        :value="quotations.id"
                        @click.stop
                      />
                    </td>
                    <td>{{ quotations.quotation_reference }}</td>
                    <td>{{ getCustomerName(quotations.cus_id) }}</td>
                    <td>{{ quotations.sales_person? getProductName(quotations.sales_person) : '' }}</td>
                    <td>{{ quotations.untax_amt }}</td>
                    <td>{{ quotations.total }}</td>
                    <td>{{ getQuotationReference(quotations.id) }}</td>
                    <td>{{ formattedScheduledDate(quotations.updated_at) }}</td>
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
import { format } from "date-fns";
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
  },
  users: Array,
  invoices: Array
});

const filters = ref({
  sort_field: "quotation_reference",
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
  let result = props.quotations;

  if (searchQuery.value) {
    result = result.filter(quotations => {
      return (
        quotations.quotation_reference
          .toLowerCase()
          .includes(searchQuery.value.toLowerCase()) ||
        getProductName(quotations.sales_person)
          .toLowerCase()
          .includes(searchQuery.value.toLowerCase()) ||
        getCustomerName(quotations.cus_id)
          .toLowerCase()
          .includes(searchQuery.value.toLowerCase()) ||
        getQuotationReference(quotations.id)
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

function getProductName(userId) {
  const user = props.users.find(users => users.id === userId);
  return user ? user.name : "Unknown user";
}

function getCustomerName(customerId) {
  const customer = props.customers.find(customer => customer.id === customerId);
  return customer ? customer.name : "Unknown Customer";
}

function getQuotationReference(invoiceId) {
  const invoice = props.invoices.find(
    invoice => invoice.quotation_id === invoiceId
  );
  return invoice ? invoice.invoice_reference : "";
}

dayjs.extend(relativeTime);

function formattedScheduledDate(date) {
  return format(new Date(date), "dd/MM/yyyy");
}

const selectedServices = ref([]);

function selectAll(event) {
  if (event.target.checked) {
    selectedServices.value = props.quotations.map(quotations => quotations.id);
  } else {
    selectedServices.value = [];
  }
}

async function deleteSelectedServices() {
  if (selectedServices.value.length === 0) {
    alert("No quotation selected for deletion.");
    return;
  }

  if (
    confirm(
      "Are you sure you want to delete selected quotation(s)? The invoices under this also will be deleted!."
    )
  ) {
    try {
      const response = await router.delete(
        `/quotation/delete-selected/${selectedServices.value.join(",")}`,
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

const forms = useForm({});

function createQuotation() {
  const serviceId = props.quotations[0].service_id;
  forms.get(route("quotation.create", { id: serviceId }));
}

function goToServiceInfo(id) {
  forms.get(route("quotation.index", { id: id }));
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
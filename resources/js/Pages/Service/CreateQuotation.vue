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
            <!-- <div class="d-flex justify-content-end align-items-center mb-3">
              <ul class="status-tabs d-flex align-items-center mb-0 me-3">
                <li class="status-tab status-tab-selected bg-secondary">New</li>
                <li class="status-tab">Confirmed</li>
                <li class="status-tab">Under Repair</li>
                <li class="status-tab">Repaired</li>
              </ul>
            </div>-->
            <button type="submit" class="btn btn-dark btn-md mb-5">Confirm</button>
            <button type="button" class="btn btn-dark btn-md mb-5 ml-3" onclick="window.history.back();">
  Go Back
</button>
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
                          style="width: 100%;"
                          :options="customers"
                          label="name"
                          :reduce="(customers) => customers.id"
                        ></v-select>
                        <div class="ml-4" v-if="form.cus_id">
                          <p>{{ getCustomerAddressAndPhone(form.cus_id) }}</p>
                        </div>
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
                      <div class="form-group d-flex align-items-start">
                        <label for="payment_terms" class="mr-2" style="width: 150px;">Payment Terms</label>
                        <select
                          type="text"
                          v-model="form.payment_terms"
                          id="payment_terms"
                          class="form-control form-control-sm"
                        >
                          <option value>Immediate Payment</option>
                          <option value>15 Days</option>
                          <option value>21 Days</option>
                          <option value>30 Days</option>
                          <option value>45 Days</option>
                          <option value>End of Following Month</option>
                          <option value>10 Days after End of Next Month</option>
                          <option value>30% Now, Balance 60 Days</option>
                        </select>
                        <div class="text-danger text-xs" v-if="errors.payment_terms">
                          <span>{{ errors.payment_terms }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <span
                        class="text-danger text-xs"
                        style="margin-left:130px;"
                        v-if="errors.expiration"
                      >{{ errors.expiration }}</span>
                      <div class="form-group d-flex align-items-start">
                        <label for="expiration" class="mr-2" style="width: 120px;">Expiration</label>
                        <VueDatePicker
                          v-model="form.expiration"
                          id="expiration"
                          style="width: 80%; background-color:white;"
                          :enable-time-picker="false"
                          @input="formatDate"
                        />
                      </div>
                      <div class="form-group d-flex align-items-start">
                        <label for="pricelist" class="mr-2" style="width: 150px;">Pricelist</label>
                        <input
                          type="text"
                          v-model="form.pricelist"
                          id="pricelist"
                          class="form-control form-control-sm rounded"
                        />
                        <div class="text-danger text-xs" v-if="errors.pricelist">
                          <span>{{ errors.pricelist }}</span>
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
                        >Order Lines</button>
                        <button
                          type="button"
                          class="btn btn-sm"
                          :class="{ 'btn-dark': currentTab === 'repairNotes', 'btn-light': currentTab!== 'repairNotes' }"
                          @click="changeTab('repairNotes')"
                        >Optional Products</button>
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
                                  v-model="line.description"
                                  class="form-control form-control-sm rounded"
                                />
                                <div
                                  class="text-danger text-xs"
                                  v-if="errors && errors[`lines.${index}.description`]"
                                >
                                  <span>{{ errors[`lines.${index}.description`] }}</span>
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
                                <button @click="removeLine(index)" class="btn btn-danger btn-sm">
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
                                      @click="removeLine(index)"
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
                                      @click="removeLine(index)"
                                      class="btn btn-danger btn-sm  ml-4"
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
                  <div v-if="currentTab === 'repairNotes'" class="mt-3">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Product</th>
                          <th>Description</th>
                          <th>Quantity</th>
                          <th>Unit Price</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="(line, index) in form.lines"
                          :key="index"
                          v-show="line.type === 'optional'"
                        >
                          <td>
                            <div class="form-group">
                              <v-select
                                v-model="line.option_pro_id"
                                id="option_pro_id"
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
                                v-if="errors && errors[`lines.${index}.option_pro_id`]"
                              >
                                <span>{{ errors[`lines.${index}.option_pro_id`] }}</span>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input
                                type="text"
                                v-model="line.option_description"
                                class="form-control form-control-sm rounded"
                              />
                              <div
                                class="text-danger text-xs"
                                v-if="errors && errors[`lines.${index}.option_description`]"
                              >
                                <span>{{ errors[`lines.${index}.option_description`] }}</span>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input
                                type="number"
                                step="0.01"
                                v-model="line.option_quantity"
                                class="form-control form-control-sm rounded"
                              />
                              <div
                                class="text-danger text-xs"
                                v-if="errors && errors[`lines.${index}.option_quantity`]"
                              >
                                <span>{{ errors[`lines.${index}.option_quantity`] }}</span>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input
                                type="number"
                                step="0.01"
                                class="form-control form-control-sm rounded"
                                v-model="line.option_unit_price"
                              />
                              <div
                                class="text-danger text-xs"
                                v-if="errors && errors[`lines.${index}.option_unit_price`]"
                              >
                                <span>{{ errors[`lines.${index}.option_unit_price`] }}</span>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <button
                                type="button"
                                @click="removeLine(index)"
                                class="btn btn-danger btn-sm"
                              >
                                <i class="bi bi-trash"></i>
                              </button>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div v-if="currentTab === 'repairNotes'">
                    <div class="mt-3">
                      <button
                        type="button"
                        class="btn btn-outline-primary btn-sm"
                        @click="addOptionLine"
                      >Add a line</button>
                    </div>
                  </div>
                  <!-- Miscellaneous Tab -->
                  <div v-if="currentTab === 'miscellaneous'" class="mt-5">
                    <div class="row">
                      <div class="col-md-6">
                        <h4 class="mb-1" style="font-weight:bold;">SALES</h4>
                        <hr />
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
                            for="online_signature"
                            class="mr-2"
                            style="width: 150px;"
                          >Online signature</label>
                          <input
                            type="checkbox"
                            id="online_signature"
                            v-model="form.online_signature"
                          />
                        </div>
                        <div class="form-group d-flex align-items-center">
                          <label
                            for="online_payment"
                            class="mr-2"
                            style="width: 150px;"
                          >Online payment</label>
                          <input type="checkbox" id="online_payment" v-model="form.online_payment" />
                        </div>
                        <div class="form-group d-flex align-items-center">
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
                        <div class="form-group d-flex align-items-center">
                          <label for="tags" class="mr-2" style="width: 150px;">Tags</label>
                          <input
                            id="tags"
                            v-model="form.tags"
                            class="form-control form-control-sm"
                            style="width: 100%;"
                          />
                        </div>
                        <h4 class="mt-5 mb-1" style="font-weight:bold;">DELIVERY</h4>
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
                            for="shipping_policy"
                            class="mr-2"
                            style="width: 150px;"
                          >Shipping Policy</label>
                          <select
                            id="shipping_policy"
                            v-model="form.shipping_policy"
                            class="form-control form-control-sm"
                          >
                            <option value="As soon as Possible">As soon as Possible</option>
                            <option value="When all products are ready">When all products are ready</option>
                          </select>
                        </div>
                        <div class="form-group d-flex align-items-center">
                          <label
                            for="delivery_date"
                            class="mr-2"
                            style="width: 150px;"
                          >Delivery Date</label>
                          <VueDatePicker
                            v-model="form.delivery_date"
                            id="delivery_date"
                            :is-24="false"
                            style="width: 85%;"
                            @input="formatDate"
                          />
                          <P class="ml-2 text-muted">Expected</P>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <h4 class="mb-1" style=" font-weight:bold;">INVOICING</h4>
                        <hr />
                        <div class="form-group d-flex align-items-center mt-3">
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
                        <h4 class="mb-1" style="margin-top: 363px; font-weight:bold;">TRACKING</h4>
                        <hr />
                        <div class="form-group d-flex align-items-center mt-3">
                          <label
                            for="source_document"
                            class="mr-2"
                            style="width: 150px;"
                          >Source Document</label>
                          <input
                            id="source_document"
                            v-model="form.source_document"
                            class="form-control form-control-sm"
                            style="width: 100%;"
                          />
                        </div>
                        <div class="form-group d-flex align-items-center">
                          <label for="campaign" class="mr-2" style="width: 150px;">Campaign</label>
                          <input
                            id="campaign"
                            v-model="form.campaign"
                            class="form-control form-control-sm"
                            style="width: 100%;"
                          />
                        </div>
                        <div class="form-group d-flex align-items-center">
                          <label for="medium" class="mr-2" style="width: 150px;">Medium</label>
                          <select
                            v-model="form.medium"
                            id="medium"
                            class="form-control form-control-sm"
                          >
                            <option value>Select Medium</option>
                            <option value="Banner">Banner</option>
                            <option value="Direct">Direct</option>
                            <option value="Email">Email</option>
                            <option value="Facebook">Facebook</option>
                            <option value="Google Adwords">Google Adwords</option>
                            <option value="LinkedIn">LinkedIn</option>
                            <option value="Phone">Phone</option>
                            <option value="Television">Television</option>
                            <option value="Twitter">Twitter</option>
                            <option value="Website">Website</option>
                          </select>
                        </div>
                        <div class="form-group d-flex align-items-center">
                          <label for="source" class="mr-2" style="width: 150px;">Source</label>
                          <select
                            v-model="form.source"
                            id="source"
                            class="form-control form-control-sm"
                          >
                            <option value>Select Source</option>
                            <option value="Search engine">Search engine</option>
                            <option value="Lead Recall">Lead Recall</option>
                            <option value="Newsletter">Newsletter</option>
                            <option value="Facebook">Facebook</option>
                            <option value="Twitter">Twitter</option>
                            <option value="LinkedIn">LinkedIn</option>
                            <option value="Monster">Monster</option>
                            <option value="Glassdoor">Glassdoor</option>
                            <option value="Craigslist">Craigslist</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
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
import Datepicker from "vue3-datepicker";

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
  service_id: {
    type: Number,
    required: true
  },
  service: {
    type: Object,
    required: true
  },
  serviceLines: {
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
  // serviceStatus: Object,
  errors: Object,
});

// const service = props.serviceStatus.service;
const taxOptions = ref(props.taxes.map(tax => ({ name: tax.name })));
// const productIds = props.serviceLines.map(line => line.product.id);

const formatDecimal = value => {
  return parseFloat(value).toFixed(2);
};

const form = useForm({
  service_id: props.service_id,
  cus_id: props.service.cus_id,
  gst: "consumer" || null,
  expiration: new Date().toISOString().substr(0, 10) || null,
  pricelist: "Default INR pricelist (INR)",
  payment_terms: "",
  total: 0,
  tax_amt: 0,
  untax_amt: null,
  sales_person: props.currentUserId || null,
  sales_team: "Sales" || null,
  online_signature: false,
  online_payment: false,
  customer_reference: "",
  tags: "",
  fiscal_position: "",
  incoterm: "",
  incoterm_location: "",
  shipping_policy: "As soon as possible",
  delivery_date: null,
  source_document: "",
  campaign: "",
  medium: "",
  source: "",
  lines: props.serviceLines.map(line => ({
    type: line.product?.id ? "product" : "",
    pro_id: line.product?.id,
    description: "",
    quantity: line.product?.id ? formatDecimal(1) : "",
    unit_price: line.product?.id ? formatDecimal(1) : "",
    taxes: "", // assuming this is a JSON string
    section: "",
    note: "",
    option_pro_id: "",
    option_description: "",
    option_quantity: "",
    option_unit_price: ""
  }))
});

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
  if (form.expiration) {
    // Format the date to YYYY-MM-DD HH:MM:SS
    const dateObj = new Date(form.expiration);
    const formattedDate = `${dateObj.getFullYear()}-${padNumber(
      dateObj.getMonth() + 1
    )}-${padNumber(dateObj.getDate())} ${padNumber(
      dateObj.getHours()
    )}:${padNumber(dateObj.getMinutes())}:${padNumber(dateObj.getSeconds())}`;
    form.expiration = formattedDate;
  }
}

function padNumber(num) {
  return num.toString().padStart(2, "0");
}

function submit() {
  formatDate();
  form.lines.forEach(line => {
    if (Array.isArray(line.taxes)) {
      line.taxes = JSON.stringify(line.taxes.map(tax => tax.name));
    }
  });
  form.post("/quotation", {
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
      pro_id: "",
      description: "",
      quantity: formatDecimal(1),
      unit_price: formatDecimal(1),
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

// const showOptionLineForm = ref(false);
function addOptionLine() {
  // showOptionLineForm.value = true;
  if (
    !form.lines.some(line => line.type === "optional" && !line.option_pro_id)
  ) {
    form.lines.push({
      type: "optional",
      option_pro_id: "",
      option_description: "",
      option_quantity: formatDecimal(1),
      option_unit_price: formatDecimal(1)
    });
  } else {
    errors.value = {
      [`lines.${form.lines.length - 1}.option_pro_id`]: "Product is required."
    };
    setTimeout(() => {
      errors.value = "";
    }, 3000);
  }
}

function removeLine(index) {
  form.lines.splice(index, 1);
}


// Set minimum date and time to current date and time
const now = new Date();
const minDateTime = ref(now.toISOString().slice(0, 16));
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
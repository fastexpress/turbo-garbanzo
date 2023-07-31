<template>
  <div>
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-sm-12">
        <div class="panel br-6 p-0">
          <div class="custom-table">
            <div class="table-header">
              <h5>
                Total: {{ this.pagination.total }}
                {{ this.pagination.total === 1 ? "paquete" : "paquetes" }}
              </h5>
              <download
                :typeReport="{ name: 'Oficina', value: 'OFICINA' }"
                :customer="customer"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-sm-12">
        <div class="panel br-6 p-0">
          <div class="custom-table">
            <div class="table-header">
              <!-- SHOW -->
              <div class="d-flex align-items-center">
                <span>Mostrar :</span>
                <span class="ml-2">
                  <b-select v-model="show" size="sm" @change="$emit('call')">
                    <b-select-option value="5">5</b-select-option>
                    <b-select-option value="10">10</b-select-option>
                    <b-select-option value="20">20</b-select-option>
                    <b-select-option value="50">50</b-select-option>
                  </b-select>
                </span>
              </div>
              <!-- END SHOW -->
              <!-- SEARCH -->
              <div class="header-search">
                <b-input
                  v-model="search"
                  size="sm"
                  placeholder="Buscar..."
                  @input="$emit('call')"
                />
                <div class="search-image">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="feather feather-search"
                  >
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                  </svg>
                </div>
              </div>
              <!-- END SEARCH -->
            </div>

            <b-table
              ref="basic_table"
              responsive
              hover
              :items="items"
              :fields="columns"
              :busy="isBusy"
              :show-empty="true"
            >
              <template #cell(telephone)="row">
                <a
                  :href="
                    row.item.telephone.length === 9
                      ? `tel:+502-${row.item.telephone}`
                      : `tel:+1-${row.item.telephone}`
                  "
                  >{{ row.item.telephone }}</a
                >
              </template>
              <template #cell(weight)="row">
                <template v-if="row.item.typical === 0">
                  {{ myLbs(row.item.weight) }}
                </template>
                <template v-else>
                  {{ myLbs(totalLbs(row.item.customer)) }}
                </template>
              </template>
              <template #cell(totalPayment)="row">
                {{ myDollar(row.item.totalPayment) }}
              </template>
              <template #cell(balance)="row">
                <template v-if="row.item.typical === 0">
                  {{ myDollar(row.item.balance) }}
                </template>
                <template v-else>
                  {{ myDollar(balancePayment(row.item.customer)) }}
                </template>
              </template>
              <template #cell(created_at)="row">
                {{ dateGT(row.item.created_at) }}
              </template>
              <template #cell(boxes)="row">
                {{ row.item.boxes.length }} cajas
              </template>
              <template #cell(customer)="row">
                <b-badge variant="warning" v-if="row.item.customer.length < 1"
                  >Oficinas</b-badge
                >
                <template v-else>
                  <b-badge
                    variant="success"
                    v-for="c in row.item.customer"
                    :key="c.id"
                    >{{ c.name }}</b-badge
                  >
                </template>
              </template>
              <template #cell(status)="row">
                <template v-if="row.item.typical === 0">
                  <b-badge variant="warning" v-if="row.item.boxes.length < 1">
                    PENDIENTE DE CONTENIDO</b-badge
                  >
                  <b-badge
                    variant="warning"
                    v-if="typeof row.item.inAddress === 'object'"
                    >PENDIENTE DE DIRECCIÓN</b-badge
                  >
                </template>
                <template v-else>
                  <template>
                    <b-badge
                      variant="warning"
                      v-if="
                        row.item.boxes.length < 1 ||
                        row.item.contents.length < 1
                      "
                    >
                      PENDIENTE DE CONTENIDO</b-badge
                    >
                    <b-badge
                      variant="warning"
                      v-if="typeof row.item.inAddress === 'object'"
                      >PENDIENTE DE DIRECCIÓN</b-badge
                    >
                    <b-badge
                      variant="warning"
                      v-if="
                        validatePayment(row.item.customer) &&
                        balancePayment(row.item.customer) > 0
                      "
                      >PENDIENTE DE PAGO</b-badge
                    >
                  </template>
                </template>
              </template>
              <template #cell(action)="row">
                <div class="position-relative">
                  <b-dropdown
                    :right="true"
                    variant="icon-only"
                    toggle-tag="a"
                    class="custom-dropdown"
                  >
                    <template #button-content>
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="feather feather-more-horizontal"
                      >
                        <circle cx="12" cy="12" r="1"></circle>
                        <circle cx="19" cy="12" r="1"></circle>
                        <circle cx="5" cy="12" r="1"></circle>
                      </svg>
                    </template>
                    <b-dropdown-item
                      v-if="
                        row.item.boxes.length < 1 ||
                        row.item.contents.length < 1
                      "
                      @click="openModalBox(row.item)"
                      link-class="action-edit"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="feather feather-send"
                      >
                        <path
                          d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"
                        ></path>
                        <polyline
                          points="3.27 6.96 12 12.01 20.73 6.96"
                        ></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                      </svg>
                      Cajas y Contenido
                    </b-dropdown-item>
                    <b-dropdown-item
                      v-if="typeof row.item.inAddress === 'object'"
                      @click="
                        sendMessage(row.item.customer, row.item.telephone)
                      "
                      link-class="action-edit"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="feather feather-send"
                      >
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                      </svg>
                      WA Dirección
                    </b-dropdown-item>
                    <b-dropdown-item
                      link-class="action-delete"
                      @click="copyAddress(row.item.address)"
                      v-if="typeof row.item.inAddress === 'object'"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="feather feather-trash"
                      >
                        <path
                          d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"
                        ></path>
                        <circle cx="12" cy="10" r="3"></circle>
                      </svg>
                      Copiar dirección
                    </b-dropdown-item>
                    <b-dropdown-item
                      link-class="action-delete"
                      @click="openModalAddress(row.item)"
                      v-if="typeof row.item.inAddress === 'object'"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="feather feather-trash"
                      >
                        <polygon
                          points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"
                        ></polygon>
                        <line x1="8" y1="2" x2="8" y2="18"></line>
                        <line x1="16" y1="6" x2="16" y2="22"></line>
                      </svg>
                      Validar dirección
                    </b-dropdown-item>
                    <b-dropdown-item
                      v-if="
                        validatePayment(row.item.customer) &&
                        balancePayment(row.item.customer) > 0
                      "
                      @click="
                        sendMoney(
                          row.item.telephone,
                          row.item.account,
                          row.item.customer
                        )
                      "
                      link-class="action-edit"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="feather feather-send"
                      >
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path
                          d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"
                        ></path>
                      </svg>
                      WA Pago
                    </b-dropdown-item>
                    <b-dropdown-item
                      v-if="
                        validatePayment(row.item.customer) &&
                        balancePayment(row.item.customer) > 0
                      "
                      @click="openModalPayment(row.item)"
                      link-class="action-edit"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="feather feather-send"
                      >
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path
                          d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"
                        ></path>
                      </svg>
                      Registrar pago
                    </b-dropdown-item>
                    <b-dropdown-item
                      v-if="
                        typeof row.item.inAddress === 'string' &&
                        row.item.boxes.length > 0
                      "
                      @click="ifForcePayment(row.item.id)"
                      link-class="action-edit"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="feather feather-send"
                      >
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                      </svg>
                      Forzar pago
                    </b-dropdown-item>
                    <b-dropdown-item
                      @click="update(row.item.id)"
                      link-class="action-edit"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="feather feather-edit-3"
                      >
                        <path d="M12 20h9"></path>
                        <path
                          d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"
                        ></path>
                      </svg>
                      Editar
                    </b-dropdown-item>
                    <!-- <b-dropdown-item
                      v-if="userRole() === 1"
                      @click="updateControl(row.item.id)"
                      link-class="action-edit"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="feather feather-edit-3"
                      >
                        <path
                          d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                        ></path>
                        <path
                          d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                        ></path>
                      </svg>
                      Edición básica
                    </b-dropdown-item> -->
                    <b-dropdown-item
                      @click="ifDelete(row.item.id)"
                      link-class="action-edit"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="feather feather-edit-3"
                      >
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path
                          d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                        ></path>
                      </svg>
                      Eliminar
                    </b-dropdown-item>
                    <b-dropdown-item
                      @click="openChat(row.item.telephone)"
                      link-class="action-edit"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="feather feather-send"
                      >
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                      </svg>
                      WA Chat
                    </b-dropdown-item>
                  </b-dropdown>
                </div>
              </template>
              <template #empty> No hay registros </template>
            </b-table>

            <div class="table-footer">
              <div
                class="
                  paginating-container
                  pagination-solid
                  flex-column
                  align-items-right
                "
              >
                <!-- END SHO REGISTRES -->
                <b-pagination
                  @change="handlePage"
                  :total-rows="pagination.total"
                  :per-page="pagination.per_page"
                  prev-text="Prev"
                  next-text="Next"
                  first-text="First"
                  last-text="Last"
                  first-class="first"
                  prev-class="prev"
                  next-class="next"
                  last-class="last"
                  class="rounded"
                >
                  <template #first-text>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7"
                      />
                    </svg>
                  </template>
                  <template #prev-text>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                      />
                    </svg>
                  </template>
                  <template #next-text>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                      />
                    </svg>
                  </template>
                  <template #last-text>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 5l7 7-7 7M5 5l7 7-7 7"
                      />
                    </svg>
                  </template>
                </b-pagination>
              </div>
            </div>
          </div>
        </div>
      </div>
      <b-toast
        id="toast"
        header-class="d-none"
        body-class="toast-success d-flex justify-content-between"
        toaster="b-toaster-bottom-right"
      >
        {{ msgToast }}
        <a
          href="javascript:;"
          class="text-white ml-2"
          @click="$bvToast.hide('toast')"
          >OK</a
        >
      </b-toast>
      <b-toast
        id="toast-error"
        header-class="d-none"
        body-class="toast-danger d-flex justify-content-between"
        toaster="b-toaster-bottom-right"
      >
        {{ msgToast }}
        <a
          href="javascript:;"
          class="text-white ml-2"
          @click="$bvToast.hide('toast')"
          >Error</a
        >
      </b-toast>
      <!-- ADDRESS -->
      <validate-address ref="address" @save="$emit('call')" />
      <payment ref="payment" @save="$emit('call')" />
      <box ref="box" @save="$emit('call')" />
      <!-- END ADDRESS -->
    </div>
  </div>
</template>
<script>
import Vue from "vue";
import axios from "axios";
import { mapGetters } from "vuex";
import "@/assets/sass/apps/todolist.scss";
import { eight } from "../../../utils/eight";
import VueClipboard from "vue-clipboard2";
import validateAddress from "./modals/validate-address.vue";
import payment from "./modals/payment.vue";
import box from "./modals/boxes.vue";
import download from "../download.vue";
VueClipboard.config.autoSetContainer = true; // add this line
Vue.use(VueClipboard);

export default {
  components: {
    "validate-address": validateAddress,
    payment,
    box,
    download,
  },
  data() {
    return {
      items: [],
      columns: [
        { key: "id", label: "ID" },
        { key: "receive", label: "Destinatario" },
        { key: "telephone", label: "Teléfono" },
        { key: "weight", label: "Peso" },
        { key: "totalPayment", label: "Pago" },
        { key: "balance", label: "Pendiente" },
        { key: "address", label: "Dirección" },
        { key: "boxes", label: "Cajas" },
        { key: "customer", label: "Cliente" },
        { key: "status", label: "Estado" },
        { key: "created_at", label: "Fecha de creación" },
        { key: "action", label: "Acciones", class: "actions text-center" },
      ],
      show: 10,
      isBusy: false,
      pagination: {
        current_page: 1,
        total: 0,
        per_page: 1,
      },
      search: "",
      meta: {},
      customer: -1,
      msgToast: "",
    };
  },
  mounted() {},
  methods: {
    ...mapGetters(["api", "userRole", "userID"]),
    ifDelete(id) {
      this.$swal({
        title: "Quieres eliminar este registro?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          this.delete(id);
        }
      });
    },
    delete(id) {
      let loader = this.$loading.show();
      axios
        .delete(this.api() + `/package-ups/${id}`, {
          user: this.userID(),
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.$emit("call");
        })
        .catch((error) => {
          loader.hide();
          this.$swal({
            text: `${error.response.data.message}`,
            icon: "error",
          });
        });
    },
    ifForcePayment(id) {
      this.$swal({
        title: "Quieres pasar este registro a guía word?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí",
        cancelButtonText: "No",
      }).then((result) => {
        if (result.isConfirmed) {
          this.forcePayment(id);
        }
      });
    },
    forcePayment(id) {
      let loader = this.$loading.show();
      axios
        .post(this.api() + "/package-ups-force-payment", {
          id,
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.$emit("call");
        })
        .catch((error) => {
          loader.hide();
          this.$swal({
            text: `${error.response.data.message}`,
            icon: "error",
          });
        });
    },
    update(id) {
      this.$router.push({
        name: "UpdateUPS",
        params: {
          id,
        },
      });
    },
    updateControl(id) {
      this.$router.push({
        name: "UpdateControlUPS",
        params: {
          id,
        },
      });
    },
    openModalBox(item) {
      this.$refs["box"].openModal(item);
    },
    parseDecimalFixed(value) {
      return parseFloat(value).toFixed(2);
    },
    parseDecimal(value) {
      return parseFloat(this.parseDecimalFixed(value));
    },
    validatePayment(customers) {
      let payment = false;
      customers.forEach((c) => {
        if (c.typePaymentTypical === 0) payment = true;
      });
      return payment;
    },
    balancePayment(customers) {
      return customers.reduce((total, item) => {
        return (total =
          this.parseDecimal(total) + this.parseDecimal(item.balance));
      }, 0);
    },
    totalLbs(customers) {
      return customers.reduce((total, item) => {
        return (total =
          this.parseDecimal(total) + this.parseDecimal(item.weight));
      }, 0);
    },
    myLbs(value) {
      return eight.lbs(parseFloat(value));
    },
    myDollar(value) {
      return eight.dollar(parseFloat(value));
    },
    dateGT(date) {
      const newDate = this.$moment(date);
      return newDate.format("DD-MM-YYYY, h:mm:ss a");
    },
    handlePage(value) {
      this.pagination.current_page = value;
      this.get(this.customer);
    },
    get(customer) {
      this.isBusy = true;
      this.customer = customer;
      if (this.search != "" && this.pagination.current_page != 1)
        this.pagination.current_page = 1;
      axios
        .get(
          this.api() +
            `/search-package-ups-office?show=${this.show}&search=${this.search}&page=${this.pagination.current_page}&customer=${customer}`
        )
        .then((response) => {
          this.isBusy = false;
          this.items = response.data.data;
          this.pagination = {
            current_page: response.data.current_page,
            total: response.data.total,
            per_page: response.data.per_page,
          };
        })
        .catch((error) => {
          this.isBusy = false;
          console.log(error);
        });
    },
    sendMessage(customers, cel, typical) {
      let customer = "";
      let message = "";
      if (typical === 1) {
        customers.forEach((c) => {
          customer += c.name.toUpperCase() + ", ";
        });
        message = `LE SALUDAMOS DE LA PAQUETERIA FAST EXPRES GT. PARA REALIZARLE EL ENVIO DE SU PAQUETE ADQUIRIDO EN LA TIENDA DE ${customer}ANEXO A ESTO NECESITAMOS QUE NOS PUEDA CONFIRMAR SU DIRECCIÓN EXACTA ASI NO TENER NINGÚN INCONVENIENTE EN LA ENTREGA DEL MISMO`;
      } else {
        message = `LE SALUDAMOS DE LA PAQUETERIA FAST EXPRES GT. PARA REALIZARLE EL ENVIO DE SU PAQUETE PARA ESTO NECESITAMOS QUE NOS PUEDA CONFIRMAR SU DIRECCIÓN EXACTA ASI NO TENER NINGÚN INCONVENIENTE EN LA ENTREGA DEL MISMO`;
      }
      const telephone =
        cel.length > 9
          ? `+1${cel.replace("-", "")}`
          : `+502${cel.replace("-", "")}`;
      window.open(
        `https://api.whatsapp.com/send?phone=${telephone}&text=${message}`,
        "_blank"
      );
    },
    sendMoney(cel, account, customers) {
      let accounts = [];
      account.forEach((a) => {
        const found = accounts.findIndex((x) => x.id === a.id);
        if (found === -1) accounts.push(a);
      });

      let message = "";
      if (account.length === 1) {
        message = `LE HACEMOS SABER QUE EL ENVIO DE SU PAQUETE TIENE UN COSTO DE *${this.balancePayment(
          customers
        )} DÓLARES* PUEDE DEPOSITAR O HACER UNA REMESA A LA CUENTA *${
          account[0].typeAccount
        }* *NO.${
          account[0].numberAccount
        }* A NOMBRE DE *${account[0].name.toUpperCase()}* DEL BANCO *${account[0].bank.toUpperCase()}*, LE AGRADECEMOS PODER CUMPLIR CON EL TOTAL INDICADO ANTERIORMENTE, YA QUE ESTE NO INCLUYE EL COSTO DEL ENVIO DEL DEPOSITO Y/O REMESA.`;
      } else {
        accounts.forEach((a) => {
          message += `LE HACEMOS SABER QUE EL ENVIO DE SU PAQUETE TIENE UN COSTO DE *${customers
            .filter((b) => b.idAccountPersonal === a.id)
            .reduce((total, item) => {
              return (total =
                this.parseDecimal(total) + this.parseDecimal(item.subtotal));
            }, 0)} DÓLARES*  PUEDE DEPOSITAR O HACER UNA REMESA A LA CUENTA *${
            a.typeAccount
          }* *NO.${
            a.numberAccount
          }* A NOMBRE DE *${a.name.toUpperCase()}* DEL BANCO *${a.bank.toUpperCase()}*, LE AGRADECEMOS PODER CUMPLIR CON EL TOTAL INDICADO ANTERIORMENTE, YA QUE ESTE NO INCLUYE EL COSTO DEL ENVIO DEL DEPOSITO Y/O REMESA.%0a`;
        });
      }
      const telephone =
        cel.length > 9
          ? `+1${cel.replaceAll("-", "")}`
          : `+502${cel.replaceAll("-", "")}`;
      window.open(
        `https://api.whatsapp.com/send?phone=${telephone}&text=${message}`,
        "_blank"
      );
    },
    openChat(cel) {
      const telephone =
        cel.length > 9
          ? `1${cel.replaceAll("-", "")}`
          : `502${cel.replaceAll("-", "")}`;
      window.open(`https://wa.me/${telephone}`, "_blank");
    },
    copyAddress: function (address) {
      const mv = this;
      this.$copyText(address).then(
        function () {
          mv.msgToast = "Dirección copiada";
          mv.$bvToast.show("toast");
        },
        function (e) {
          mv.msgToast = e;
          mv.$bvToast.show("toast-error");
        }
      );
    },
    openModalAddress(item) {
      this.$refs["address"].openModal(item);
    },
    openModalPayment(item) {
      this.$refs["payment"].openModal(item);
    },
  },
};
</script>

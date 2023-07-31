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
                :typeReport="{ name: 'Guía Word', value: 'CODE' }"
                :customer="customer"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-sm-12">
        <div class="panel">
          <b-button block variant="secondary" @click="downloadListPackages"
            >Descargar lista de paquetes</b-button
          >
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
                {{ myDollar(row.item.balance) }}
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
                <b-badge variant="warning" v-if="row.item.status === 'CODE'"
                  >PENDIENTE DE ENVÍAR GUÍA WORD</b-badge
                >
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
                      @click="update(row.item.id)"
                      v-if="userRole() === 1"
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
                    <b-dropdown-item
                      v-if="userRole() === 1"
                      @click="ifReturnUPS(row.item.id)"
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
                        <polyline points="9 10 4 15 9 20"></polyline>
                        <path d="M20 4v7a4 4 0 0 1-4 4H4"></path>
                      </svg>
                      Regresar
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
import download from "../download.vue";

VueClipboard.config.autoSetContainer = true; // add this line
Vue.use(VueClipboard);

export default {
  components: {
    "validate-address": validateAddress,
    payment,
    download,
  },
  data() {
    return {
      items: [],
      columns: [
        { key: "code", label: "Código" },
        { key: "id", label: "Código de ratreo" },
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
    update(id) {
      this.$router.push({
        name: "UpdateUPS",
        params: {
          id,
        },
      });
    },
    ifReturnUPS(id) {
      this.$swal({
        title: "Quieres regresar este registro?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          this.returnUPS(id);
        }
      });
    },
    returnUPS(id) {
      let loader = this.$loading.show();
      axios
        .post(this.api() + `/package-ups-return`, {
          id,
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
    balancePayment(customers) {
      return customers.reduce((total, item) => {
        return (total =
          this.parseDecimal(total) + this.parseDecimal(item.balance));
      }, 0);
    },
    parseDecimalFixed(value) {
      return parseFloat(value).toFixed(2);
    },
    parseDecimal(value) {
      return parseFloat(this.parseDecimalFixed(value));
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
            `/search-package-ups-code?show=${this.show}&search=${this.search}&page=${this.pagination.current_page}&customer=${customer}`
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
    openChat(cel) {
      const telephone =
        cel.length > 9
          ? `1${cel.replaceAll("-", "")}`
          : `502${cel.replaceAll("-", "")}`;
      window.open(`https://wa.me/${telephone}`, "_blank");
    },
    downloadListPackages() {
      window.open(this.api() + `/package-ups-download`, "_blank");
      this.$router.go(this.$router.currentRoute);
    },
  },
};
</script>

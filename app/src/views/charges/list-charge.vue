<template>
  <div class="layout-px-spacing">
    <portal to="breadcrumb">
      <div class="page-header">
        <nav class="breadcrumb-one" aria-label="breadcrumb">
          <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
        </nav>
      </div>
    </portal>

    <div class="row layout-top-spacing">
      <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="panel br-6 p-0">
          <div class="custom-table">
            <div class="table-header">
              <!-- SHOW -->
              <div class="d-flex align-items-center">
                <span>Mostrar :</span>
                <span class="ml-2">
                  <b-select v-model="show" size="sm" @change="get">
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
                  @input="get"
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
              <template #cell(id)="row">
                {{ row.item.ups.code === "" ? "Pendiente" : row.item.ups.code }}
              </template>
              <template #cell(code)="row">
                {{ row.item.ups.id }}
              </template>
              <template #cell(receive)="row">
                {{ row.item.ups.receive }}
              </template>
              <template #cell(telephone)="row">
                <a
                  :href="
                    row.item.ups.telephone.length === 9
                      ? `tel:+502-${row.item.ups.telephone}`
                      : `tel:+1-${row.item.ups.telephone}`
                  "
                  >{{ row.item.ups.telephone }}</a
                >
              </template>
              <!-- <template #cell(balance)="row">
                {{ myDollar(row.item.ups.balance) }}
              </template> -->
              <template #cell(weight)="row">
                {{ myLbs(row.item.ups.weight) }}
              </template>
              <template #cell(exchangeRate)="row">
                {{ myMoney(row.item.exchangeRate) }}
              </template>
              <template #cell(amountDollar)="row">
                {{ myDollar(row.item.amountDollar) }}
              </template>
              <template #cell(amount)="row">
                {{ myMoney(row.item.amount) }}
              </template>
              <template #cell(account)="row">
                {{ row.item.account.name }}
              </template>
              <template #cell(collect)="row">
                <b-badge
                  variant="warning"
                  v-if="row.item.collect === 'PENDIENTE'"
                  >{{ row.item.collect }}</b-badge
                >
                <b-badge
                  variant="success"
                  v-if="row.item.collect === 'COBRADO'"
                  >{{ row.item.collect }}</b-badge
                >
              </template>
              <template #cell(type)="row">
                <b-badge
                  variant="primary"
                  v-if="row.item.type === 'DEPÓSITO'"
                  >{{ row.item.type }}</b-badge
                >
                <b-badge variant="success" v-if="row.item.type === 'REMESA'">{{
                  row.item.type
                }}</b-badge>
              </template>
              <template #cell(created_at)="row">
                {{ dateGT(row.item.created_at) }}
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
                      @click="
                        openModalConsignment(row.item) &&
                          row.item.collect === 'PENDIENTE'
                      "
                      link-class="action-edit"
                      v-if="row.item.type === 'DEPÓSITO'"
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
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <polyline points="1 20 1 14 7 14"></polyline>
                        <path
                          d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"
                        ></path>
                      </svg>
                      Cambiar a remesa
                    </b-dropdown-item>
                    <b-dropdown-item
                      @click="ifChange(row.item.id)"
                      link-class="action-edit"
                      v-if="
                        row.item.type === 'REMESA' &&
                        row.item.collect === 'PENDIENTE'
                      "
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
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <polyline points="1 20 1 14 7 14"></polyline>
                        <path
                          d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"
                        ></path>
                      </svg>
                      Cambiar a déposito
                    </b-dropdown-item>
                    <b-dropdown-item
                      @click="ifCharged(row.item.id)"
                      link-class="action-edit"
                      v-if="row.item.collect === 'PENDIENTE'"
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
                      Cobrar
                    </b-dropdown-item>
                    <b-dropdown-item
                      @click="openChat(row.item.ups.telephone)"
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
    </div>
    <consignment ref="consignment" @save="get" />
  </div>
</template>
<script>
import axios from "axios";
import { mapGetters } from "vuex";
import { eight } from "../../utils/eight";
import consignment from "./modals/consignment.vue";
export default {
  metaInfo: { title: "Lista de categorías" },
  components: {
    consignment,
  },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Lista de cobros", active: true },
      ],
      items: [],
      columns: [
        { key: "id", label: "ID" },
        { key: "code", label: "Código de rastreo" },
        { key: "receive", label: "Destinatario" },
        { key: "telephone", label: "Teléfono" },
        { key: "weight", label: "Peso" },
        { key: "amountDollar", label: "Pago en dólares" },
        { key: "exchangeRate", label: "Tipo de cambio" },
        { key: "amount", label: "Pago en quetzales" },
        { key: "account", label: "Cuenta" },
        { key: "collect", label: "Cobrado" },
        { key: "type", label: "Tipo" },
        { key: "created_at", label: "Fecha de creación" },
        { key: "action", label: "Acciones", class: "actions text-center" },
      ],
      search: "",
      show: 10,
      isBusy: false,
      pagination: {
        current_page: 1,
        total: 1,
        per_page: 1,
      },
      meta: {},
    };
  },
  mounted() {
    this.get();
  },
  methods: {
    ...mapGetters(["api", "userID"]),
    ifChange(id) {
      this.$swal({
        title: "Quieres cambiar a déposito?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Cambiar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          this.change(id);
        }
      });
    },
    change(id) {
      let loader = this.$loading.show();
      axios
        .post(this.api() + `/charge/changeToDeposit`, {
          id,
          user: this.userID(),
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.get();
        })
        .catch((error) => {
          loader.hide();
          this.$swal({
            text: `${error.response.data.message}`,
            icon: "error",
          });
        });
    },
    ifCharged(id) {
      this.$swal({
        title: "Quiere cobrar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Cobrar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          this.charged(id);
        }
      });
    },
    charged(id) {
      let loader = this.$loading.show();
      axios
        .post(this.api() + `/charge/charged`, {
          id,
          user: this.userID(),
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.get();
        })
        .catch((error) => {
          loader.hide();
          this.$swal({
            text: `${error.response.data.message}`,
            icon: "error",
          });
        });
    },
    openModalConsignment(item) {
      this.$refs["consignment"].openModal(item);
    },
    openChat(cel) {
      const telephone =
        cel.length > 9
          ? `1${cel.replaceAll("-", "")}`
          : `502${cel.replaceAll("-", "")}`;
      window.open(`https://wa.me/${telephone}`, "_blank");
    },
    handlePage(value) {
      this.pagination.current_page = value;
      this.get();
    },
    dateGT(date) {
      const newDate = this.$moment(date);
      return newDate.format("DD-MM-YYYY, h:mm:ss a");
    },
    myLbs(value) {
      return eight.lbs(parseFloat(value));
    },
    myDollar(value) {
      return eight.dollar(parseFloat(value));
    },
    myMoney(value) {
      return eight.money(parseFloat(value));
    },
    get() {
      this.isBusy = true;
      if (this.search != "" && this.pagination.current_page != 1)
        this.pagination.current_page = 1;
      axios
        .get(
          this.api() +
            `/charge/get?show=${this.show}&search=${this.search}&page=${this.pagination.current_page}`
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
  },
};
</script>

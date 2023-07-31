<template>
  <div class="layout-px-spacing">
    <portal to="breadcrumb">
      <div class="page-header">
        <nav class="breadcrumb-one" aria-label="breadcrumb">
          <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
        </nav>
      </div>
    </portal>
    <div class="row">
      <div class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="statbox panel box box-shadow">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                <h4>Filtros</h4>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <b-row>
              <b-col cols="12" xl="6" lg="6" md="12" sm="12">
                <flatpickr
                  v-model="date"
                  :config="config"
                  class="form-control flatpickr active"
                  @on-change="get"
                ></flatpickr>
              </b-col>
              <b-col cols="12" xl="6" lg="6" md="12" sm="12">
                <multiselect
                  v-model="city"
                  :options="cities"
                  :allow-empty="false"
                  :show-labels="false"
                  :searchable="true"
                  placeholder="Seleccione una ciudad"
                  @close="get"
                >
                </multiselect>
              </b-col>
            </b-row>
            <hr />
            <b-row>
              <b-table
                ref="table"
                responsive
                hover
                :items="bags"
                :fields="columns"
                :show-empty="true"
              >
                <template #cell(id)="row">
                  {{ row.index + 1 }}
                </template>
                <template #cell(traveler)="row">
                  {{ row.item.user_traveler.name }}
                </template>
                <template #cell(capacity)="row">
                  {{ myLbs(row.item.capacity) }}
                </template>
                <template #cell(status)="row">
                  <b-badge variant="primary" v-if="row.item.status === 2"
                    >GUATEMALA</b-badge
                  >
                  <b-badge variant="success" v-if="row.item.status === 1"
                    >USA</b-badge
                  >
                  <b-badge variant="danger" v-if="row.item.status === 0"
                    >ANULADO</b-badge
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
                        @click="update(row.item.id, 2)"
                        link-class="action-edit"
                        v-if="row.item.status === 1 || row.item.status === 0"
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
                          <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        GUATEMALA
                      </b-dropdown-item>
                      <b-dropdown-item
                        @click="update(row.item.id, 1)"
                        link-class="action-edit"
                        v-if="row.item.status === 2 || row.item.status === 0"
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
                          <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        USA
                      </b-dropdown-item>
                      <b-dropdown-item
                        link-class="action-delete"
                        @click="ifCancel(row.item.id)"
                        v-if="row.item.status === 1 || row.item.status === 2"
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
                          <line x1="18" y1="6" x2="6" y2="18"></line>
                          <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        ANULAR
                      </b-dropdown-item>
                    </b-dropdown>
                  </div>
                </template>
                <template #empty> No hay registros </template>
              </b-table>
            </b-row>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import axios from "axios";
import { mapGetters } from "vuex";

import "flatpickr/dist/flatpickr.min.css";
import "@/assets/sass/forms/custom-flatpickr.css";
import flatpickr from "flatpickr";
import { Spanish } from "flatpickr/dist/l10n/es";
import Vueflatpickr from "vue-flatpickr-component";
flatpickr.localize(Spanish);

import { eight } from "../../utils/eight";

export default {
  metaInfo: { title: "Descargar manifiesto" },
  components: {
    flatpickr: Vueflatpickr,
    Multiselect,
  },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Generar manifiesto", active: true },
      ],
      date: new Date(),
      config: {
        wrap: true, // set wrap to true only when using 'input-group'
        altFormat: "d-m-Y",
        altInput: true,
        dateFormat: "Y-m-d",
        locale: Spanish, // locale for this instance only
      },

      columns: [
        { key: "id", label: "ID" },
        { key: "bag", label: "Tipo de maleta" },
        { key: "capacity", label: "Capacidad" },
        { key: "traveler", label: "Viajero" },
        { key: "status", label: "Estado" },
        { key: "action", label: "Acciones", class: "actions text-center" },
      ],

      travelers: [],
      traveler: "",
      loaderTraveler: false,

      cities: [],
      city: "",

      bags: [],
    };
  },
  mounted() {
    this.getCities();
  },
  methods: {
    ...mapGetters(["api"]),
    getTravelers(search) {
      this.loaderTraveler = true;
      axios
        .get(this.api() + `/getTravelers?filter=${search}`)
        .then((response) => {
          this.loaderTraveler = false;
          this.travelers = response.data.data;
        })
        .catch((error) => {
          console.log(error);
          this.loaderTraveler = false;
        });
    },
    getCities() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + "/setting/3")
        .then((response) => {
          loader.hide();
          this.cities = JSON.parse(response.data.value);
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    myLbs(value) {
      return eight.lbs(parseFloat(value));
    },
    get() {
      if (this.city === null || this.city === "") {
        this.$swal({
          text: "Seleccione todos los filtros",
          icon: "error",
        });
        return;
      }
      let loader = this.$loading.show();
      axios
        .get(
          this.api() +
            `/getBags?city=${this.city}&date=${this.$moment(this.date).format(
              "YYYY-MM-DD"
            )}`
        )
        .then((response) => {
          loader.hide();
          this.bags = response.data;
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    ifCancel(id) {
      this.$swal({
        title: "¿Quieres marcar esta maleta como anulada?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          this.update(id, 0);
        }
      });
    },
    update(id, status) {
      let loader = this.$loading.show();
      axios
        .post(this.api() + "/bags/changeStatus", {
          id,
          status,
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
  },
};
</script>
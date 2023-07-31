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
                >
                </multiselect>
              </b-col>
            </b-row>
            <hr />
            <b-row>
              <b-button block @click="download" variant="primary"
                >Descargar</b-button
              >
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
    download() {
      if (this.city === null || this.city === "") {
        this.$swal({
          text: "Seleccione todos los filtros",
          icon: "error",
        });
        return;
      }
      window.open(
        `${this.api()}/getNumberBag?city=${this.city}&date=${this.$moment(
          this.date
        ).format("YYYY-MM-DD")}`,
        "_blank"
      );
    },
  },
};
</script>
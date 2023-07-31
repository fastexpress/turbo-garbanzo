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
              <b-col cols="12" xl="4" lg="4" md="12" sm="12">
                <flatpickr
                  v-model="dateInit"
                  :config="config"
                  class="form-control flatpickr active"
                ></flatpickr>
              </b-col>
              <b-col cols="12" xl="4" lg="4" md="12" sm="12">
                <flatpickr
                  v-model="dateEnd"
                  :config="config"
                  class="form-control flatpickr active"
                ></flatpickr>
              </b-col>
              <b-col cols="12" xl="4" lg="4" md="12" sm="12">
                <multiselect
                  v-model="customer"
                  :options="customers"
                  :show-labels="false"
                  :searchable="true"
                  label="name"
                  track-by="name"
                  placeholder="Seleccione un cliente"
                  @search-change="getCustomers"
                  :internal-search="false"
                  :class="errors.length > 0 ? 'is-danger' : ''"
                  :loading="loaderCustomer"
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
  metaInfo: { title: "Descargar reporte completo" },
  components: {
    flatpickr: Vueflatpickr,
    Multiselect,
  },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Generar reporte completo", active: true },
      ],
      dateInit: new Date(),
      dateEnd: new Date(),
      config: {
        wrap: true, // set wrap to true only when using 'input-group'
        altFormat: "d-m-Y",
        altInput: true,
        dateFormat: "Y-m-d",
        locale: Spanish, // locale for this instance only
      },

      customers: [],
      customer: "",
      loaderCustomer: false,
    };
  },
  mounted() {},
  methods: {
    ...mapGetters(["api"]),
    getCustomers(search) {
      this.loaderCustomer = true;
      axios
        .get(this.api() + `/getCustomer?filter=${search}`)
        .then((response) => {
          this.loaderCustomer = false;
          this.customers = response.data.data;
        })
        .catch((error) => {
          console.log(error);
          this.loaderCustomer = false;
        });
    },

    download() {
      if (this.customer === "" || this.customer === null) {
        this.$swal({
          text: "Seleccione todos los filtros",
          icon: "error",
        });
        return;
      }
      window.open(
        `${this.api()}/report/not-typical?customer=${
          this.customer.id
        }&dateInit=${this.$moment(this.dateInit).format(
          "YYYY-MM-DD"
        )}&dateEnd=${this.$moment(this.dateEnd).format("YYYY-MM-DD")}`,
        "_blank"
      );
    },
  },
};
</script>
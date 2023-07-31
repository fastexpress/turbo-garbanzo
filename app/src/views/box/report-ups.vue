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
                  v-model="dateInit"
                  :config="config"
                  class="form-control flatpickr active"
                ></flatpickr>
              </b-col>
              <b-col cols="12" xl="6" lg="6" md="12" sm="12">
                <flatpickr
                  v-model="dateEnd"
                  :config="config"
                  class="form-control flatpickr active"
                ></flatpickr>
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
import { mapGetters } from "vuex";

import "flatpickr/dist/flatpickr.min.css";
import "@/assets/sass/forms/custom-flatpickr.css";
import flatpickr from "flatpickr";
import { Spanish } from "flatpickr/dist/l10n/es";
import Vueflatpickr from "vue-flatpickr-component";
flatpickr.localize(Spanish);

export default {
  metaInfo: { title: "Descargar reporte UPS" },
  components: {
    flatpickr: Vueflatpickr,
  },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Generar reporte UPS", active: true },
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
    };
  },
  mounted() {},
  methods: {
    ...mapGetters(["api"]),
    download() {
      window.open(
        `${this.api()}/report/ups?dateInit=${this.$moment(this.dateInit).format(
          "YYYY-MM-DD"
        )}&dateEnd=${this.$moment(this.dateEnd).format("YYYY-MM-DD")}`,
        "_blank"
      );
    },
  },
};
</script>
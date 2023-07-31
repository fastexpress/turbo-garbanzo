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
                  v-model="account"
                  :options="accounts"
                  :show-labels="false"
                  :searchable="true"
                  label="name"
                  track-by="name"
                  placeholder="Seleccione una cuenta"
                  @search-change="getAccounts"
                  :internal-search="false"
                  :class="errors.length > 0 ? 'is-danger' : ''"
                  :loading="loaderAccount"
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
        { text: "Reporte de cuentas", active: true },
      ],
      config: {
        wrap: true, // set wrap to true only when using 'input-group'
        altFormat: "d-m-Y",
        altInput: true,
        dateFormat: "Y-m-d",
        locale: Spanish, // locale for this instance only
      },
      dateInit: new Date(),
      dateEnd: new Date(),
      account: null,
      accounts: [],
      loaderAccount: false,
    };
  },
  mounted() {},
  methods: {
    ...mapGetters(["api"]),
    getAccounts(search) {
      this.loaderAccount = true;
      axios
        .get(this.api() + `/getAllAccount?filter=${search}`)
        .then((response) => {
          this.loaderAccount = false;
          this.accounts = response.data.data;
        })
        .catch((error) => {
          console.log(error);
          this.loaderAccount = false;
        });
    },
    download() {
      if (this.account === null || this.account === "") {
        this.$swal({
          text: "Seleccione una cuenta",
          icon: "error",
        });
        return;
      }
      window.open(
        `${this.api()}/report/account?account=${
          this.account.id
        }&beginDate=${this.$moment(this.dateInit).format(
          "YYYY-MM-DD"
        )}&endDate=${this.$moment(this.dateEnd).format("YYYY-MM-DD")}`,
        "_blank"
      );
    },
  },
};
</script>
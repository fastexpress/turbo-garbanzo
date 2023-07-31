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
      <div class="col-lg-12 layout-top-spacing">
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
                  @on-change="callTab"
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
                  @close="callTab"
                >
                </multiselect>
              </b-col>
            </b-row>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 layout-top-spacing">
        <div class="statbox panel box box-shadow">
          <div class="panel-body">
            <b-tabs v-model="tabIndex" @input="callTab">
              <b-tab title="Paquetes sin maleta">
                <withoutbag ref="withoutbag" @call="callTab" />
              </b-tab>
              <b-tab title="Llamadas">
                <call ref="call" @call="callTab" />
              </b-tab>
              <b-tab title="WhatsApp">
                <whatsapp ref="whatsapp" @call="callTab" />
              </b-tab>
              <b-tab title="Paquete pendientes">
                <slope ref="slope" @call="callTab" />
              </b-tab>
              <b-tab title="Paquetes entregados">
                <delivered ref="delivered" @call="callTab" />
              </b-tab>
              <b-tab title="Todos">
                <all ref="all" @call="callTab" />
              </b-tab>
            </b-tabs>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import { mapGetters } from "vuex";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import call from "./list/call.vue";
import whatsapp from "./list/whatspp.vue";
import withoutbag from "./list/withoutbag.vue";
import slope from "./list/slope.vue";
import delivered from "./list/delivered.vue";
import all from "./list/all.vue";

import "flatpickr/dist/flatpickr.min.css";
import "@/assets/sass/forms/custom-flatpickr.css";
import flatpickr from "flatpickr";
import { Spanish } from "flatpickr/dist/l10n/es";
import Vueflatpickr from "vue-flatpickr-component";
flatpickr.localize(Spanish);

export default {
  metaInfo: { title: "Lista de paquetes" },
  components: {
    Multiselect,
    call,
    withoutbag,
    slope,
    delivered,
    whatsapp,
    all,
    flatpickr: Vueflatpickr,
  },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Lista de paquetes", active: true },
      ],
      date: new Date(),
      config: {
        wrap: true, // set wrap to true only when using 'input-group'
        altFormat: "d-m-Y",
        altInput: true,
        dateFormat: "Y-m-d",
        locale: Spanish, // locale for this instance only
      },
      tabIndex: 0,
      cities: [],
      city: "",
    };
  },
  mounted() {
    this.getCities();
  },
  methods: {
    ...mapGetters(["api"]),
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
    callTab() {
      if (this.tabIndex === 0)
        this.$refs["withoutbag"].get(this.date, this.city);
      if (this.tabIndex === 1) this.$refs["call"].get(this.date, this.city);
      if (this.tabIndex === 2) this.$refs["whatsapp"].get(this.date, this.city);
      if (this.tabIndex === 3) this.$refs["slope"].get(this.date, this.city);
      if (this.tabIndex === 4)
        this.$refs["delivered"].get(this.date, this.city);
      if (this.tabIndex === 5) this.$refs["all"].get(this.date, this.city);
    },
  },
};
</script>

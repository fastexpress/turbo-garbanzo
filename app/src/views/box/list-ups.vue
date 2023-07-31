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
              <b-col cols="12" xl="12" lg="12" md="12" sm="12">
                <multiselect
                  id="típico"
                  name="típico"
                  ref="típico"
                  v-model="customer"
                  :options="customers"
                  :show-labels="false"
                  :searchable="true"
                  label="name"
                  track-by="name"
                  placeholder="Seleccione un típico"
                  @search-change="getCustomer"
                  :internal-search="false"
                  :class="errors.length > 0 ? 'is-danger' : ''"
                  :loading="loaderCustomer"
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
              <b-tab title="Oficina">
                <office ref="office" @call="callTab" />
              </b-tab>
              <b-tab title="Guía word">
                <request-code ref="code" @call="callTab" />
              </b-tab>
              <b-tab title="Pendiente código UPS">
                <ups-code ref="ups" @call="callTab" />
              </b-tab>
              <b-tab title="Listo para envío">
                <ready ref="ready" @call="callTab" />
              </b-tab>
              <b-tab title="En camino">
                <inroute ref="inroute" @call="callTab" />
              </b-tab>
              <b-tab title="Entregado">
                <delivered ref="delivered" @call="callTab" />
              </b-tab>
              <b-tab title="Reclamo">
                <stopped ref="stopped" @call="callTab" />
              </b-tab>
              <b-tab title="Pendiente de pago">
                <balance ref="balance" @call="callTab" />
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
import office from "./list/office.vue";
import requestCode from "./list/code.vue";
import upsCode from "./list/generated.vue";
import ready from "./list/ready.vue";
import inroute from "./list/inroute.vue";
import delivered from "./list/delivered.vue";
import stopped from "./list/stopped.vue";
import balance from "./list/balance.vue";

export default {
  metaInfo: { title: "Lista de paquetes UPS" },
  components: {
    Multiselect,
    office,
    "request-code": requestCode,
    "ups-code": upsCode,
    ready,
    inroute,
    delivered,
    stopped,
    balance,
  },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Lista de paquetes UPS", active: true },
      ],
      tabIndex: 0,
      customer: {
        id: -1,
        name: "Todos",
      },
      customers: [
        {
          id: -1,
          name: "Todos",
        },
        {
          id: 0,
          name: "Oficinas",
        },
      ],
      loaderCustomer: false,
    };
  },
  mounted() {
    this.callTab();
  },
  methods: {
    ...mapGetters(["api"]),
    getCustomer(search) {
      this.loaderCustomer = true;
      axios
        .get(this.api() + `/getCustomer?filter=${search}`)
        .then((response) => {
          this.loaderCustomer = false;
          this.customers = [];
          response.data.data.forEach((item) => {
            this.customers.push(item);
          });
          this.customers.push({
            id: -1,
            name: "Todos",
          });
          this.customers.push({
            id: 0,
            name: "Oficinas",
          });
        })
        .catch((error) => {
          console.log(error);
          this.loaderCustomer = false;
        });
    },
    callTab() {
      if (this.tabIndex === 0) this.$refs["office"].get(this.customer.id);
      else if (this.tabIndex === 1) this.$refs["code"].get(this.customer.id);
      else if (this.tabIndex === 2) this.$refs["ups"].get(this.customer.id);
      else if (this.tabIndex === 3) this.$refs["ready"].get(this.customer.id);
      else if (this.tabIndex === 4) this.$refs["inroute"].get(this.customer.id);
      else if (this.tabIndex === 5)
        this.$refs["delivered"].get(this.customer.id);
      else if (this.tabIndex === 6) this.$refs["stopped"].get(this.customer.id);
      else if (this.tabIndex === 7) this.$refs["balance"].get(this.customer.id);
    },
  },
};
</script>

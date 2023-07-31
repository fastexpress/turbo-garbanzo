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
              <b-col cols="12" xl="6" lg="6" md="12" sm="12">
                <multiselect
                  v-model="type"
                  :options="types"
                  :allow-empty="false"
                  :show-labels="false"
                  :searchable="true"
                  label="name"
                  track-by="name"
                  placeholder="Seleccione una tipo"
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

export default {
  metaInfo: { title: "Descargar reporte de cobros" },
  components: {
    Multiselect,
  },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Generar reporte cobros", active: true },
      ],

      accounts: [],
      account: "",
      loaderAccount: false,

      type: {
        id: 1,
        name: "PENDIENTE",
      },

      types: [
        {
          id: 1,
          name: "PENDIENTE",
        },
        {
          id: 2,
          name: "COBRADO",
        },
        {
          id: 3,
          name: "TODOS",
        },
      ],
    };
  },
  mounted() {
    this.getCities();
  },
  methods: {
    ...mapGetters(["api"]),
    getAccounts(search) {
      this.loaderAccount = true;
      axios
        .get(this.api() + `/getAccountPersonal?filter=${search}`)
        .then((response) => {
          this.loaderAccount = false;
          this.accounts = response.data.data;
        })
        .catch((error) => {
          console.log(error);
          this.loaderAccount = false;
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
      if (
        this.type === null ||
        this.type === "" ||
        this.account === "" ||
        this.account === null
      ) {
        this.$swal({
          text: "Seleccione todos los filtros",
          icon: "error",
        });
        return;
      }
      window.open(
        `${this.api()}/charge/report?type=${this.type.name}&account=${
          this.account.id
        }`,
        "_blank"
      );
    },
  },
};
</script>
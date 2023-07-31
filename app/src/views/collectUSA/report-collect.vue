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
            <!-- SHOW -->
            <hr />
            <div class="container">
              <b-row>
                <b-col cols="12" xl="4" lg="4" md="12" sm="12">
                  <multiselect
                    v-model="departament"
                    :options="departaments"
                    :allow-empty="false"
                    :show-labels="false"
                    :searchable="true"
                    label="name"
                    track-by="name"
                    placeholder="Seleccione departamento"
                    @close="getTowns"
                  >
                  </multiselect>
                </b-col>
                <b-col cols="12" xl="4" lg="4" md="12" sm="12">
                  <multiselect
                    v-model="town"
                    :options="towns"
                    :allow-empty="false"
                    :show-labels="false"
                    :searchable="true"
                    label="name"
                    track-by="name"
                    placeholder="Seleccione un municipio"
                  >
                  </multiselect>
                </b-col>
                <b-col cols="12" xl="4" lg="4" md="12" sm="12">
                  <multiselect
                    v-model="type"
                    :options="types"
                    :allow-empty="false"
                    :show-labels="false"
                    :searchable="true"
                    label="name"
                    track-by="name"
                    placeholder="Seleccione una opción"
                  >
                  </multiselect>
                </b-col>
              </b-row>
              <hr />

              <b-row>
                <b-col cols="12" xl="6" lg="6" md="12" sm="12">
                  <flatpickr
                    v-model="from"
                    :config="config"
                    class="form-control flatpickr active"
                  ></flatpickr>
                </b-col>
                <b-col cols="12" xl="6" lg="6" md="12" sm="12">
                  <flatpickr
                    v-model="to"
                    :config="config"
                    class="form-control flatpickr active"
                  ></flatpickr>
                </b-col>
              </b-row>
            </div>
            <hr />
            <!-- END SHOW -->
            <div class="table-header">
              <b-button block @click="getReport" variant="primary"
                >Generar reporte</b-button
              >
            </div>
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
// DATE
import "flatpickr/dist/flatpickr.min.css";
import "@/assets/sass/forms/custom-flatpickr.css";
import flatpickr from "flatpickr";
import { Spanish } from "flatpickr/dist/l10n/es";
import Vueflatpickr from "vue-flatpickr-component";
flatpickr.localize(Spanish);
// END DATE
export default {
  metaInfo: { title: "Lista de recolección" },
  components: {
    Multiselect,
    flatpickr: Vueflatpickr,
  },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Lista de recolección", active: true },
      ],
      config: {
        wrap: true, // set wrap to true only when using 'input-group'
        altFormat: "d-m-Y",
        altInput: true,
        dateFormat: "Y-m-d",
        locale: Spanish, // locale for this instance only
      },
      departament: {
        id: 0,
        name: "Todos",
      },
      departaments: [],
      town: {
        id: 0,
        name: "Todos",
      },
      towns: [],
      type: {
        value: 2,
        name: "Pendientes",
      },
      types: [
        {
          value: 2,
          name: "Pendientes",
        },
        {
          value: 1,
          name: "Recolectados",
        },
        {
          value: 0,
          name: "Anulados",
        },
      ],
      to: new Date(),
      from: new Date(),
    };
  },
  mounted() {
    this.getDepartment("");
  },
  methods: {
    ...mapGetters(["api", "userID"]),
    // DEPARTAMENTS
    getReport() {
      window.open(
        `${this.api()}/report/collect?departament=${this.departament.id}&town=${
          this.town.id
        }&type=${this.type.value}&from=${this.$moment(this.from).format(
          "YYYY-MM-DD"
        )}&to=${this.$moment(this.to).format("YYYY-MM-DD")}`,
        "_blank"
      );
    },
    getDepartment(search) {
      let loader = this.$loading.show();
      axios
        .get(this.api() + `/getDepartment?filter=${search}`)
        .then((response) => {
          this.departaments = response.data;
          this.departaments.unshift({
            id: 0,
            name: "Todos",
          });
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          loader.hide();
        });
    },
    getTowns() {
      this.town = {
        id: 0,
        name: "Todos",
      };
      this.get();
      let loader = this.$loading.show();
      axios
        .get(this.api() + `/getTowns?idDepartament=${this.departament.id}`)
        .then((response) => {
          this.towns = response.data;
          this.towns.unshift({
            id: 0,
            name: "Todos",
          });
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          loader.hide();
        });
    },
  },
};
</script>
<style>
[role="menu"] {
  transform: translate3d(-140px, -50px, 0px) !important;
}
</style>
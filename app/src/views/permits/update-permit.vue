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
                <b-col cols="12" xl="6" lg="6" md="12" sm="12">
                  <multiselect
                    v-model="rol"
                    :options="rols"
                    :allow-empty="false"
                    :show-labels="false"
                    :searchable="true"
                    label="name"
                    track-by="name"
                    placeholder="Seleccione un rol"
                    @search-change="getRols"
                    :internal-search="false"
                    :loading="isLoadingRols"
                    @close="getAllPermits"
                  >
                  </multiselect>
                </b-col>
                <b-col cols="12" xl="6" lg="6" md="12" sm="12">
                  <multiselect
                    v-model="permit"
                    :options="permits"
                    :allow-empty="false"
                    :show-labels="false"
                    :searchable="true"
                    label="name"
                    track-by="name"
                    placeholder="Seleccione un permiso"
                    @close="getAllPermits"
                  >
                  </multiselect>
                </b-col>
              </b-row>
            </div>
            <hr />
            <!-- END SHOW -->
            <div class="container">
              <b-row v-for="p in allPermits" :key="p.id">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <b-form-group :label="p.name">
                    <label class="switch s-icons s-outline s-outline-primary">
                      <input
                        type="checkbox"
                        v-model="p.status"
                        @change="updatePermit(p)"
                      />
                      <span class="slider round"></span>
                    </label>
                  </b-form-group>
                </div>
              </b-row>
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

export default {
  metaInfo: { title: "Lista de permisos" },
  components: {
    Multiselect,
  },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Permisos", active: true },
      ],
      permit: null,
      permits: [],

      rol: null,
      rols: [],

      allPermits: [],

      isLoadingRols: false,
    };
  },
  mounted() {
    this.getPermits();
  },
  methods: {
    ...mapGetters(["api"]),
    getPermits() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + `/getPermits`)
        .then((response) => {
          this.permits = response.data;
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          loader.hide();
        });
    },
    getRols(search) {
      let loader = this.$loading.show();
      this.isLoadingRols = true;
      axios
        .get(this.api() + `/getRols?filter=${search}`)
        .then((response) => {
          this.isLoadingRols = false;
          this.rols = response.data;
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          this.isLoadingRols = false;
          loader.hide();
        });
    },
    getAllPermits() {
      if (
        this.rol !== "" &&
        this.rol !== null &&
        this.permit !== "" &&
        this.permit !== null
      ) {
        const idRol = this.rol.id;
        const idPermit = this.permit.id;
        let loader = this.$loading.show();
        axios
          .get(this.api() + `/getPermit/${idRol}/${idPermit}`)
          .then((response) => {
            this.allPermits = response.data;
            loader.hide();
          })
          .catch((error) => {
            console.log(error);
            loader.hide();
          });
      }
    },
    updatePermit(permit) {
      if (this.rol.id === 1) {
        this.$swal({
          text: "Los permisos del administrador no pueden editarse",
          icon: "error",
        });
        this.getAllPermits();
        return;
      }
      let loader = this.$loading.show();
      axios
        .post(this.api() + `/updatePermit/${permit.id}`, {
          status: permit.status,
        })
        .then((response) => {
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          loader.hide();
          this.getAllPermits();
        })
        .catch((error) => {
          this.$swal({
            text: `${error.response.data.message}`,
            icon: "error",
          });
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
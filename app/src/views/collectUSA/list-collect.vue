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
        <div class="panel br-0 p-3">
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
                    @close="get"
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
                    @close="get"
                  >
                  </multiselect>
                </b-col>
              </b-row>
              <hr />

              <b-row>
                <b-col cols="12" xl="4" lg="4" md="12" sm="12">
                  <flatpickr
                    v-model="from"
                    :config="config"
                    class="form-control flatpickr active"
                    @on-change="get"
                  ></flatpickr>
                </b-col>
                <b-col cols="12" xl="4" lg="4" md="12" sm="12">
                  <flatpickr
                    v-model="to"
                    :config="config"
                    class="form-control flatpickr active"
                    @on-change="get"
                  ></flatpickr>
                </b-col>
                <b-col cols="12" xl="4" lg="4" md="12" sm="12">
                  <h4>{{ total }} recolecciones en total</h4>
                </b-col>
              </b-row>
            </div>
            <hr />
            <!-- END SHOW -->
            <div class="table-header">
              <!-- SHOW -->
              <div class="d-flex align-items-center">
                <span>Mostrar :</span>
                <span class="ml-2">
                  <b-select v-model="show" size="sm" @change="get">
                    <b-select-option value="5">5</b-select-option>
                    <b-select-option value="10">10</b-select-option>
                    <b-select-option value="20">20</b-select-option>
                    <b-select-option value="50">50</b-select-option>
                  </b-select>
                </span>
              </div>
              <!-- END SHOW -->
              <!-- SEARCH -->
              <div class="header-search">
                <b-input
                  v-model="search"
                  size="sm"
                  placeholder="Buscar..."
                  @input="get"
                />
                <div class="search-image">
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
                    class="feather feather-search"
                  >
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                  </svg>
                </div>
              </div>
              <!-- END SEARCH -->
            </div>

            <b-table
              ref="basic_table"
              responsive
              hover
              :items="items"
              :fields="columns"
              :busy="isBusy"
              :show-empty="true"
            >
              <template #cell(telephone)="row">
                <a
                  :href="
                    row.item.telephone.length === 9
                      ? `tel:+502-${row.item.telephone}`
                      : `tel:+1-${row.item.telephone}`
                  "
                  >{{ row.item.telephone }}</a
                >
              </template>
              <template #cell(alternativeTelephone)="row">
                <template v-if="row.item.alternativeTelephone === null">
                  <p>{{ row.item.alternativeTelephone }}</p>
                </template>
                <template v-if="row.item.alternativeTelephone !== null">
                  <a
                    :href="
                      row.item.alternativeTelephone.length === 9
                        ? `tel:+502-${row.item.alternativeTelephone}`
                        : `tel:+1-${row.item.alternativeTelephone}`
                    "
                    >{{ row.item.alternativeTelephone }}</a
                  >
                </template>
              </template>
              <template #cell(departament)="row">
                <b-badge variant="secondary">{{
                  row.item.departament.name
                }}</b-badge>
              </template>
              <template #cell(town)="row">
                <b-badge variant="secondary">{{ row.item.town.name }}</b-badge>
              </template>
              <!-- getState -->

              <template #cell(status)="row">
                <b-badge variant="warning" v-if="row.item.status === 2"
                  >Pendiente</b-badge
                >
                <b-badge variant="success" v-if="row.item.status === 1"
                  >Recolectado</b-badge
                >
                <b-badge variant="danger" v-if="row.item.status === 0"
                  >Anulado</b-badge
                >
              </template>
              <template #cell(user_collect)="row">
                <b-badge variant="success">{{
                  row.item.user_collect.name
                }}</b-badge>
              </template>
              <template #cell(user_created)="row">
                <b-badge variant="success">{{
                  row.item.user_created.name
                }}</b-badge>
              </template>
              <template #cell(user_updated)="row">
                <b-badge variant="success">{{
                  row.item.user_updated.name
                }}</b-badge>
              </template>
              <template #cell(date)="row">
                {{ dateGTWithoutTime(row.item.date) }}
              </template>
              <template #cell(created_at)="row">
                {{ dateGT(row.item.created_at) }}
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
                      link-class="action-delete"
                      @click="ifAccept(row.item.id)"
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
                        <polyline points="20 6 9 17 4 12" />
                      </svg>
                      Recoger
                    </b-dropdown-item>
                    <b-dropdown-item
                      link-class="action-delete"
                      @click="ifDeny(row.item.id)"
                      v-if="row.item.status === 2"
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
                        <line
                          xmlns="http://www.w3.org/2000/svg"
                          x1="18"
                          y1="6"
                          x2="6"
                          y2="18"
                        />
                        <line
                          xmlns="http://www.w3.org/2000/svg"
                          x1="6"
                          y1="6"
                          x2="18"
                          y2="18"
                        />
                      </svg>
                      Anular
                    </b-dropdown-item>
                    <b-dropdown-item
                      @click="update(row.item.id)"
                      link-class="action-edit"
                      v-if="row.item.status === 2"
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
                        <path d="M12 20h9"></path>
                        <path
                          d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"
                        ></path>
                      </svg>
                      Editar
                    </b-dropdown-item>
                  </b-dropdown>
                </div>
              </template>
              <template #empty> No hay registros </template>
            </b-table>

            <div class="table-footer">
              <div
                class="
                  paginating-container
                  pagination-solid
                  flex-column
                  align-items-right
                "
              >
                <!-- END SHO REGISTRES -->
                <b-pagination
                  @change="handlePage"
                  :total-rows="pagination.total"
                  :per-page="pagination.per_page"
                  prev-text="Prev"
                  next-text="Next"
                  first-text="First"
                  last-text="Last"
                  first-class="first"
                  prev-class="prev"
                  next-class="next"
                  last-class="last"
                  class="rounded"
                >
                  <template #first-text>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7"
                      />
                    </svg>
                  </template>
                  <template #prev-text>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                      />
                    </svg>
                  </template>
                  <template #next-text>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                      />
                    </svg>
                  </template>
                  <template #last-text>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 5l7 7-7 7M5 5l7 7-7 7"
                      />
                    </svg>
                  </template>
                </b-pagination>
              </div>
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
      items: [],
      columns: [
        { key: "id", label: "ID" },
        { key: "name", label: "Nombre" },
        { key: "address", label: "Dirección" },
        { key: "telephone", label: "Teléfono" },
        { key: "alternativeTelephone", label: "Teléfono secundario" },
        { key: "date", label: "Fecha de recolección" },
        // { key: "state", label: "Estado USA" },
        // { key: "city", label: "Ciudad USA" },
        { key: "departament", label: "Departamento" },
        { key: "town", label: "Municipio" },
        { key: "status", label: "Estado" },
        { key: "user_collect", label: "Recolector" },
        { key: "user_created", label: "Creador" },
        { key: "user_updated", label: "Actualizó" },
        { key: "created_at", label: "Fecha de creación" },
        { key: "action", label: "Acciones", class: "actions text-center" },
      ],
      search: "",
      show: 10,
      isBusy: false,
      pagination: {
        current_page: 1,
        total: 1,
        per_page: 1,
      },
      meta: {},
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
      isLoadingShipments: false,
      total: 0,
    };
  },
  mounted() {
    this.get();
    this.getDepartment("");
  },
  methods: {
    ...mapGetters(["api", "userID"]),
    ifAccept(id) {
      this.$swal({
        title: "Quieres marcar este paquete como recolectado?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          this.accept(id);
        }
      });
    },
    accept(id) {
      let loader = this.$loading.show();
      axios
        .post(this.api() + `/collect/accept/${id}`, {
          userCollect: this.userID(),
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
    ifDeny(id) {
      this.$swal({
        title: "Quieres marcar este paquete como rechazado?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          this.deny(id);
        }
      });
    },
    deny(id) {
      let loader = this.$loading.show();
      axios
        .post(this.api() + `/collect/deny/${id}`, {
          userCollect: this.userID(),
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
    update(id) {
      this.$router.push({
        name: "UpdateCollect",
        params: {
          id,
        },
      });
    },
    handlePage(value) {
      this.pagination.current_page = value;
      this.get();
    },
    dateGT(date) {
      const newDate = this.$moment(date);
      return newDate.format("DD-MM-YYYY, h:mm:ss a");
    },
    dateGTWithoutTime(date) {
      const newDate = this.$moment(date);
      return newDate.format("DD-MM-YYYY");
    },
    get() {
      this.isBusy = true;
      if (this.search != "" && this.pagination.current_page != 1)
        this.pagination.current_page = 1;
      if (this.pagination.current_page === "undefined")
        this.pagination.current_page = 1;

      axios
        .get(
          this.api() +
            `/collect?show=${this.show}&search=${this.search}&page=${
              this.pagination.current_page
            }&departament=${this.departament.id}&town=${this.town.id}&type=${
              this.type.value
            }&from=${this.$moment(this.from).format(
              "YYYY-MM-DD"
            )}&to=${this.$moment(this.to).format("YYYY-MM-DD")}`
        )
        .then((response) => {
          this.isBusy = false;
          this.items = response.data.collect.data;
          this.pagination = {
            current_page: response.data.collect.current_page,
            total: response.data.collect.total,
            per_page: response.data.collect.per_page,
          };
          this.total = response.data.total;
        })
        .catch((error) => {
          this.isBusy = false;
          console.log(error);
        });
    },
    // DEPARTAMENTS
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
<template>
  <div class="layout-px-spacing">
    <portal to="breadcrumb">
      <div class="page-header">
        <nav class="breadcrumb-one" aria-label="breadcrumb">
          <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
        </nav>
      </div>
    </portal>
    <div class="container">
      <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="statbox panel box box-shadow">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                <h4>Busqueda</h4>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="row">
              <b-col cols="12" xl="4" lg="4" md="12" sm="12">
                <multiselect
                  v-model="office"
                  :options="offices"
                  :allow-empty="false"
                  :show-labels="false"
                  :searchable="true"
                  label="name"
                  track-by="name"
                  placeholder="Seleccione una oficina"
                  @close="get"
                >
                </multiselect>
              </b-col>
              <b-col cols="12" xl="4" lg="4" md="12" sm="12">
                <flatpickr
                  id="fecha"
                  name="fecha"
                  ref="fecha"
                  v-model="from"
                  :config="config"
                  class="form-control flatpickr active"
                  @on-change="get"
                ></flatpickr>
              </b-col>
              <b-col cols="12" xl="4" lg="4" md="12" sm="12">
                <flatpickr
                  id="fecha"
                  name="fecha"
                  ref="fecha"
                  v-model="to"
                  :config="config"
                  class="form-control flatpickr active"
                  @on-change="get"
                ></flatpickr>
              </b-col>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container" v-if="office.name !== 'Todos'">
      <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="statbox panel box box-shadow">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                <h4>{{ registre.type }} {{ office.name }}</h4>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                <ValidationObserver ref="observer">
                  <b-form
                    slot-scope="{ validate }"
                    @submit.prevent="validate().then(handleSubmit)"
                  >
                    <!-- DATE -->
                    <ValidationProvider
                      tag="div"
                      rules="required"
                      name="fecha"
                      v-slot="{ errors }"
                    >
                      <b-form-group
                        label="Fecha:"
                        :state="errors.length > 0 ? false : null"
                      >
                        <flatpickr
                          id="fecha"
                          name="fecha"
                          ref="fecha"
                          v-model="registre.date"
                          :config="config"
                          class="form-control flatpickr active"
                        ></flatpickr>
                        <b-form-invalid-feedback
                          :state="errors.length > 0 ? false : null"
                        >
                          {{ errors[0] }}
                        </b-form-invalid-feedback>
                      </b-form-group>
                    </ValidationProvider>
                    <!-- END DATE -->
                    <!-- GEOLOCATION -->
                    <ValidationProvider rules="required" name="libras">
                      <b-form-group
                        slot-scope="{ valid, errors }"
                        label="Libras"
                      >
                        <b-form-input
                          v-money="lbs"
                          v-model="registre.value"
                          name="libras"
                          type="text"
                          :state="errors[0] ? false : valid ? true : null"
                        ></b-form-input>

                        <b-form-invalid-feedback>
                          {{ errors[0] }}
                        </b-form-invalid-feedback>
                      </b-form-group>
                    </ValidationProvider>
                    <!-- END GEOLOCATION -->
                    <hr />
                    <div class="text-center">
                      <b-button-group>
                        <b-button variant="danger" @click="clean"
                          >Nuevo</b-button
                        >
                        <b-button type="submit" variant="primary"
                          >Guardar</b-button
                        >
                      </b-button-group>
                    </div>
                  </b-form>
                </ValidationObserver>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="statbox panel box box-shadow">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                <h4>Lista</h4>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <b-table
              ref="basic_table"
              responsive
              hover
              :items="items"
              :fields="columns"
              :busy="isBusy"
              :show-empty="true"
            >
              <template #cell(value)="row">
                {{ myLbs(row.item.value) }}
              </template>
              <template #cell(date)="row">
                {{ dateGTWithOutHour(row.item.date) }}
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
                      @click="update(row.item)"
                      link-class="action-edit"
                      v-if="office.name !== 'Todos'"
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
                    <b-dropdown-item
                      link-class="action-delete"
                      @click="ifDelete(row.item.id)"
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
                      Eliminar
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
import Vue from "vue";
import axios from "axios";
// DATE
import "flatpickr/dist/flatpickr.min.css";
import "@/assets/sass/forms/custom-flatpickr.css";
import flatpickr from "flatpickr";
import { Spanish } from "flatpickr/dist/l10n/es";
import Vueflatpickr from "vue-flatpickr-component";
flatpickr.localize(Spanish);
// END DATE
import { mapGetters } from "vuex";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import es from "vee-validate/dist/locale/es";
import { VMoney } from "v-money";

Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });
import { eight } from "../../utils/eight";

export default {
  metaInfo: { title: "Oficinas GT" },
  directives: { money: VMoney },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Oficinas GT", active: true },
      ],
      pagination: {
        current_page: 1,
        total: 1,
        per_page: 1,
      },
      config: {
        wrap: true, // set wrap to true only when using 'input-group'
        altFormat: "d-m-Y",
        altInput: true,
        dateFormat: "Y-m-d",
        locale: Spanish, // locale for this instance only
      },
      lbs: {
        decimal: ".",
        thousands: ",",
        prefix: "",
        suffix: " lbs",
        precision: 2,
        masked: true,
      },
      items: [],
      columns: [
        { key: "name", label: "Oficina" },
        { key: "date", label: "Fecha" },
        { key: "value", label: "Libras" },
        { key: "created_at", label: "Fecha de creación" },
        { key: "action", label: "Acciones", class: "actions text-center" },
      ],
      places: [],
      office: {
        name: "Todos",
      },
      offices: [],
      from: new Date(),
      to: new Date(),
      isBusy: false,
      registre: {
        id: 0,
        type: "Nuevo registro de",
        status: true,
        date: new Date(),
        value: "",
      },
    };
  },
  components: {
    ValidationObserver,
    ValidationProvider,
    Multiselect,
    flatpickr: Vueflatpickr,
  },
  mounted() {
    this.getOfficesGT();
    this.clean();
    this.get();
  },
  methods: {
    ...mapGetters(["api", "upload"]),
    myLbs(value) {
      return eight.lbs(parseFloat(value));
    },
    update(item) {
      this.registre = {
        id: item.id,
        type: "Editar registro de",
        status: false,
        date: item.date,
        value: item.value,
      };
    },
    dateGT(date) {
      const newDate = this.$moment(date);
      return newDate.format("DD-MM-YYYY, h:mm:ss a");
    },
    dateGTWithOutHour(date) {
      const newDate = this.$moment(date);
      return newDate.format("DD-MM-YYYY");
    },
    get() {
      this.isBusy = true;
      axios
        .get(
          this.api() +
            `/officeGT?office=${this.office.name}&show=10&from=${this.$moment(
              this.from
            ).format("YYYY-MM-DD")}&to=${this.$moment(this.to).format(
              "YYYY-MM-DD"
            )}`
        )
        .then((response) => {
          this.isBusy = false;
          this.items = response.data.data;
          this.pagination = {
            current_page: response.data.current_page,
            total: response.data.total,
            per_page: response.data.per_page,
          };
        })
        .catch((error) => {
          this.isBusy = false;
          console.log(error);
        });
    },
    openMap(place) {
      const location = place.geolocation.split(",");
      if (location.length > 1) {
        const latitude = location[0].replace(/\s/g, "");
        const longitude = location[1].replace(/\s/g, "");
        window.open(
          `https://www.google.com/maps/search/?api=1&query=${latitude}%2C${longitude}`,
          "_blank"
        );
      }
    },
    converMaskToNumber(value) {
      var myNumber = "";
      for (var i = 0; i < value.length; i += 1) {
        if (
          value.charAt(i) === "l" ||
          value.charAt(i) === "b" ||
          value.charAt(i) === "s" ||
          value.charAt(i) === "$" ||
          value.charAt(i) === "," ||
          value.charAt(i) === " " ||
          value.charAt(i) === "%"
        ) {
          continue;
        } else {
          myNumber += value.charAt(i);
        }
      }
      return parseFloat(myNumber).toFixed(2);
    },
    handleSubmit() {
      let loader = this.$loading.show();
      if (this.registre.status === true) {
        axios
          .post(this.api() + "/officeGT", {
            name: this.office.name,
            date: this.$moment(this.registre.date).format("YYYY-MM-DD"),
            value: this.converMaskToNumber(this.registre.value),
          })
          .then((response) => {
            loader.hide();
            this.$swal({ text: `${response.data.message}`, icon: "success" });
            this.clean();
            this.get();
          })
          .catch((error) => {
            loader.hide();
            this.$swal({
              text: `${error.response.data.message}`,
              icon: "error",
            });
          });
      } else {
        axios
          .put(this.api() + `/officeGT/${this.registre.id}`, {
            name: this.office.name,
            date: this.$moment(this.registre.date).format("YYYY-MM-DD"),
            value: this.converMaskToNumber(this.registre.value),
          })
          .then((response) => {
            loader.hide();
            this.$swal({ text: `${response.data.message}`, icon: "success" });
            this.clean();
            this.get();
          })
          .catch((error) => {
            loader.hide();
            this.$swal({
              text: `${error.response.data.message}`,
              icon: "error",
            });
          });
      }
    },
    clean() {
      this.registre = {
        id: 0,
        type: "Nuevo registro de",
        status: true,
        date: new Date(),
        value: "",
      };
    },
    getOfficesGT() {
      axios
        .get(this.api() + "/setting/5")
        .then((response) => {
          const offices = JSON.parse(response.data.value);
          this.offices = offices.map((office) => {
            return {
              name: office,
            };
          });
          this.offices.unshift({
            name: "Todos",
          });
          this.office = this.offices[0];
        })
        .catch((error) => {
          console.log(error);
        });
    },
    handlePage(value) {
      this.pagination.current_page = value;
      this.get();
    },
    ifDelete(id) {
      this.$swal({
        title: "Quieres eliminar este registro?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          this.delete(id);
        }
      });
    },
    delete(id) {
      let loader = this.$loading.show();
      axios
        .delete(this.api() + `/officeGT/${id}`)
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
  },
};
</script>

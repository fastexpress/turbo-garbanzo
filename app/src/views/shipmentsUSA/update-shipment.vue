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
      <div class="row layout-top-spacing">
        <div id="basic" class="col-lg-12 layout-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Nuevo envío a USA</h4>
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
                        <!-- CITY -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="ciudad"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Ciudad:"
                            :state="errors.length > 0 ? false : null"
                          >
                            <!-- getShipments -->
                            <multiselect
                              id="ciudad"
                              name="ciudad"
                              ref="ciudad"
                              v-model="city"
                              :options="cities"
                              placeholder="Seleccione una ciudad"
                              :class="errors.length > 0 ? 'is-danger' : ''"
                              @close="getTotalWeight"
                            >
                            </multiselect>
                            <b-form-invalid-feedback
                              :state="errors.length > 0 ? false : null"
                            >
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END CITY -->
                        <!-- DATE SHIPMENT -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="fecha"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Fecha de envío:"
                            :state="errors.length > 0 ? false : null"
                          >
                            <flatpickr
                              id="fecha"
                              name="fecha"
                              ref="fecha"
                              v-model="date"
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
                        <!-- END DATE SHIPMENT -->
                        <!-- USER -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="encargado"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Encargado"
                            :state="errors.length > 0 ? false : null"
                          >
                            <!-- getShipments -->
                            <multiselect
                              id="encargado"
                              name="encargado"
                              ref="encargado"
                              v-model="charge"
                              :options="charges"
                              :show-labels="false"
                              :searchable="true"
                              label="name"
                              track-by="name"
                              placeholder="Seleccione un encargado"
                              @search-change="getCharges"
                              :internal-search="false"
                              :class="errors.length > 0 ? 'is-danger' : ''"
                              :loading="loaderCharge"
                            >
                            </multiselect>
                            <b-form-invalid-feedback
                              :state="errors.length > 0 ? false : null"
                            >
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END USER -->
                        <b-form-group
                          :label="
                            city === '' || city === null
                              ? 'Seleccione una ciudad'
                              : `Total de paquetes para ${city}`
                          "
                        >
                          <h3>{{ myLbs(total) }}</h3>
                        </b-form-group>
                        <modal-bag-new @save-bag="saveBag" />
                        <b-table
                          ref="table"
                          responsive
                          hover
                          :items="bags"
                          :fields="columns"
                          :show-empty="true"
                        >
                          <template #cell(id)="row">
                            {{ row.index + 1 }}
                          </template>
                          <template #cell(traveler)="row">
                            {{ row.item.traveler.name }}
                          </template>
                          <template #cell(capacity)="row">
                            {{ myLbs(row.item.capacity) }}
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
                                  Actualizar
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
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                      d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                                    ></path>
                                  </svg>
                                  Eliminar
                                </b-dropdown-item>
                              </b-dropdown>
                            </div>
                          </template>
                          <template #empty> No hay registros </template>
                        </b-table>
                        <hr />
                        <b-button block type="submit" variant="primary"
                          >Guardar</b-button
                        >
                      </b-form>
                    </ValidationObserver>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- MODALS -->
    <modal-bag-update ref="updateModal" @update-bag="updateBag" />
    <!-- END MODALS -->
  </div>
</template>
<script>
import Vue from "vue";
import axios from "axios";
import { mapGetters, mapMutations } from "vuex";
import "flatpickr/dist/flatpickr.min.css";
import "@/assets/sass/forms/custom-flatpickr.css";
import flatpickr from "flatpickr";
import { Spanish } from "flatpickr/dist/l10n/es";
import Vueflatpickr from "vue-flatpickr-component";
flatpickr.localize(Spanish);

import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import modalBagNew from "./update-modals/modal-bag-new.vue";
import modalBagUpdate from "./update-modals/modal-bag-update.vue";

import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import es from "vee-validate/dist/locale/es";

import { eight } from "../../utils/eight";

Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });
export default {
  metaInfo: { title: "Nuevo envío" },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Nuevo envío", active: true },
      ],
      config: {
        wrap: true, // set wrap to true only when using 'input-group'
        altFormat: "d-m-Y",
        altInput: true,
        dateFormat: "Y-m-d",
        locale: Spanish, // locale for this instance only
      },
      columns: [
        { key: "id", label: "ID" },
        { key: "bag", label: "Tipo de maleta" },
        { key: "capacity", label: "Capacidad" },
        { key: "traveler", label: "Viajero" },
        { key: "action", label: "Acciones", class: "actions text-center" },
      ],
      id: 0,

      date: new Date(),

      cities: [],
      city: "",

      charge: "",
      charges: [],
      loaderCharge: false,

      bags: [],

      total: 0,
    };
  },
  components: {
    ValidationObserver,
    ValidationProvider,
    flatpickr: Vueflatpickr,
    Multiselect,
    "modal-bag-new": modalBagNew,
    "modal-bag-update": modalBagUpdate,
  },
  mounted() {
    this.getCities();
    this.find();
  },
  methods: {
    ...mapGetters(["api", "getBag", "getUpdateBag"]),
    ...mapMutations(["setBag", "setUpdateBag"]),
    find() {
      axios
        .get(this.api() + `/shipments/usa/${this.$route.params.id}`)
        .then((response) => {
          const { bags, shipments } = response.data;
          (this.id = shipments.id), (this.city = shipments.city);
          this.date = shipments.date;
          this.charge = shipments.in_charge;
          this.bags = [];
          bags.forEach((bag) => {
            this.bags.push({
              id: bag.id,
              bag: bag.bag,
              capacity: bag.capacity,
              traveler: bag.user_traveler,
            });
          });
          this.getTotalWeight();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getTotalWeight() {
      if (this.city !== "" || this.city !== null) {
        let loader = this.$loading.show();
        axios
          .get(this.api() + `/getTotalWeight?city=${this.city}`)
          .then((response) => {
            loader.hide();
            this.total = response.data.total;
          })
          .catch((error) => {
            console.log(error);
            this.loaderCharge = false;
          });
      }
    },
    getCharges(search) {
      this.loaderCharge = true;
      axios
        .get(this.api() + `/getTravelers?filter=${search}`)
        .then((response) => {
          this.loaderCharge = false;
          this.charges = response.data.data;
        })
        .catch((error) => {
          console.log(error);
          this.loaderCharge = false;
        });
    },
    getCities() {
      axios
        .get(this.api() + "/setting/3")
        .then((response) => {
          this.cities = JSON.parse(response.data.value);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    myLbs(value) {
      return eight.lbs(parseFloat(value));
    },
    saveBag() {
      const bag = this.getBag();
      const lenght = bag.quantity;
      for (let index = 0; index < lenght; index++) {
        this.bags.push({
          id: this.uuidv4(),
          bag: bag.bag,
          capacity: bag.capacity,
          traveler: bag.traveler,
        });
      }
      this.$refs.table.refresh();
    },
    handleSubmit() {
      if (this.bags.length < 1) {
        this.$swal({
          text: "Para guardar este envío es necesario tener equipaje agregado",
          icon: "error",
        });
        return;
      }
      let loader = this.$loading.show();
      axios
        .put(this.api() + `/shipments/usa/${this.id}`, {
          city: this.city,
          charge: this.charge.id,
          date: this.$moment(this.date).format("YYYY-MM-DD"),
          bags: JSON.stringify(this.bags),
        })
        .then((response) => {
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          loader.hide();
          this.$router.push({
            path: `/envios/usa/lista`,
          });
        })
        .catch((error) => {
          this.$swal({
            text: `${error.response.data.message}`,
            icon: "error",
          });
          loader.hide();
        });
    },
    uuidv4() {
      return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, (c) =>
        (
          crypto.getRandomValues(new Uint8Array(1))[0] &
          (15 >> (c / 4))
        ).toString(16)
      );
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
      const index = this.bags.findIndex((x) => x.id === id);
      if (index !== -1) {
        this.bags.splice(index, 1);
      }
    },
    update(item) {
      this.setUpdateBag(item);
      this.$refs.updateModal.openModal();
    },
    updateBag() {
      let myBag = this.getUpdateBag();
      const index = this.bags.findIndex((x) => x.id === myBag.id);
      if (index !== -1) {
        this.bags[index] = myBag;
      }
      this.$refs.table.refresh();
    },
  },
};
</script>
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
      <div class="row">
        <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Caja encargada de recibir el dinero paquetería</h4>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <ValidationObserver ref="observer">
                    <b-form
                      slot-scope="{ validate }"
                      @submit.prevent="validate().then(saveFirst)"
                    >
                      <!-- CHARGE -->
                      <ValidationProvider
                        tag="div"
                        rules="required"
                        name="encargado"
                        v-slot="{ errors }"
                      >
                        <b-form-group
                          label="Encargado:"
                          :state="errors.length > 0 ? false : null"
                        >
                          <!-- getShipments -->
                          <multiselect
                            id="encargado"
                            name="encargado"
                            ref="encargado"
                            v-model="userCharge"
                            :options="usersCharge"
                            :show-labels="false"
                            :searchable="true"
                            label="name"
                            track-by="name"
                            placeholder="Seleccione un encargado"
                            @search-change="getCashRegister"
                            :internal-search="false"
                            :class="errors.length > 0 ? 'is-danger' : ''"
                            :loading="loaderUserCharge"
                          >
                          </multiselect>
                          <b-form-invalid-feedback
                            :state="errors.length > 0 ? false : null"
                          >
                            {{ errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </ValidationProvider>
                      <!-- END CHARGE -->
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
      <div class="row">
        <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Cambio de Dólar a Quetzal</h4>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <ValidationObserver ref="observer">
                    <b-form
                      slot-scope="{ validate }"
                      @submit.prevent="validate().then(saveSecond)"
                    >
                      <!-- AMOUNT -->
                      <ValidationProvider rules="required" name="cantidad">
                        <b-form-group
                          slot-scope="{ valid, errors }"
                          label="Cantidad"
                        >
                          <b-form-input
                            v-money="quetzal"
                            v-model="quantity"
                            name="cantidad"
                            type="text"
                            :state="errors[0] ? false : valid ? true : null"
                          ></b-form-input>
                          <b-form-invalid-feedback>
                            {{ errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </ValidationProvider>
                      <!-- END AMOUNT -->
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
      <div class="row">
        <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Estados para envíar por paquetería</h4>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <ValidationObserver ref="observer">
                    <b-form
                      slot-scope="{ validate }"
                      @submit.prevent="validate().then(saveThree)"
                    >
                      <!-- ESTADO -->
                      <ValidationProvider
                        tag="div"
                        rules="required"
                        name="estado"
                        v-slot="{ errors }"
                      >
                        <b-form-group
                          label="Estado:"
                          :state="errors.length > 0 ? false : null"
                        >
                          <!-- getShipments -->
                          <multiselect
                            id="estado"
                            name="estado"
                            ref="estado"
                            v-model="place"
                            :options="places"
                            tag-placeholder="Agregar estado"
                            :multiple="true"
                            :taggable="true"
                            label="name"
                            track-by="name"
                            placeholder="Seleccione los estados"
                            :class="errors.length > 0 ? 'is-danger' : ''"
                            @tag="addPlace"
                          >
                          </multiselect>
                          <b-form-invalid-feedback
                            :state="errors.length > 0 ? false : null"
                          >
                            {{ errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </ValidationProvider>
                      <!-- END ESTADO -->
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
      <div class="row">
        <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Oficina central en Guatemala</h4>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <ValidationObserver ref="observer">
                    <b-form
                      slot-scope="{ validate }"
                      @submit.prevent="validate().then(saveFour)"
                    >
                      <!-- AMOUNT -->
                      <ValidationProvider rules="required" name="oficina">
                        <b-form-group
                          slot-scope="{ valid, errors }"
                          label="Oficina"
                        >
                          <b-form-input
                            v-model="office"
                            name="oficina"
                            type="text"
                            :state="errors[0] ? false : valid ? true : null"
                          ></b-form-input>
                          <b-form-invalid-feedback>
                            {{ errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </ValidationProvider>
                      <!-- END AMOUNT -->
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
      <div class="row">
        <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Oficinas en Guatemala</h4>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <ValidationObserver ref="observer">
                    <b-form
                      slot-scope="{ validate }"
                      @submit.prevent="validate().then(saveThree)"
                    >
                      <!-- ESTADO -->
                      <ValidationProvider
                        tag="div"
                        rules="required"
                        name="oficina"
                        v-slot="{ errors }"
                      >
                        <b-form-group
                          label="Oficinas:"
                          :state="errors.length > 0 ? false : null"
                        >
                          <!-- getShipments -->
                          <multiselect
                            id="oficina"
                            name="oficina"
                            ref="oficina"
                            v-model="placeGT"
                            :options="placesGT"
                            tag-placeholder="Agregar oficina"
                            :multiple="true"
                            :taggable="true"
                            label="name"
                            track-by="name"
                            placeholder="Seleccione las oficinas"
                            :class="errors.length > 0 ? 'is-danger' : ''"
                            @tag="addPlaceGT"
                          >
                          </multiselect>
                          <b-form-invalid-feedback
                            :state="errors.length > 0 ? false : null"
                          >
                            {{ errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </ValidationProvider>
                      <!-- END ESTADO -->
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
      <div class="row">
        <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Pago a las sucursales en Guatemala</h4>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <ValidationObserver ref="observer">
                    <b-form
                      slot-scope="{ validate }"
                      @submit.prevent="validate().then(saveSix)"
                    >
                      <!-- AMOUNT -->
                      <ValidationProvider rules="required" name="cantidad">
                        <b-form-group
                          slot-scope="{ valid, errors }"
                          label="Cantidad"
                        >
                          <b-form-input
                            v-money="quetzal"
                            v-model="price"
                            name="cantidad"
                            type="text"
                            :state="errors[0] ? false : valid ? true : null"
                          ></b-form-input>
                          <b-form-invalid-feedback>
                            {{ errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </ValidationProvider>
                      <!-- END AMOUNT -->
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
      <div class="row">
        <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Serie</h4>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <ValidationObserver ref="observer">
                    <b-form
                      slot-scope="{ validate }"
                      @submit.prevent="validate().then(saveSeven)"
                    >
                      <!-- AMOUNT -->
                      <ValidationProvider rules="required" name="serie">
                        <b-form-group
                          slot-scope="{ valid, errors }"
                          label="Serie"
                        >
                          <b-form-input
                            v-model="serie"
                            name="serie"
                            type="text"
                            :state="errors[0] ? false : valid ? true : null"
                          ></b-form-input>
                          <b-form-invalid-feedback>
                            {{ errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </ValidationProvider>
                      <!-- END AMOUNT -->
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
      <div class="row">
        <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Tipo de equipaje</h4>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <ValidationObserver ref="observer">
                    <b-form
                      slot-scope="{ validate }"
                      @submit.prevent="validate().then(saveEight)"
                    >
                      <!-- BAG -->
                      <ValidationProvider
                        tag="div"
                        rules="required"
                        name="equipaje"
                        v-slot="{ errors }"
                      >
                        <b-form-group
                          label="Equipajes:"
                          :state="errors.length > 0 ? false : null"
                        >
                          <!-- getShipments -->
                          <multiselect
                            id="equipaje"
                            name="equipaje"
                            ref="equipaje"
                            v-model="bag"
                            :options="suitcases"
                            tag-placeholder="Agregar equipaje"
                            :multiple="true"
                            :taggable="true"
                            label="name"
                            track-by="name"
                            placeholder="Seleccione los equipajes"
                            :class="errors.length > 0 ? 'is-danger' : ''"
                            @tag="addBag"
                          >
                          </multiselect>
                          <b-form-invalid-feedback
                            :state="errors.length > 0 ? false : null"
                          >
                            {{ errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </ValidationProvider>
                      <!-- END BAG -->
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
      <!-- STEP NINE -->
      <div class="row">
        <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Cuentas para cobrar las cajas UPS</h4>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <ValidationObserver ref="observer">
                    <b-form
                      slot-scope="{ validate }"
                      @submit.prevent="validate().then(saveNine)"
                    >
                      <!-- CHARGE -->
                      <ValidationProvider
                        tag="div"
                        rules="required"
                        name="cuentas personales"
                        v-slot="{ errors }"
                      >
                        <b-form-group
                          label="Cuentas:"
                          :state="errors.length > 0 ? false : null"
                        >
                          <!-- getShipments -->
                          <multiselect
                            id="cuentas personales"
                            name="cuentas personales"
                            ref="cuentas personales"
                            v-model="accountPersonal"
                            :options="accountsPersonal"
                            :show-labels="false"
                            :searchable="true"
                            label="id"
                            track-by="id"
                            :custom-label="customLabelAccontPersonal"
                            placeholder="Seleccione las cuentas"
                            @search-change="getPersonalAccounts"
                            :multiple="true"
                            :internal-search="false"
                            :class="errors.length > 0 ? 'is-danger' : ''"
                            :loading="accountsPersonalLoader"
                          >
                          </multiselect>
                          <b-form-invalid-feedback
                            :state="errors.length > 0 ? false : null"
                          >
                            {{ errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </ValidationProvider>
                      <!-- END CHARGE -->
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
      <!-- END STEP NINE -->
      <!-- STEN TEN -->
      <div class="row">
        <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Cambio de Dólar a Quetzal Cajas UPS</h4>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <ValidationObserver ref="observer">
                    <b-form
                      slot-scope="{ validate }"
                      @submit.prevent="validate().then(saveTen)"
                    >
                      <!-- AMOUNT -->
                      <ValidationProvider rules="required" name="cantidad">
                        <b-form-group
                          slot-scope="{ valid, errors }"
                          label="Cantidad"
                        >
                          <b-form-input
                            v-money="quetzal"
                            v-model="quantityDolarDefault"
                            name="cantidad"
                            type="text"
                            :state="errors[0] ? false : valid ? true : null"
                          ></b-form-input>
                          <b-form-invalid-feedback>
                            {{ errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </ValidationProvider>
                      <!-- END AMOUNT -->
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
      <!-- END STEP TEN -->
      <!-- STEP ELEVEN -->
      <!-- <div class="row">
        <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Tipo de equipaje</h4>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <ValidationObserver ref="observer">
                    <b-form
                      slot-scope="{ validate }"
                      @submit.prevent="validate().then(saveEleven)"
                    >
                      <ValidationProvider
                        tag="div"
                        rules="required"
                        name="empaques"
                        v-slot="{ errors }"
                      >
                        <b-form-group
                          label="Empaques:"
                          :state="errors.length > 0 ? false : null"
                        >
                          <multiselect
                            id="empaques"
                            name="empaques"
                            ref="empaques"
                            v-model="packing"
                            :options="packaging"
                            tag-placeholder="Agregar empaque"
                            :multiple="true"
                            :taggable="true"
                            label="name"
                            track-by="name"
                            placeholder="Seleccione los empaques"
                            :class="errors.length > 0 ? 'is-danger' : ''"
                            @tag="addPacking"
                          >
                          </multiselect>
                          <b-form-invalid-feedback
                            :state="errors.length > 0 ? false : null"
                          >
                            {{ errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </ValidationProvider>
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
      </div> -->
      <!-- END STEP ELEVEN -->
    </div>
  </div>
</template>
<script>
import Vue from "vue";
import axios from "axios";
import { mapGetters } from "vuex";
import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import es from "vee-validate/dist/locale/es";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import { VMoney } from "v-money";
import { eight } from "../../utils/eight";

Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });

export default {
  metaInfo: { title: "Ajustes" },
  directives: { money: VMoney },

  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Ajustes", active: true },
      ],
      quetzal: {
        decimal: ".",
        thousands: ",",
        prefix: "Q ",
        suffix: "",
        precision: 2,
        masked: true,
      },
      // STEP 1
      userCharge: null,
      usersCharge: [],
      loaderUserCharge: false,
      // STEP 2
      quantity: "",
      // STEP THREE PLACES
      places: [],
      place: [],
      office: "",
      // STEP FIVE
      placeGT: [],
      placesGT: [],
      // STEP SIX
      price: "",
      // STEP SEVEN
      serie: "",
      // STEP EIGHT
      suitcases: [],
      bag: [],
      // STEP NINE
      accountPersonal: [],
      accountsPersonal: [],
      accountsPersonalLoader: false,
      // STEP TEN
      quantityDolarDefault: "",
      // STEP ELEVEN
      packaging: [],
      packing: [],
    };
  },
  components: {
    ValidationObserver,
    ValidationProvider,
    Multiselect,
  },
  mounted() {
    this.getFirst();
    this.getSecond();
    this.getThree();
    this.getFour();
    this.getFive();
    this.getSix();
    this.getSeven();
    this.getEight();
    this.getNine();
    this.getTen();
    this.getEleven();
  },
  methods: {
    ...mapGetters(["api"]),
    myMoney(value) {
      return eight.money(parseFloat(value));
    },
    getCashRegister(search) {
      this.loaderUserCharge = true;
      axios
        .get(this.api() + `/getCashRegister?filter=${search}`)
        .then((response) => {
          this.loaderUserCharge = false;
          this.usersCharge = response.data.data;
        })
        .catch((error) => {
          console.log(error);
          this.loaderUserCharge = false;
        });
    },
    getPersonalAccounts(search) {
      this.accountsPersonalLoader = true;
      axios
        .get(this.api() + `/getAccountPersonal?filter=${search}`)
        .then((response) => {
          this.accountsPersonalLoader = false;
          this.accountsPersonal = response.data.data;
        })
        .catch((error) => {
          console.log(error);
          this.accountsPersonalLoader = false;
        });
    },
    saveFirst() {
      let loader = this.$loading.show();
      axios
        .put(this.api() + "/setting/1", {
          name: "cash_package",
          value: JSON.stringify(this.userCharge),
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.getFirst();
        })
        .catch((error) => {
          loader.hide();

          console.log(error);
        });
    },
    getFirst() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + "/setting/1")
        .then((response) => {
          loader.hide();
          const { value } = response.data;
          if (value !== "") {
            this.userCharge = JSON.parse(value.replaceAll(/\\/g, ""));
          } else {
            this.userCharge = null;
          }
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    saveSecond() {
      let loader = this.$loading.show();
      axios
        .put(this.api() + "/setting/2", {
          name: "dollar_to_quetzal",
          value: this.quantity,
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.getSecond();
        })
        .catch((error) => {
          loader.hide();

          console.log(error);
        });
    },
    getSecond() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + "/setting/2")
        .then((response) => {
          loader.hide();
          const { value } = response.data;
          this.quantity = value;
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    saveThree() {
      let loader = this.$loading.show();
      const place = this.place.map((place) => {
        return place.name;
      });
      axios
        .put(this.api() + "/setting/3", {
          name: "state_package",
          value: JSON.stringify(place),
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.getThree();
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    getThree() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + "/setting/3")
        .then((response) => {
          loader.hide();
          const { value } = response.data;
          if (value !== "") {
            const places = JSON.parse(value);
            this.place = places.map((place) => {
              return {
                name: place,
              };
            });
            this.places = this.place;
          } else {
            this.place = [];
          }
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    addPlace(newPlace) {
      const place = {
        name: newPlace,
      };
      this.places.push(place);
    },
    addPlaceGT(newPlace) {
      const place = {
        name: newPlace,
      };
      this.placesGT.push(place);
    },
    saveFour() {
      let loader = this.$loading.show();
      axios
        .put(this.api() + "/setting/4", {
          name: "central_office",
          value: this.office,
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.getFour();
        })
        .catch((error) => {
          loader.hide();

          console.log(error);
        });
    },
    getFour() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + "/setting/4")
        .then((response) => {
          loader.hide();
          const { value } = response.data;
          this.office = value;
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    saveFive() {
      let loader = this.$loading.show();
      const place = this.placeGT.map((place) => {
        return place.name;
      });
      axios
        .put(this.api() + "/setting/5", {
          name: "guatemala_offices",
          value: JSON.stringify(place),
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.getFive();
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    getFive() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + "/setting/5")
        .then((response) => {
          loader.hide();
          const { value } = response.data;
          if (value !== "") {
            const places = JSON.parse(value);
            this.placeGT = places.map((place) => {
              return {
                name: place,
              };
            });
            this.placesGT = this.placeGT;
          } else {
            this.placeGT = [];
          }
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    saveSix() {
      let loader = this.$loading.show();
      axios
        .put(this.api() + "/setting/6", {
          name: "payment_guatemala_offices",
          value: this.price,
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.getSix();
        })
        .catch((error) => {
          loader.hide();

          console.log(error);
        });
    },
    getSix() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + "/setting/6")
        .then((response) => {
          loader.hide();
          const { value } = response.data;
          this.price = value;
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    saveSeven() {
      let loader = this.$loading.show();
      axios
        .put(this.api() + "/setting/7", {
          name: "serie",
          value: this.serie,
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.getSeven();
        })
        .catch((error) => {
          loader.hide();

          console.log(error);
        });
    },
    getSeven() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + "/setting/7")
        .then((response) => {
          loader.hide();
          const { value } = response.data;
          this.serie = value;
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    saveEight() {
      let loader = this.$loading.show();
      const suitcases = this.bag.map((bag) => {
        return bag.name;
      });
      axios
        .put(this.api() + "/setting/8", {
          name: "guatemala_offices",
          value: JSON.stringify(suitcases),
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.getEight();
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    getEight() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + "/setting/8")
        .then((response) => {
          loader.hide();
          const { value } = response.data;
          if (value !== "") {
            const suitcases = JSON.parse(value);
            this.suitcases = suitcases.map((bag) => {
              return {
                name: bag,
              };
            });
            this.bag = this.suitcases;
          } else {
            this.bag = [];
          }
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    addBag(newBag) {
      const bag = {
        name: newBag,
      };
      this.suitcases.push(bag);
    },
    // NINE
    saveNine() {
      let loader = this.$loading.show();
      axios
        .put(this.api() + "/setting/9", {
          name: "accounts_personal",
          value: JSON.stringify(this.accountPersonal),
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.getNine();
        })
        .catch((error) => {
          loader.hide();

          console.log(error);
        });
    },
    getNine() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + "/setting/9")
        .then((response) => {
          loader.hide();
          const { value } = response.data;
          if (value !== "") {
            const accounts = JSON.parse(value.replaceAll(/\\/g, ""));

            accounts.forEach((account) => {
              this.accountPersonal = [];
              let loader = this.$loading.show();
              axios
                .get(this.api() + `/accountspersonal/${account.id}`)
                .then((response) => {
                  loader.hide();
                  this.accountPersonal.push({
                    id: response.data.id,
                    name: response.data.name,
                    amountBank: response.data.amountBank,
                    bank: response.data.bank,
                  });
                })
                .catch((error) => {
                  loader.hide();
                  console.log(error);
                });
            });
          } else {
            this.accountPersonal = [];
          }
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    customLabelAccontPersonal({ name, amountBank, bank }) {
      return `${name} - ${this.myMoney(amountBank)} - ${bank}`;
    },
    // END NINE
    // STEP TEN
    saveTen() {
      let loader = this.$loading.show();
      axios
        .put(this.api() + "/setting/10", {
          name: "dollar_to_quetzal_default",
          value: this.quantityDolarDefault,
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.getTen();
        })
        .catch((error) => {
          loader.hide();

          console.log(error);
        });
    },
    getTen() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + "/setting/10")
        .then((response) => {
          loader.hide();
          const { value } = response.data;
          this.quantityDolarDefault = value;
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    // END STEP TEN
    // STEP ELEVEN
    saveEleven() {
      let loader = this.$loading.show();
      const packaging = this.packing.map((packing) => {
        return packing.name;
      });
      axios
        .put(this.api() + "/setting/11", {
          name: "types_of_packaging",
          value: JSON.stringify(packaging),
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.getEleven();
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    getEleven() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + "/setting/11")
        .then((response) => {
          loader.hide();
          const { value } = response.data;
          if (value !== "") {
            const packaging = JSON.parse(value);
            this.packaging = packaging.map((packing) => {
              return {
                name: packing,
              };
            });
            this.packing = this.packaging;
          } else {
            this.packing = [];
          }
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    addPacking(newPacking) {
      const packing = {
        name: newPacking,
      };
      this.packaging.push(packing);
    },
    // END STEP ELEVEN
  },
};
</script>

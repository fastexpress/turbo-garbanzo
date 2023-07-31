<template>
  <div v-if="typical === false">
    <!-- DEPARTAMENT -->
    <ValidationProvider
      tag="div"
      rules="required"
      name="departamento"
      v-slot="{ errors }"
    >
      <b-form-group
        label="Departamento del que envía:"
        :state="errors.length > 0 ? false : null"
      >
        <multiselect
          id="departamento"
          name="departamento"
          ref="departamento"
          v-model="departament"
          :options="departaments"
          :allow-empty="false"
          :show-labels="false"
          :searchable="true"
          label="name"
          track-by="name"
          placeholder="Seleccione departamento"
          :class="errors.length > 0 ? 'is-danger' : ''"
          @select="getPrice"
        >
        </multiselect>
        <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
          {{ errors[0] }}
        </b-form-invalid-feedback>
      </b-form-group>
    </ValidationProvider>
    <!-- END DEPARTAMENT -->
    <!-- TYPE -->
    <ValidationProvider
      tag="div"
      rules="required"
      name="entrega"
      v-slot="{ errors }"
    >
      <b-form-group
        label="Entregar en"
        :state="errors.length > 0 ? false : null"
      >
        <multiselect
          id="entrega"
          name="entrega"
          ref="entrega"
          v-model="type"
          :options="types"
          :allow-empty="false"
          :show-labels="false"
          :searchable="true"
          label="name"
          track-by="name"
          placeholder="Seleccione tipo de entrega"
          :class="errors.length > 0 ? 'is-danger' : ''"
          @select="getPrice"
        >
        </multiselect>
        <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
          {{ errors[0] }}
        </b-form-invalid-feedback>
      </b-form-group>
    </ValidationProvider>
    <!-- END TYPE -->
    <!-- ADDRESS -->
    <ValidationProvider
      tag="div"
      rules="required"
      name="dirección"
      v-slot="{ errors }"
    >
      <b-form-group
        label="Dirección:"
        :state="errors.length > 0 ? false : null"
      >
        <b-input-group class="mt-3">
          <vue-google-autocomplete
            v-model="address"
            ref="dirección"
            id="dirección"
            name="dirección"
            classname="form-control"
            placeholder="Ingrese la dirección del cliente"
            v-on:placechanged="getAddressData"
            types="geocode"
            :class="errors.length > 0 ? 'is-invalid' : ''"
          />
          <b-input-group-append>
            <b-button
              :disabled="address !== '' ? false : true"
              variant="primary"
              v-clipboard:copy="address"
              v-clipboard:success="onCopy"
              v-clipboard:error="onError"
              >Copiar dirección</b-button
            >
          </b-input-group-append>
        </b-input-group>
        <b-form-text v-if="show">{{
          `Ciudad: ${city} , Estado: ${getStateByCode(
            state
          )}, Código Postal: ${postalCode}`
        }}</b-form-text>
        <b-form-text v-else>{{
          `Ciudad: ${city} , Estado: ${state}, Código Postal: ${postalCode}`
        }}</b-form-text>
        <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
          {{ errors[0] }}
        </b-form-invalid-feedback>
      </b-form-group>
    </ValidationProvider>
    <!-- END ADDRESS -->
    <!-- CATEGORY -->
    <ValidationProvider
      tag="div"
      rules="required"
      name="categoría"
      v-slot="{ errors }"
    >
      <b-form-group label="Categoría" :state="errors.length > 0 ? false : null">
        <!-- getShipments -->
        <multiselect
          id="categoría"
          name="categoría"
          ref="categoría"
          v-model="category"
          :options="categories"
          :show-labels="false"
          :searchable="true"
          label="name"
          track-by="name"
          placeholder="Seleccione una categoria"
          @search-change="getCategories"
          :internal-search="false"
          :class="errors.length > 0 ? 'is-danger' : ''"
          :loading="loaderCategory"
          @select="getPrice"
        >
        </multiselect>
        <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
          {{ errors[0] }}
        </b-form-invalid-feedback>
      </b-form-group>
    </ValidationProvider>
    <!-- END CATEGORY -->
    <!-- CHECK CREDIT -->
    <template>
      <b-form-group :label="typePayment === true ? 'Cancelado' : 'Por cobrar'">
        <label class="switch s-icons s-outline s-outline-primary">
          <input type="checkbox" v-model="typePayment" />
          <span class="slider round"></span>
        </label>
      </b-form-group>
    </template>
    <!-- END CHECK CREDIT -->
    <!-- CHARGE -->
    <ValidationProvider
      tag="div"
      rules="required"
      name="cuenta"
      v-slot="{ errors }"
      v-if="typePayment === false"
    >
      <b-form-group label="Cuentas:" :state="errors.length > 0 ? false : null">
        <!-- getShipments -->
        <multiselect
          id="cuenta"
          name="cuenta"
          ref="cuenta"
          v-model="accountPersonal"
          :options="accountsPersonal"
          :show-labels="false"
          :searchable="true"
          label="name"
          track-by="name"
          placeholder="Seleccione cuenta"
          @search-change="getAccounts"
          :internal-search="false"
          :class="errors.length > 0 ? 'is-danger' : ''"
          :loading="accountsPersonalLoader"
        >
        </multiselect>
        <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
          {{ errors[0] }}
        </b-form-invalid-feedback>
      </b-form-group>
    </ValidationProvider>
    <!-- END CHARGE -->
    <!-- WEIGHT -->
    <ValidationProvider rules="required" name="peso">
      <b-form-group slot-scope="{ valid, errors }" label="Peso:">
        <b-form-input
          @input="calculateTotal"
          v-money="pounds"
          v-model="weight"
          name="peso"
          type="text"
          :state="errors[0] ? false : valid ? true : null"
        ></b-form-input>
        <b-form-invalid-feedback>
          {{ errors[0] }}
        </b-form-invalid-feedback>
      </b-form-group>
    </ValidationProvider>
    <!-- END WEIGHT -->
    <!-- PRICE -->
    <ValidationProvider rules="required" name="costo">
      <b-form-group slot-scope="{ valid, errors }" label="Costo">
        <b-form-input
          @input="calculateTotal"
          @keypress="updatedMultiplier = true"
          v-money="dollar"
          v-model="cost"
          name="costo"
          type="text"
          :state="errors[0] ? false : valid ? true : null"
        ></b-form-input>
        <b-form-invalid-feedback>
          {{ errors[0] }}
        </b-form-invalid-feedback>
      </b-form-group>
    </ValidationProvider>
    <!-- END PRICE -->
    <!-- OFFICE -->
    <ValidationProvider
      tag="div"
      rules="required"
      name="oficina"
      v-slot="{ errors }"
    >
      <b-form-group label="Oficina" :state="errors.length > 0 ? false : null">
        <multiselect
          id="oficina"
          name="oficina"
          ref="oficina"
          v-model="office_guatemala"
          :options="offices"
          :allow-empty="false"
          :show-labels="false"
          :searchable="true"
          placeholder="Seleccione una oficina"
          :class="errors.length > 0 ? 'is-danger' : ''"
        >
        </multiselect>
        <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
          {{ errors[0] }}
        </b-form-invalid-feedback>
      </b-form-group>
    </ValidationProvider>
    <!-- END OFFICE -->
    <!-- TOTAL -->
    <b-form-group label="Total">
      <h4>{{ myDollar(total) }}</h4>
    </b-form-group>
    <!-- END TOTAL -->
  </div>
</template>
<script>
import Vue from "vue";
import axios from "axios";
import VeeValidate, { Validator, ValidationProvider } from "vee-validate";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import es from "vee-validate/dist/locale/es";
import { VMoney } from "v-money";
import { mapGetters } from "vuex";
import VueGoogleAutocomplete from "vue-google-autocomplete";
import VueClipboard from "vue-clipboard2";
import { State, Country } from "country-state-city";

VueClipboard.config.autoSetContainer = true; // add this line
Vue.use(VueClipboard);

Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });

import { eight } from "../../../utils/eight";

export default {
  directives: { money: VMoney },
  props: {
    typical: {
      type: Boolean,
      default: true,
    },
  },
  components: {
    ValidationProvider,
    Multiselect,
    VueGoogleAutocomplete,
  },
  data() {
    return {
      pounds: {
        decimal: ".",
        thousands: ",",
        prefix: "",
        suffix: " lbs",
        precision: 2,
        masked: true,
      },
      dollar: {
        decimal: ".",
        thousands: ",",
        prefix: "$ ",
        suffix: "",
        precision: 2,
        masked: true,
      },
      category: null,
      categories: [],
      loaderCategory: false,
      loaderDepartament: false,
      departament: null,
      departaments: [],
      type: null,
      typePayment: true,
      accountsPersonal: [],
      accountPersonal: null,
      accountsPersonalLoader: false,
      types: [
        {
          id: 1,
          name: "Oficina",
        },
        {
          id: 2,
          name: "Dirección",
        },
      ],
      weight: "",
      cost: "",
      multiplier: "",
      updatedMultiplier: true,
      total: 0,
      address: "",
      postalCode: "",
      state: "",
      country: "",
      countryIsoCode: "",
      city: "",
      msgToast: "",
      office_guatemala: null,
      offices: [],
      show: false,
    };
  },
  created() {
    this.getDepartment("");
    this.getOfficesGT();
  },
  methods: {
    ...mapGetters(["api"]),
    getStateByCode(code) {
      const country = Country.getAllCountries().find(
        (x) => x.name === this.country
      );
      this.countryIsoCode = country.isoCode;
      return State.getStateByCodeAndCountry(code, country.isoCode).name;
    },
    getOfficesGT() {
      axios
        .get(this.api() + "/setting/5")
        .then((response) => {
          this.offices = JSON.parse(response.data.value);
          this.offices.unshift("Central");
        })
        .catch((error) => {
          console.log(error);
        });
    },
    onCopy: function () {
      this.msgToast = "Dirección copiada";
      this.$bvToast.show("toast");
    },
    onError: function (e) {
      this.msgToast = e;
      this.$bvToast.show("toast-error");
    },
    getAddressData: function (addressData, placeResultData) {
      this.country = addressData.country;
      this.postalCode = addressData.postal_code;
      this.state = addressData.administrative_area_level_1;
      if (addressData.locality) this.city = addressData.locality;
      else this.city = addressData.administrative_area_level_2;
      this.address = placeResultData.formatted_address;
      this.show = true;
    },
    parseDecimalFixed(value) {
      return parseFloat(value).toFixed(2);
    },
    parseDecimal(value) {
      return parseFloat(this.parseDecimalFixed(value));
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
    getCategories(search) {
      this.loaderCategory = true;
      axios
        .get(this.api() + `/getCategory?filter=${search}`)
        .then((response) => {
          this.loaderCategory = false;
          this.categories = response.data;
        })
        .catch((error) => {
          console.log(error);
          this.loaderCategory = false;
        });
    },
    myDollar(value) {
      return eight.dollar(parseFloat(value));
    },
    getDepartment(search) {
      this.loaderDepartament = true;
      axios
        .get(this.api() + `/getDepartment?filter=${search}`)
        .then((response) => {
          this.departaments = response.data;
          this.loaderDepartament = false;
        })
        .catch((error) => {
          console.log(error);
          this.loaderDepartament = false;
        });
    },
    calculateTotal() {
      const cost = this.parseDecimal(this.converMaskToNumber(this.cost));
      const weight = this.parseDecimal(this.converMaskToNumber(this.weight));
      const multiplier = this.parseDecimal(this.multiplier);
      if (Number.isNaN(cost) && Number.isNaN(weight)) {
        this.total = 0;
      } else {
        if (weight > 1 && this.updatedMultiplier === false) {
          this.total = this.parseDecimal(
            cost + multiplier * this.parseDecimal(weight - 1)
          );
        } else if (this.updatedMultiplier === true) {
          this.total = this.parseDecimal(cost * weight);
        } else {
          this.total = this.parseDecimal(cost * weight);
        }
      }
    },
    getAccounts(search) {
      this.accountsPersonalLoader = true;
      axios
        .get(this.api() + `/getCashRegister?filter=${search}`)
        .then((response) => {
          this.accountsPersonalLoader = false;
          this.accountsPersonal = response.data.data;
        })
        .catch((error) => {
          console.log(error);
          this.accountsPersonalLoader = false;
        });
    },
    getPrice(value, type) {
      if (
        (this.category !== null || type === "categoría") &&
        (this.departament !== null || type === "departamento") &&
        (this.type || type === "departamento") !== null
      ) {
        let loader = this.$loading.show();
        const url =
          type === "departamento"
            ? `/getPrice?departament=${value.id}&category=${this.category.id}&type=${this.type.name}`
            : type === "entrega"
            ? `/getPrice?departament=${this.departament.id}&category=${this.category.id}&type=${value.name}`
            : `/getPrice?departament=${this.departament.id}&category=${value.id}&type=${this.type.name}`;
        axios
          .get(this.api() + url)
          .then((response) => {
            loader.hide();
            this.cost = response.data.price.basePrice;
            this.multiplier = response.data.price.multiplicater;
            this.updatedMultiplier = false;
            this.calculateTotal();
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
  },
};
</script>
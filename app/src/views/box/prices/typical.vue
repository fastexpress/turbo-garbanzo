<template>
  <div v-if="typical === true">
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
    <!-- CUSTOMER -->
    <ValidationProvider
      tag="div"
      rules="required"
      name="típico"
      v-slot="{ errors }"
    >
      <b-form-group label="Tipico" :state="errors.length > 0 ? false : null">
        <!-- getShipments -->
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
          :multiple="true"
          @close="getPriceBefore"
        >
        </multiselect>
        <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
          {{ errors[0] }}
        </b-form-invalid-feedback>
      </b-form-group>
    </ValidationProvider>
    <!-- END CUSTOMER -->
    <!-- MULTIPLE -->
    <div v-for="c in customer" :key="c.id">
      <h4>{{ c.name }}</h4>
      <hr />
      <!-- CHECK CREDIT -->
      <template v-if="customer !== null">
        <b-form-group
          v-if="c.credit === 1"
          :label="
            c.checkCredit === true ? 'Cobrar al típico' : 'Cobrar al cliente'
          "
        >
          <label class="switch s-icons s-outline s-outline-primary">
            <input
              type="checkbox"
              v-model="c.checkCredit"
              @change="c.accountPersonal = null"
            />
            <span class="slider round"></span>
          </label>
        </b-form-group>
      </template>
      <!-- END CHECK CREDIT -->
      <!-- CHARGE -->
      <ValidationProvider
        tag="div"
        rules="required"
        name="cuentas personales"
        v-slot="{ errors }"
        v-if="c.checkCredit === false"
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
            v-model="c.accountPersonal"
            :options="accountsPersonal"
            :show-labels="false"
            :searchable="true"
            label="name"
            track-by="name"
            :custom-label="customLabelAccontPersonal"
            placeholder="Seleccione las cuentas"
            :internal-search="false"
            :class="errors.length > 0 ? 'is-danger' : ''"
            :loading="c.accountsPersonalLoader"
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
            @input="calculateTotalPayment"
            @blur="getPriceBefore"
            v-money="pounds"
            v-model="c.weight"
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
            @input="calculateTotalPayment"
            @keypress="c.updatedMultiplier = true"
            v-money="dollar"
            v-model="c.cost"
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
      <!-- SUBTOTAL -->
      <b-form-group :label="`Subtotal ${c.name}`">
        <h4>{{ myDollar(c.subtotal) }}</h4>
      </b-form-group>
      <hr />
      <!-- END SUBTOTAL -->
      <!-- MULTIPLE -->
    </div>
    <!-- TOTAL -->
    <b-form-group label="Total">
      <h3>{{ myDollar(totalPayment) }}</h3>
    </b-form-group>
    <!-- END TOTAL -->
    <b-toast
      id="toast"
      header-class="d-none"
      body-class="toast-success d-flex justify-content-between"
      toaster="b-toaster-bottom-right"
    >
      {{ msgToast }}
      <a
        href="javascript:;"
        class="text-white ml-2"
        @click="$bvToast.hide('toast')"
        >OK</a
      >
    </b-toast>
    <b-toast
      id="toast-error"
      header-class="d-none"
      body-class="toast-danger d-flex justify-content-between"
      toaster="b-toaster-bottom-right"
    >
      {{ msgToast }}
      <a
        href="javascript:;"
        class="text-white ml-2"
        @click="$bvToast.hide('toast')"
        >Error</a
      >
    </b-toast>
  </div>
</template>
<script>
import axios from "axios";
import Vue from "vue";
import VueClipboard from "vue-clipboard2";
import { mapGetters } from "vuex";
import { VMoney } from "v-money";
import VeeValidate, { Validator, ValidationProvider } from "vee-validate";
import es from "vee-validate/dist/locale/es";
import { eight } from "../../../utils/eight";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import VueGoogleAutocomplete from "vue-google-autocomplete";
import { State, Country } from "country-state-city";

VueClipboard.config.autoSetContainer = true; // add this line
Vue.use(VueClipboard);

Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });
export default {
  directives: { money: VMoney },
  components: {
    ValidationProvider,
    Multiselect,
    VueGoogleAutocomplete,
  },
  props: {
    typical: {
      type: Boolean,
      default: true,
    },
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
      loaderCustomer: false,
      customer: [],
      customers: [],
      accountsPersonal: [],
      address: "",
      postalCode: "",
      state: "",
      city: "",
      country: "",
      countryIsoCode: "",
      msgToast: "",
      totalPayment: 0,
      show: false,
    };
  },
  mounted() {
    this.getSettingNine();
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
    myDollar(value) {
      return eight.dollar(parseFloat(value));
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
    onCopy: function () {
      this.msgToast = "Dirección copiada";
      this.$bvToast.show("toast");
    },
    onError: function (e) {
      this.msgToast = e;
      this.$bvToast.show("toast-error");
    },
    converMaskToNumber(value) {
      var myNumber = "";
      for (var i = 0; i < value.length; i += 1) {
        if (
          value.charAt(i) === "k" ||
          value.charAt(i) === "g" ||
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
    myMoney(value) {
      return eight.money(parseFloat(value));
    },
    parseDecimalFixed(value) {
      return parseFloat(value).toFixed(2);
    },
    parseDecimal(value) {
      return parseFloat(this.parseDecimalFixed(value));
    },
    getPriceBefore() {
      this.customer.forEach((c, i) => {
        if (c.type === 1) {
          this.customer[i].cost = c.basePrice;
          this.customer[i].multiplicater = c.multiplicater;
        } else {
          const prices = JSON.parse(c.prices);
          const weight = this.converMaskToNumber(c.weight);
          if (weight > 0) {
            if (weight >= 100) {
              this.customer[i].cost = prices[prices.length - 1].price;
            } else {
              prices.forEach((price) => {
                if (weight >= price.min && weight <= price.max) {
                  this.customer[i].cost = price.price;
                  return;
                }
              });
            }
          } else {
            this.customer[i].cost = 0;
          }
          this.customer[i].updatedMultiplier = true;
        }
      });
    },
    calculateTotalPayment() {
      let total = 0;
      this.customer.forEach((c, i) => {
        if (c.type === 1) {
          const cost = this.parseDecimal(this.converMaskToNumber(c.cost));
          const weight = this.parseDecimal(c.weight);
          const multiplicater = this.parseDecimal(c.multiplicater);
          if (Number.isNaN(cost) && Number.isNaN(weight)) {
            this.customer[i].subtotal = 0;
            total = this.parseDecimal(total) + this.parseDecimal(0);
          } else {
            if (weight > 1 && c.updatedMultiplier === false) {
              total =
                this.parseDecimal(total) +
                this.parseDecimal(
                  cost + multiplicater * this.parseDecimal(weight - 1)
                );
              this.customer[i].subtotal = this.parseDecimal(
                cost + multiplicater * this.parseDecimal(weight - 1)
              );
            } else if (c.updatedMultiplier === true) {
              this.customer[i].subtotal = this.parseDecimal(cost * weight);
              total =
                this.parseDecimal(total) + this.parseDecimal(cost * weight);
            } else {
              this.customer[i].subtotal = this.parseDecimal(cost * weight);
              total =
                this.parseDecimal(total) + this.parseDecimal(cost * weight);
            }
          }
        } else {
          const cost = this.parseDecimal(this.converMaskToNumber(c.cost));
          const weight = this.parseDecimal(c.weight);
          if (Number.isNaN(cost) && Number.isNaN(weight)) {
            total = this.parseDecimal(total) + 0;
            this.customer[i].subtotal = 0;
          } else {
            this.customer[i].subtotal = this.parseDecimal(cost * weight);
            total = this.parseDecimal(total) + this.parseDecimal(cost * weight);
          }
        }
      });
      this.totalPayment = total;
    },
    getSettingNine() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + "/setting/9")
        .then((response) => {
          const { value } = response.data;
          const accounts = JSON.parse(value.replaceAll(/\\/g, ""));
          if (accounts.length > 0) {
            accounts.forEach((account) => {
              axios
                .get(this.api() + `/accountspersonal/${account.id}`)
                .then((response) => {
                  loader.hide();
                  this.accountsPersonal.push({
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
            loader.hide();
            this.accountsPersonal = [];
          }
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    getCustomer(search) {
      this.loaderCustomer = true;
      axios
        .get(this.api() + `/getCustomer?filter=${search}`)
        .then((response) => {
          const { data } = response.data;
          const customers = [];
          data.forEach((c) => {
            customers.push({
              ...c,
              updatedMultiplier: false,
              weight: "",
              cost: "",
              accountPersonal: null,
              checkCredit: false,
              subtotal: 0,
              accountsPersonalLoader: false,
            });
          });
          this.loaderCustomer = false;
          this.customers = customers;
        })
        .catch((error) => {
          console.log(error);
          this.loaderCustomer = false;
        });
    },
    customLabelAccontPersonal({ name, amountBank, bank }) {
      if (bank === null) return `${name} - ${this.myMoney(amountBank)}`;
      else return `${name} - ${this.myMoney(amountBank)} - ${bank}`;
    },
  },
};
</script>
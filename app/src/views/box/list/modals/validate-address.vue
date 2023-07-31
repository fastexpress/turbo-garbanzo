<template>
  <b-modal
    ref="modal_update_address"
    title="Validar dirección"
    title-tag="h4"
    size="xl"
    footer-class="justify-content-center"
    centered
  >
    <ValidationObserver ref="observer-address">
      <b-form>
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
            <b-form-text v-if="show === false">{{
              `Ciudad: ${city} , Estado: ${state}, Código Postal: ${postalCode}`
            }}</b-form-text>
            <b-form-text v-else>{{
              `Ciudad: ${city} , Estado: ${getStateByCode(
                state
              )}, Código Postal: ${postalCode}`
            }}</b-form-text>
            <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END ADDRESS -->
      </b-form>
    </ValidationObserver>
    <template #modal-footer>
      <b-button variant="danger" @click="clodeModalAddress">Cancelar</b-button>
      <b-button variant="primary" @click="saveAddress">Guardar</b-button>
    </template>
  </b-modal>
</template>
<script>
import Vue from "vue";
import axios from "axios";
import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import { mapGetters } from "vuex";
import es from "vee-validate/dist/locale/es";
import VueGoogleAutocomplete from "vue-google-autocomplete";
import VueClipboard from "vue-clipboard2";
import { State, Country } from "country-state-city";

VueClipboard.config.autoSetContainer = true; // add this line
Vue.use(VueClipboard);
Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });
export default {
  components: {
    ValidationObserver,
    ValidationProvider,
    VueGoogleAutocomplete,
  },
  data() {
    return {
      id: 0,
      address: "",
      postalCode: "",
      state: "",
      city: "",
      country: "",
      show: false,
    };
  },
  methods: {
    ...mapGetters(["api"]),
    getStateByCode(code) {
      const country = Country.getAllCountries().find(
        (x) => x.name === this.country
      );
      return State.getStateByCodeAndCountry(code, country.isoCode).name;
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
    openModal(upsPackage) {
      this.id = upsPackage.id;
      this.address = upsPackage.address;
      this.postalCode = upsPackage.postalCode;
      this.state = upsPackage.state;
      this.city = upsPackage.city;
      this.show = false;
      this.$refs["modal_update_address"].show();
    },
    saveAddress() {
      this.$refs["observer-address"].validate().then((isValid) => {
        if (isValid) {
          let loader = this.$loading.show();
          axios
            .post(this.api() + "/package-ups-validate-address", {
              id: this.id,
              address: this.address,
              postalCode: this.postalCode,
              state: this.state,
              city: this.city,
            })
            .then((response) => {
              loader.hide();
              this.$swal({ text: `${response.data.message}`, icon: "success" });
              this.$emit("save");
              this.clodeModalAddress();
            })
            .catch((error) => {
              this.$swal({
                text: `${error.response.data.message}`,
                icon: "error",
              });
              loader.hide();
            });
        }
      });
    },
    clodeModalAddress() {
      this.address = "";
      this.postalCode = "";
      this.state = "";
      this.city = "";
      this.$refs["modal_update_address"].hide();
    },
  },
};
</script>
<style>
.pac-container {
  z-index: 11000; /* needs to be > z-index modal */
}
</style>
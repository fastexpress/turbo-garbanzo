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
                  <h4>Ingreso/Retiro de una cuenta</h4>
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
                        <!-- ACCOUNT -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="cuenta"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Cuenta"
                            :state="errors.length > 0 ? false : null"
                          >
                            <!-- getShipments -->
                            <multiselect
                              id="cuenta"
                              name="cuenta"
                              ref="cuenta"
                              v-model="account"
                              :options="accounts"
                              :show-labels="false"
                              :searchable="true"
                              label="name"
                              track-by="name"
                              placeholder="Seleccione cuenta"
                              @search-change="getAccounts"
                              :internal-search="false"
                              :class="errors.length > 0 ? 'is-danger' : ''"
                              :loading="loaderAccount"
                              @input="getCurrency"
                              :custom-label="customLabelAccontPersonal"
                            >
                            </multiselect>
                            <b-form-invalid-feedback
                              :state="errors.length > 0 ? false : null"
                            >
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END ACCOUNT-->
                        <!-- TYPE -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="tipo"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Tipo"
                            :state="errors.length > 0 ? false : null"
                          >
                            <!-- getShipments -->
                            <multiselect
                              id="tipo"
                              name="tipo"
                              ref="tipo"
                              v-model="type"
                              :options="types"
                              label="name"
                              track-by="name"
                              placeholder="Seleccione tipo de movimiento"
                              :class="errors.length > 0 ? 'is-danger' : ''"
                            >
                            </multiselect>
                            <b-form-invalid-feedback
                              :state="errors.length > 0 ? false : null"
                            >
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END TYPE-->
                        <!-- AMOUNT -->
                        <ValidationProvider rules="required" name="monto">
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Monto"
                          >
                            <b-form-input
                              v-money="whatIsMask()"
                              v-model="amount"
                              name="monto"
                              type="text"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END AMOUNT -->
                        <!-- REFERENCE -->
                        <ValidationProvider rules="required" name="referencía">
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Referencía"
                          >
                            <b-form-textarea
                              name="referencía"
                              v-model="reference"
                              type="text"
                              rows="3"
                              max-rows="6"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-textarea>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END REFERENCE -->
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
  </div>
</template>
<script>
import Vue from "vue";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import axios from "axios";
import { mapGetters } from "vuex";
import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import es from "vee-validate/dist/locale/es";
import { VMoney } from "v-money";

Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });
export default {
  metaInfo: { title: "Ingreso/Retiro de una cuenta" },
  directives: { money: VMoney },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Ingreso/Retiro de una cuenta", active: true },
      ],
      dollar: {
        decimal: ".",
        thousands: ",",
        prefix: "$ ",
        suffix: "",
        precision: 2,
        masked: true,
      },
      quetzal: {
        decimal: ".",
        thousands: ",",
        prefix: "Q ",
        suffix: "",
        precision: 2,
        masked: true,
      },

      accounts: [],
      account: null,
      loaderAccount: false,

      type: { id: 1, name: "Ingreso", sign: "+" },
      types: [
        { id: 1, name: "Ingreso", sign: "+" },
        { id: 2, name: "Retiro", sign: "-" },
      ],

      sign: "Q",
      amount: "",
      reference: "",
    };
  },
  components: {
    ValidationObserver,
    ValidationProvider,
    Multiselect,
  },
  methods: {
    ...mapGetters(["api", "userID"]),
    converMaskToNumber(value) {
      var myNumber = "";
      for (var i = 0; i < value.length; i += 1) {
        if (
          value.charAt(i) === "Q" ||
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
    getAccounts(search) {
      this.loaderAccount = true;
      axios
        .get(this.api() + `/getAllAccount?filter=${search}`)
        .then((response) => {
          this.loaderAccount = false;
          this.accounts = response.data.data;
        })
        .catch((error) => {
          console.log(error);
          this.loaderAccount = false;
        });
    },
    handleSubmit() {
      let loader = this.$loading.show();
      axios
        .post(this.api() + `/transaction/account`, {
          name: this.account.name,
          type: this.type.sign,
          id: this.account.id,
          user: this.userID(),
          reference: this.reference,
          amount: this.converMaskToNumber(this.amount),
        })
        .then((response) => {
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          loader.hide();
          this.$router.push({
            path: `/transaccion/lista`,
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
    getCurrency() {
      if (this.account !== null) {
        this.sign = this.account.sign;
      }
    },
    whatIsMask() {
      return this.sign === "Q" ? this.quetzal : this.dollar;
    },
    customLabelAccontPersonal({ name, bank }) {
      if (bank === null || bank === "") return `${name}`;
      else return `${name} - ${bank}`;
    },
  },
};
</script>

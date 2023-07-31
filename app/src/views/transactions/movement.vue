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
                  <h4>Movimiento entre cuentas</h4>
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
                        <!-- ACCOUNT REMOVE -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="cuenta"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Cuenta de retiro"
                            :state="errors.length > 0 ? false : null"
                          >
                            <!-- getShipments -->
                            <multiselect
                              id="cuenta"
                              name="cuenta"
                              ref="cuenta"
                              v-model="accountRemove"
                              :options="accountsRemove"
                              :show-labels="false"
                              :searchable="true"
                              label="name"
                              track-by="name"
                              placeholder="Seleccione cuenta de salida"
                              @search-change="getAccountsRemove"
                              :internal-search="false"
                              :class="errors.length > 0 ? 'is-danger' : ''"
                              :loading="loaderAccountRemove"
                              @input="verifyExchange"
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
                        <!-- END ACCOUNT REMOVE -->
                        <!-- ACCOUNT REMOVE -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="cuenta"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Cuenta de ingreso"
                            :state="errors.length > 0 ? false : null"
                          >
                            <!-- getShipments -->
                            <multiselect
                              id="cuenta"
                              name="cuenta"
                              ref="cuenta"
                              v-model="accountInsert"
                              :options="accountsInsert"
                              :show-labels="false"
                              :searchable="true"
                              label="name"
                              track-by="name"
                              placeholder="Seleccione cuenta de ingreso"
                              @search-change="getAccountsInsert"
                              :internal-search="false"
                              :class="errors.length > 0 ? 'is-danger' : ''"
                              :loading="loaderAccountInsert"
                              @input="verifyExchange"
                            >
                            </multiselect>
                            <b-form-invalid-feedback
                              :state="errors.length > 0 ? false : null"
                            >
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END ACCOUNT REMOVE -->
                        <!-- EXCHANGE -->
                        <ValidationProvider
                          rules="required"
                          name="cambio"
                          v-if="!sameSign"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Cambio"
                          >
                            <b-form-input
                              v-money="whatIsMaskedExChange()"
                              v-model="exchangeRate"
                              name="cambio"
                              type="text"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END EXCHANGE -->
                        <!-- AMOUNT -->
                        <ValidationProvider rules="required" name="monto">
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Monto"
                          >
                            <b-form-input
                              v-money="whatIsMasked()"
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
import { VMoney } from "v-money";
import { mapGetters } from "vuex";
import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import es from "vee-validate/dist/locale/es";

Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });
export default {
  metaInfo: { title: "Movimiento entre cuentas" },
  directives: { money: VMoney },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Movimiento entre cuentas", active: true },
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
      accountsRemove: [],
      accountRemove: null,
      loaderAccountRemove: false,

      accountsInsert: [],
      accountInsert: null,
      loaderAccountInsert: false,

      amount: "",
      exchangeRate: "",
      sameSign: true,

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
    parseDecimalFixed(value) {
      return parseFloat(value).toFixed(2);
    },
    parseDecimal(value) {
      return parseFloat(this.parseDecimalFixed(value));
    },
    whatIsMasked() {
      if (this.sameSign === true) {
        if (this.accountRemove !== null && this.accountInsert !== null) {
          if (this.accountRemove.sign === "Q") return this.quetzal;
          else return this.dollar;
        } else {
          return this.quetzal;
        }
      } else {
        if (this.accountRemove !== null && this.accountInsert !== null) {
          if (this.accountRemove.sign === "Q") return this.quetzal;
          else return this.dollar;
        } else {
          return this.quetzal;
        }
      }
    },
    whatIsMaskedExChange() {
      if (this.sameSign === true) {
        if (this.accountRemove !== null && this.accountInsert !== null) {
          if (this.accountRemove.sign === "Q") return this.dollar;
          else return this.quetzal;
        } else {
          return this.dollar;
        }
      } else {
        if (this.accountRemove !== null && this.accountInsert !== null) {
          if (this.accountRemove.sign === "Q") return this.dollar;
          else return this.quetzal;
        } else {
          return this.dollar;
        }
      }
    },
    getAccountsRemove(search) {
      this.loaderAccountRemove = true;
      axios
        .get(this.api() + `/getAllAccount?filter=${search}`)
        .then((response) => {
          this.loaderAccountRemove = false;
          this.accountsRemove = response.data.data;
        })
        .catch((error) => {
          console.log(error);
          this.loaderAccountRemove = false;
        });
    },
    getAccountsInsert(search) {
      this.loaderAccountInsert = true;
      axios
        .get(this.api() + `/getAllAccount?filter=${search}`)
        .then((response) => {
          this.loaderAccountInsert = false;
          this.accountsInsert = response.data.data;
        })
        .catch((error) => {
          console.log(error);
          this.loaderAccountInsert = false;
        });
    },
    verifyExchange() {
      if (this.accountRemove !== null && this.accountInsert !== null) {
        if (this.accountRemove.sign === this.accountInsert.sign) {
          this.sameSign = true;
        } else {
          this.sameSign = false;
        }
      } else {
        this.sameSign = false;
      }
    },
    handleSubmit() {
      if (this.accountRemove.id === this.accountInsert.id) {
        this.$swal({
          text: "Error no se puede hacer transacciónes de la misma cuenta",
          icon: "error",
        });
        return;
      }
      let loader = this.$loading.show();
      axios
        .post(this.api() + "/transaction/movement", {
          amountIn: this.getAmount(),
          amountOut: this.converMaskToNumber(this.amount),
          nameOut: this.accountRemove.name,
          nameIn: this.accountInsert.name,
          idOut: this.accountRemove.id,
          idIn: this.accountInsert.id,
          user: this.userID(),
          reference: this.reference,
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
    getAmount() {
      if (this.sameSign === true) {
        return this.converMaskToNumber(this.amount);
      } else {
        return this.parseDecimal(
          this.parseDecimal(this.converMaskToNumber(this.amount)) *
            this.parseDecimal(this.converMaskToNumber(this.exchangeRate))
        );
      }
    },
    customLabelAccontPersonal({ name, bank }) {
      if (bank === null || bank === "") return `${name}`;
      else return `${name} - ${bank}`;
    },
  },
};
</script>

<template>
  <b-modal
    ref="modal_payment_shipment"
    title="Pago del envío"
    title-tag="h4"
    size="xl"
    footer-class="justify-content-center"
    centered
  >
    <ValidationObserver ref="observer-payment">
      <b-form>
        <div v-for="c in accounts" :key="c.id">
          <h4>{{ showStringCustomer(c.customer) }}</h4>
          <!-- TYPE -->
          <b-form-group :label="c.type === true ? 'DEPÓSITO' : 'REMESA'">
            <label class="switch s-icons s-outline s-outline-primary">
              <input type="checkbox" v-model="c.type" :autofocus="true" />
              <span class="slider round"></span>
            </label>
          </b-form-group>
          <!-- END TYPE -->
          <!-- RECEIVE -->
          <ValidationProvider
            rules="required"
            name="número de clave"
            v-if="c.type === false"
          >
            <b-form-group
              slot-scope="{ valid, errors }"
              label="Número de clave:"
            >
              <b-form-input
                :autofocus="true"
                v-model="c.keyNumber"
                name="número de clave"
                type="text"
                :state="errors[0] ? false : valid ? true : null"
              ></b-form-input>
              <b-form-invalid-feedback>
                {{ errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </ValidationProvider>
          <!-- END RECEIVE -->
          <!-- RECEIVE -->
          <ValidationProvider
            rules="required"
            name="envía"
            v-if="c.type === false"
          >
            <b-form-group slot-scope="{ valid, errors }" label="Envía:">
              <b-form-input
                :autofocus="true"
                v-model="c.nameSend"
                name="envía"
                type="text"
                :state="errors[0] ? false : valid ? true : null"
              ></b-form-input>
              <b-form-invalid-feedback>
                {{ errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </ValidationProvider>
          <!-- END RECEIVE -->
          <!-- BANK -->
          <ValidationProvider
            rules="required"
            name="banco"
            v-if="c.type === false"
          >
            <b-form-group slot-scope="{ valid, errors }" label="Banco:">
              <b-form-input
                :autofocus="true"
                v-model="c.bank"
                name="banco"
                type="text"
                :state="errors[0] ? false : valid ? true : null"
              ></b-form-input>
              <b-form-invalid-feedback>
                {{ errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </ValidationProvider>
          <!-- END BANK -->
          <!-- EXCHANGE -->
          <ValidationProvider rules="required" name="cambio">
            <b-form-group slot-scope="{ valid, errors }" label="Cambio">
              <b-form-input
                :autofocus="true"
                v-money="quetzal"
                v-model="c.exchangeRate"
                name="cambio"
                type="text"
                :state="errors[0] ? false : valid ? true : null"
              ></b-form-input>
              <b-form-invalid-feedback>
                {{ errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </ValidationProvider>
          <ValidationProvider
            rules="required"
            name="monto"
            v-for="a in c.customer"
            :key="a.id"
          >
            <b-form-group
              slot-scope="{ valid, errors }"
              :label="`Monto ${a.name}`"
            >
              <!-- END EXCHANGE -->
              <h5>Total a pagar: {{ myDollar(a.balance) }}</h5>
              <!-- AMOUNT -->
              <b-form-input
                :autofocus="true"
                v-money="dollar"
                v-model="a.amountDollar"
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
          <hr />
        </div>
      </b-form>
    </ValidationObserver>
    <template #modal-footer>
      <b-button variant="danger" @click="closeModalPayment">Cancelar</b-button>
      <b-button variant="primary" @click="savePayment">Guardar</b-button>
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
import { VMoney } from "v-money";
import { eight } from "../../../../utils/eight";
Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });
export default {
  directives: { money: VMoney },

  components: {
    ValidationObserver,
    ValidationProvider,
  },
  data() {
    return {
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
      id: 0,
      accounts: [],
    };
  },
  methods: {
    ...mapGetters(["api", "userID"]),

    myDollar(value) {
      return eight.dollar(parseFloat(value));
    },
    openModal(upsPackage) {
      this.id = upsPackage.id;
      this.accounts = [];
      upsPackage.account.forEach((a) => {
        const found = this.accounts.findIndex((x) => x.id === a.id);
        if (found === -1)
          this.accounts.push({
            ...a,
            customer: upsPackage.customer.filter(
              (b) => b.idAccountPersonal === a.id && b.balance > 0
            ),
            maxAmount: upsPackage.customer
              .filter((b) => b.idAccountPersonal === a.id)
              .reduce((total, item) => {
                return (total =
                  this.parseDecimal(total) + this.parseDecimal(item.subtotal));
              }, 0),
            exchangeRate: "",
            amountDollar: 0,
            amount: 0,
            keyNumber: "",
            bank: "",
            nameSend: "",
            type: true,
          });
      });
      this.accounts = this.accounts.filter((x) => x.customer.length > 0);
      this.$refs["modal_payment_shipment"].show();
    },
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
    savePayment() {
      this.$refs["observer-payment"].validate().then((isValid) => {
        if (isValid) {
          let found = false;
          let exchange = false;
          this.accounts.forEach((a, i) => {
            this.accounts[i].amountDollar = 0;
            this.accounts[i].amount = 0;
            this.accounts[i].exchangeRate = this.converMaskToNumber(
              this.accounts[i].exchangeRate
            );
            if (
              parseInt(this.converMaskToNumber(a.exchangeRate)) === 0 ||
              parseInt(this.converMaskToNumber(a.exchangeRate)) > 10
            ) {
              exchange = true;
            }
            a.customer.forEach((c, j) => {
              if (this.converMaskToNumber(c.amountDollar) > c.balance) {
                found = true;
              }

              this.accounts[i].amountDollar =
                this.parseDecimal(this.accounts[i].amountDollar) +
                this.parseDecimal(this.converMaskToNumber(c.amountDollar));
              this.accounts[i].amount =
                this.parseDecimal(this.accounts[i].amount) +
                this.parseDecimal(
                  this.parseDecimal(this.converMaskToNumber(c.amountDollar)) *
                    this.parseDecimal(this.converMaskToNumber(a.exchangeRate))
                );
              this.accounts[i].customer[j].amountDollar =
                this.converMaskToNumber(
                  this.accounts[i].customer[j].amountDollar
                );
            });
          });
          if (found === true) {
            this.$swal({
              text: "La monto no puede ser más que el costo de envío del paquete",
              icon: "error",
            });
            return;
          }
          if (exchange === true) {
            this.$swal({
              text: "Revise el tipo de cambio",
              icon: "error",
            });
            return;
          }
          let loader = this.$loading.show();
          axios
            .post(this.api() + "/package-ups-payment", {
              id: this.id,
              accounts: JSON.stringify(this.accounts),
              user: this.userID(),
            })
            .then((response) => {
              this.$emit("save");
              loader.hide();
              this.$swal({ text: `${response.data.message}`, icon: "success" });
              this.closeModalPayment();
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
    showStringCustomer(customers) {
      let name = "";
      customers.forEach((c) => {
        name += c.name + ", ";
      });
      return name.substr(0, name.length - 2);
    },
    closeModalPayment() {
      this.id = 0;
      this.idAccount = 0;
      this.exchangeRate = "";
      this.amountDollar = "";
      this.amount = "";
      this.maxAmount = 0;
      this.keyNumber = "";
      this.nameSend = "";
      this.type = true;
      this.$refs["modal_payment_shipment"].hide();
    },
  },
};
</script>
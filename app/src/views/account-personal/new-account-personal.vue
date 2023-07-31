<template>
  <div class="layout-px-spacing">
    <portal to="breadcrumb">
      <div class="page-header">
        <nav class="breadcrumb-one" aria-label="breadcrumb">
          <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
        </nav>
      </div>
    </portal>
    <!-- typeAccount -->
    <div class="container">
      <div class="row layout-top-spacing">
        <div id="basic" class="col-lg-12 layout-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Nueva cuenta</h4>
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
                        <!-- NAME -->
                        <ValidationProvider rules="required" name="nombre">
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Nombre de la cuenta:"
                          >
                            <b-form-input
                              v-model="name"
                              name="nombre"
                              type="text"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END NAME -->
                        <!-- BANK -->
                        <ValidationProvider rules="required" name="banco">
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Banco:"
                          >
                            <b-form-input
                              v-model="bank"
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
                        <!-- TYPE ACCOUNT -->
                        <ValidationProvider rules="required" name="tipo">
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Tipo de cuenta:"
                          >
                            <b-form-input
                              v-model="typeAccount"
                              name="tipo"
                              type="text"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END TYPE ACCOUNT -->
                        <!-- NUMBER ACCOUNT -->
                        <ValidationProvider
                          rules="required"
                          name="número de cuenta"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="No. de cuenta:"
                          >
                            <b-form-input
                              v-model="numberAccount"
                              name="número de cuenta"
                              type="text"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END NUMBER ACCOUNT -->
                        <!-- AMOUNT -->
                        <ValidationProvider rules="required" name="monto">
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Monto"
                          >
                            <b-form-input
                              v-money="quetzal"
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
                        <!-- OBSERVATION -->
                        <b-form-group label="Descripción:">
                          <b-form-textarea
                            v-model="description"
                            type="text"
                            rows="3"
                            max-rows="6"
                          ></b-form-textarea>
                        </b-form-group>
                        <!-- END OBSERVATION -->
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
  metaInfo: { title: "Nueva cuenta" },
  directives: { money: VMoney },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Nueva cuenta", active: true },
      ],
      quetzal: {
        decimal: ".",
        thousands: ",",
        prefix: "Q ",
        suffix: "",
        precision: 2,
        masked: true,
      },
      numberAccount: "",
      name: "",
      bank: "",
      amount: "",
      description: "",
      typeAccount: "",
    };
  },
  components: {
    ValidationObserver,
    ValidationProvider,
  },
  methods: {
    ...mapGetters(["api", "userID"]),
    converMaskToNumber(value) {
      var myNumber = "";
      for (var i = 0; i < value.length; i += 1) {
        if (
          value.charAt(i) === "Q" ||
          value.charAt(i) === "," ||
          value.charAt(i) === " "
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
      axios
        .post(this.api() + "/accountspersonal", {
          name: this.name,
          numberAccount: this.numberAccount,
          bank: this.bank,
          amount: this.converMaskToNumber(this.amount),
          user: this.userID(),
          description: this.description,
          typeAccount: this.typeAccount,
        })
        .then((response) => {
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          loader.hide();
          this.$router.push({
            path: `/cuentas-personales/lista`,
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
  },
};
</script>
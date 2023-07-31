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
                  <h4>Nuevo Cliente</h4>
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
                            label="Nombre del cliente o la empresa:"
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
                        <!-- CHECK TELEPHONE -->
                        <b-form-group
                          :label="
                            checkedTelephone === true
                              ? 'Número GT'
                              : 'Número USA'
                          "
                        >
                          <label
                            class="switch s-icons s-outline s-outline-primary"
                          >
                            <input type="checkbox" v-model="checkedTelephone" />
                            <span class="slider round"></span>
                          </label>
                        </b-form-group>
                        <!-- END CHECK TELEPHONE -->
                        <!-- TELEFONO -->
                        <ValidationProvider
                          :rules="
                            checkedTelephone === true
                              ? 'required|length:9'
                              : 'required|length:12'
                          "
                          name="teléfono"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Teléfono del cliente o la empresa:"
                          >
                            <b-form-input
                              v-model="telephone"
                              name="teléfono"
                              type="text"
                              v-mask="whatMasked(checkedTelephone)"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END TELEFONO -->
                        <!-- CHECK TELEPHONE ALTERNATIVE -->
                        <b-form-group
                          :label="
                            checkedAlternativeTelephone === true
                              ? 'Número GT'
                              : 'Número USA'
                          "
                        >
                          <label
                            class="switch s-icons s-outline s-outline-primary"
                          >
                            <input
                              type="checkbox"
                              v-model="checkedAlternativeTelephone"
                            />
                            <span class="slider round"></span>
                          </label>
                        </b-form-group>
                        <!-- END CHECK TELEPHONE ALTERNATIVE -->
                        <!-- TELEFONO -->
                        <ValidationProvider
                          :rules="
                            checkedAlternativeTelephone === true
                              ? 'length:9'
                              : 'length:12'
                          "
                          name="teléfono"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Teléfono del cliente o la empresa (Alternativo):"
                          >
                            <b-form-input
                              v-model="alternativeTelephone"
                              name="teléfono"
                              type="text"
                              v-mask="whatMasked(checkedAlternativeTelephone)"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END TELEFONO ALTERNATIVE -->
                        <!-- REPRESENT -->
                        <!-- NAME -->
                        <b-form-group label="Nombre del representante:">
                          <b-form-input
                            v-model="nameCharge"
                            name="nombre del representante"
                            type="text"
                          ></b-form-input>
                        </b-form-group>
                        <!-- END NAME -->
                        <!-- CHECK TELEPHONE -->
                        <b-form-group
                          :label="
                            checkedTelephoneCharge === true
                              ? 'Número GT'
                              : 'Número USA'
                          "
                        >
                          <label
                            class="switch s-icons s-outline s-outline-primary"
                          >
                            <input
                              type="checkbox"
                              v-model="checkedTelephoneCharge"
                            />
                            <span class="slider round"></span>
                          </label>
                        </b-form-group>
                        <!-- END CHECK TELEPHONE -->
                        <!-- TELEFONO -->
                        <ValidationProvider
                          :rules="
                            nameCharge !== ''
                              ? checkedTelephoneCharge === true
                                ? 'required|length:9'
                                : 'required|length:12'
                              : ''
                          "
                          name="teléfono"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Teléfono del representante:"
                          >
                            <b-form-input
                              v-model="telephoneCharge"
                              name="teléfono"
                              type="text"
                              v-mask="whatMasked(checkedTelephoneCharge)"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END TELEFONO -->
                        <!-- CHECK TELEPHONE ALTERNATIVE -->
                        <b-form-group
                          :label="
                            checkedAlternativeTelephoneCharge === true
                              ? 'Número GT'
                              : 'Número USA'
                          "
                        >
                          <label
                            class="switch s-icons s-outline s-outline-primary"
                          >
                            <input
                              type="checkbox"
                              v-model="checkedAlternativeTelephoneCharge"
                            />
                            <span class="slider round"></span>
                          </label>
                        </b-form-group>
                        <!-- END CHECK TELEPHONE ALTERNATIVE -->
                        <!-- TELEFONO -->
                        <ValidationProvider
                          :rules="
                            checkedAlternativeTelephoneCharge === true
                              ? 'length:9'
                              : 'length:12'
                          "
                          name="teléfono"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Teléfono del representante (Alternativo):"
                          >
                            <b-form-input
                              v-model="alternativeTelephoneCharge"
                              name="teléfono"
                              type="text"
                              v-mask="
                                whatMasked(checkedAlternativeTelephoneCharge)
                              "
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END TELEFONO ALTERNATIVE -->
                        <!-- END REPRESENT -->
                        <!-- EXCHANGE RATE -->
                        <ValidationProvider
                          rules="required"
                          name="tipo de cambio"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Tipo de cambio"
                          >
                            <b-form-input
                              v-money="quetzal"
                              v-model="exchangeRate"
                              name="tipo de cambio"
                              type="text"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END EXCHANGE RATE -->
                        <!-- PRICES -->
                        <!-- CHECK TELEPHONE -->
                        <b-form-group
                          :label="
                            type === true ? 'Precio fijo' : 'Precio por libras'
                          "
                        >
                          <label
                            class="switch s-icons s-outline s-outline-primary"
                          >
                            <input type="checkbox" v-model="type" />
                            <span class="slider round"></span>
                          </label>
                        </b-form-group>
                        <!-- END CHECK TELEPHONE -->
                        <!-- END PRICES -->
                        <!-- CHECK CREDIT -->
                        <b-form-group
                          :label="
                            credit === true
                              ? 'Opción de crédito'
                              : 'Sin crédito'
                          "
                        >
                          <label
                            class="switch s-icons s-outline s-outline-primary"
                          >
                            <input type="checkbox" v-model="credit" />
                            <span class="slider round"></span>
                          </label>
                        </b-form-group>
                        <!-- END CHECK CREDIT -->
                        <!-- FIXED PRICES -->
                        <div v-if="type === true">
                          <!-- PRICE -->
                          <ValidationProvider rules="required" name="precio">
                            <b-form-group
                              slot-scope="{ valid, errors }"
                              label="Precio base"
                            >
                              <b-form-input
                                v-money="money"
                                v-model="price"
                                name="precio"
                                type="text"
                                :state="errors[0] ? false : valid ? true : null"
                              ></b-form-input>
                              <b-form-invalid-feedback>
                                {{ errors[0] }}
                              </b-form-invalid-feedback>
                            </b-form-group>
                          </ValidationProvider>
                          <!-- END PRICE -->
                          <!-- PRICE -->
                          <ValidationProvider
                            rules="required"
                            name="multiplicador"
                          >
                            <b-form-group
                              slot-scope="{ valid, errors }"
                              label="Multiplicador"
                            >
                              <b-form-input
                                v-money="money"
                                v-model="multiplier"
                                name="multiplicador"
                                type="text"
                                :state="errors[0] ? false : valid ? true : null"
                              ></b-form-input>
                              <b-form-invalid-feedback>
                                {{ errors[0] }}
                              </b-form-invalid-feedback>
                            </b-form-group>
                          </ValidationProvider>
                          <!-- END PRICE -->
                        </div>
                        <!-- END FIXED PRICES -->
                        <!-- PRICES PER POUND -->
                        <div v-else>
                          <b-button
                            block
                            variant="secondary"
                            @click="openNewPrice()"
                            >Agregar precio</b-button
                          >
                          <new-price @save-price="savePrice" ref="newPrice" />
                          <b-table
                            ref="table"
                            responsive
                            hover
                            :items="prices"
                            :fields="columns"
                            :show-empty="true"
                          >
                            <template #cell(min)="row">
                              {{ row.item.min }}
                            </template>
                            <template #cell(max)="row">
                              {{
                                row.item.max === 100
                                  ? "100 en adelante"
                                  : row.item.max
                              }}
                            </template>
                            <template #cell(price)="row">
                              {{ myMoney(row.item.price) }}
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
                                      <polyline
                                        points="3 6 5 6 21 6"
                                      ></polyline>
                                      <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                                      ></path>
                                    </svg>
                                    Eliminar
                                  </b-dropdown-item>
                                </b-dropdown>
                              </div>
                            </template>
                          </b-table>
                        </div>
                        <!-- END PRICES PER POUND -->
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
    <!-- BEGIN MODALS -->
    <update-price ref="updatePrice" @update-price="updatePrice" />
    <!-- END MODALS -->
  </div>
</template>
<script>
import Vue from "vue";
import axios from "axios";
import { mapGetters } from "vuex";
import { VMoney } from "v-money";
import newPrice from "./prices-modals-new/new-price-modals.vue";
import updatePrice from "./prices-modals-new/update-price-modals.vue";

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
  metaInfo: { title: "Nuevo cliente" },
  directives: { money: VMoney },
  data() {
    return {
      money: {
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
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Nuevo cliente", active: true },
      ],
      id: 0,
      name: "",
      telephone: "",
      credit: false,
      type: false, //TRUE - FIXED PRICE
      checkedTelephone: true,
      alternativeTelephone: "",
      checkedAlternativeTelephone: true,
      nameCharge: "",
      telephoneCharge: "",
      checkedTelephoneCharge: true,
      alternativeTelephoneCharge: "",
      checkedAlternativeTelephoneCharge: true,
      multiplier: "$ 0.00",
      price: "$ 0.00",
      exchangeRate: "Q 7.50",
      prices: [],
      columns: [
        { key: "min", label: "Mínimo" },
        { key: "max", label: "Máximo" },
        { key: "price", label: "Precio" },
        { key: "action", label: "Acciones", class: "actions text-center" },
      ],
    };
  },
  components: {
    ValidationObserver,
    ValidationProvider,
    "new-price": newPrice,
    "update-price": updatePrice,
  },
  computed: {
    min() {
      if (this.prices.length > 0) {
        const numberMin = this.prices[this.prices.length - 1].max + 1;
        if (numberMin > 100) return 100;
        else return numberMin;
      } else return 1;
    },
    max() {
      return 100;
    },
  },
  mounted() {
    this.find();
  },
  methods: {
    ...mapGetters(["api", "userID"]),
    find() {
      axios
        .get(this.api() + `/customers/${this.$route.params.id}`)
        .then((response) => {
          this.id = response.data.id;

          this.name = response.data.name;
          this.telephone = response.data.telephone;

          const telephoneLength = this.telephone.length;
          if (telephoneLength === 0 || telephoneLength === 9)
            this.checkedTelephone = true;
          else this.checkedTelephone = false;

          this.alternativeTelephone = response.data.alternativeTelephone;
          if (this.alternativeTelephone === null)
            this.alternativeTelephone = "";
          const alternativeTelephoneLength = this.alternativeTelephone.length;
          if (
            alternativeTelephoneLength === 0 ||
            alternativeTelephoneLength === 9
          )
            this.checkedAlternativeTelephone = true;
          else this.checkedAlternativeTelephone = false;

          this.nameCharge =
            response.data.nameCharge === null ? "" : response.data.nameCharge;

          this.telephoneCharge = response.data.telephoneCharge;
          if (this.telephoneCharge === null) this.telephoneCharge = "";
          const telephoneChargeLength = this.telephoneCharge.length;
          if (telephoneChargeLength === 0 || telephoneChargeLength === 9)
            this.checkedTelephoneCharge = true;
          else this.checkedTelephoneCharge = false;

          this.alternativeTelephoneCharge =
            response.data.alternativeTelephoneCharge;
          if (this.alternativeTelephoneCharge === null)
            this.alternativeTelephoneCharge = "";
          const alternativeTelephoneChargeLength =
            this.alternativeTelephoneCharge.length;
          if (
            alternativeTelephoneChargeLength === 0 ||
            alternativeTelephoneChargeLength === 9
          )
            this.checkedAlternativeTelephoneCharge = true;
          else this.checkedAlternativeTelephoneCharge = false;

          this.multiplier = response.data.multiplicater;
          this.price = response.data.basePrice;
          this.exchangeRate = response.data.exchangeRate;
          this.type = response.data.type === 0 ? false : true;
          this.credit = response.data.credit === 0 ? false : true;
          this.prices = JSON.parse(response.data.prices);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    ifDelete(id) {
      if (this.prices[this.prices.length - 1].id !== id) {
        this.$swal({
          text: "Tiene que eliminar el ultimo registro",
          icon: "error",
        });
        return;
      }
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
      const index = this.prices.findIndex((x) => x.id === id);
      if (index !== -1) {
        this.prices.splice(index, 1);
      }
    },
    updatePrice(price) {
      const index = this.prices.findIndex((x) => x.id === price.id);
      if (index !== -1) {
        this.prices[index] = price;
      }
      this.$refs.table.refresh();
    },
    openNewPrice() {
      if (this.prices.length > 0) {
        if (this.prices[this.prices.length - 1].max === 100) {
          this.$swal({
            text: "Ya no se puede agregar más precios",
            icon: "error",
          });
          return;
        }
      }
      this.$refs.newPrice.openModal(this.min, this.max);
    },
    update(item) {
      const last = this.prices[this.prices.length - 1].id === item.id;
      this.$refs.updatePrice.openModal(item, last);
    },
    myMoney(value) {
      return eight.dollar(parseFloat(value));
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
    whatMasked(value) {
      return value === true ? "####-####" : "###-###-####";
    },
    savePrice(price) {
      this.prices.push(price);
    },
    handleSubmit() {
      if (this.type === false) {
        if (this.prices[this.prices.length - 1].max !== 100) {
          this.$swal({
            text: "Tiene que ingresar los precios de todos lo rangos",
            icon: "error",
          });
          return;
        }
      }
      let loader = this.$loading.show();
      axios
        .put(this.api() + `/customers/${this.id}`, {
          name: this.name,
          telephone: this.telephone,
          alternativeTelephone: this.alternativeTelephone,
          nameCharge: this.nameCharge,
          telephoneCharge: this.telephoneCharge,
          alternativeTelephoneCharge: this.alternativeTelephoneCharge,
          multiplier: this.converMaskToNumber(this.multiplier),
          price: this.converMaskToNumber(this.price),
          user: this.userID(),
          type: this.type,
          credit: this.credit,
          exchangeRate: this.converMaskToNumber(this.exchangeRate),
          prices: JSON.stringify(this.prices),
        })
        .then((response) => {
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          loader.hide();
          this.$router.push({
            path: `/cliente/lista`,
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
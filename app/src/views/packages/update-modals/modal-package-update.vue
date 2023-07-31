<template>
  <b-modal
    ref="modal_update_package"
    title="Actualizar paquete"
    title-tag="h4"
    size="xl"
    footer-class="justify-content-center"
    centered
  >
    <ValidationObserver ref="observer">
      <b-form>
        <!-- SEND -->
        <ValidationProvider rules="required" name="envía">
          <b-form-group slot-scope="{ valid, errors }" label="Envía:">
            <b-form-input
              v-model="send"
              name="envía"
              type="text"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-input>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END SEND -->
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
        <!-- CONTENT -->
        <ValidationProvider rules="required" name="contenido">
          <b-form-group slot-scope="{ valid, errors }" label="Contenido:">
            <b-form-input
              v-model="content"
              name="contenido"
              type="text"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-input>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END SEND -->
        <!-- CATEGORY -->
        <ValidationProvider
          tag="div"
          rules="required"
          name="categoría"
          v-slot="{ errors }"
        >
          <b-form-group
            label="Categoría"
            :state="errors.length > 0 ? false : null"
          >
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
        <!-- TOTAL -->
        <b-form-group label="Total">
          <h4>{{ myDollar(total) }}</h4>
        </b-form-group>
        <!-- END TOTAL -->
        <!-- CHECK STATUS PAYMENT -->
        <b-form-group :label="cancel === false ? 'POR COBRAR' : 'CANCELADO'">
          <label class="switch s-icons s-outline s-outline-primary">
            <input type="checkbox" v-model="cancel" />
            <span class="slider round"></span>
          </label>
        </b-form-group>
        <!-- END STATUS PAYMENT -->
        <!-- BALER -->
        <ValidationProvider
          tag="div"
          rules="required"
          name="empacadora"
          v-slot="{ errors }"
        >
          <b-form-group
            label="Empacadora"
            :state="errors.length > 0 ? false : null"
          >
            <!-- getShipments -->
            <multiselect
              id="empacadora"
              name="empacadora"
              ref="empacadora"
              v-model="baler"
              :options="balers"
              :show-labels="false"
              :searchable="true"
              label="name"
              track-by="name"
              placeholder="Seleccione una empacadora"
              @search-change="getBalers"
              :internal-search="false"
              :class="errors.length > 0 ? 'is-danger' : ''"
              :loading="loaderBaler"
              :custom-label="customLabel"
            >
            </multiselect>
            <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END BALER -->
        <!-- CORRELATIVE -->
        <ValidationProvider rules="required" name="correlativo">
          <b-form-group slot-scope="{ valid, errors }" label="Correlativo:">
            <b-form-input
              v-model="correlative"
              name="correlativo"
              type="number"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-input>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END CORRELATIVE -->
        <!-- SEND -->
        <ValidationProvider rules="required" name="guía de envío">
          <b-form-group slot-scope="{ valid, errors }" label="Guía de envío:">
            <b-form-input
              v-model="guide"
              name="guía de envío"
              type="text"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-input>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END SEND -->
        <!-- OBSERVATION -->
        <b-form-group label="Observación:">
          <b-form-textarea
            v-model="observation"
            type="text"
            rows="3"
            max-rows="6"
          ></b-form-textarea>
        </b-form-group>
        <!-- END OBSERVATION -->
      </b-form>
    </ValidationObserver>
    <template #modal-footer>
      <b-button variant="danger" @click="closeModal">Cancelar</b-button>
      <b-button variant="primary" @click="handleSubmit">Guardar</b-button>
    </template>
  </b-modal>
</template>
<script>
import Vue from "vue";
import axios from "axios";
import { mapGetters, mapMutations } from "vuex";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import { VMoney } from "v-money";
import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import es from "vee-validate/dist/locale/es";
import { eight } from "../../../utils/eight";

Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });

export default {
  directives: { money: VMoney },
  components: {
    ValidationObserver,
    ValidationProvider,
    Multiselect,
  },
  watch: {
    send: function (e) {
      this.send = e.toUpperCase();
    },
    content: function (e) {
      this.content = e.toUpperCase();
    },
  },
  data() {
    return {
      cancel: false,
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
      id: "",
      key: "",
      send: "",
      correlative: "",
      category: null,
      categories: [],
      loaderDepartament: false,
      loaderCategory: false,
      departament: null,
      departaments: [],
      type: null,
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
      office: "",
      offices: [],
      baler: null,
      balers: [],
      loaderBaler: false,
      content: "",
      weight: "",
      cost: "",
      multiplier: "",
      observation: "",
      total: 0,
      updatedMultiplier: true,
      guide: "",
    };
  },
  mounted() {
    this.getDepartment("");
    this.getOfficesGT();
  },
  methods: {
    ...mapGetters(["api", "getUpdatePackage"]),
    ...mapMutations(["setPackage"]),
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
    customLabel({ name, code }) {
      return `${name}-${code}`;
    },
    myDollar(value) {
      return eight.dollar(parseFloat(value));
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
    handleSubmit() {
      this.$refs["observer"]
        .validate()
        .then((isValid) => {
          if (isValid) {
            if (this.total === 0) {
              this.$swal({
                text: `El total no puede ser ${this.myDollar(this.total)}`,
                icon: "error",
              });
            } else {
              let loader = this.$loading.show();
              axios
                .post("https://translate.argosopentech.com/translate", {
                  q: this.content,
                  source: "es",
                  target: "en",
                  format: "text",
                })
                .then((response) => {
                  loader.hide();
                  this.setPackage({
                    id: this.id,
                    correlative: this.correlative,
                    key: this.key,
                    send: this.send,
                    departament: this.departament,
                    type: this.type,
                    content: this.content,
                    content_en: response.data.translatedText.toUpperCase(),
                    category: this.category,
                    office: this.office,
                    weight: this.converMaskToNumber(this.weight),
                    cost: this.converMaskToNumber(this.cost),
                    subtotal: this.total,
                    payment: this.cancel,
                    baler: this.baler,
                    guide: this.guide,
                    observation: this.observation,
                    updatedMultiplier: this.updatedMultiplier,
                    multiplier: this.multiplier,
                  });
                  this.clearData();
                  this.$emit("update-package");
                  this.closeModal();
                })
                .catch((err) => {
                  loader.hide();
                  console.log(err);
                });
            }
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getBalers(search) {
      this.loaderBaler = true;
      axios
        .get(this.api() + `/getBaler?filter=${search}`)
        .then((response) => {
          this.loaderBaler = false;
          this.balers = response.data;
        })
        .catch((error) => {
          console.log(error);
          this.loaderBaler = false;
        });
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
    closeModal() {
      this.$refs["modal_update_package"].hide();
    },
    openModal() {
      this.$refs["modal_update_package"].show();
      // PACKAGE
      const myPackage = this.getUpdatePackage();
      this.id = myPackage.id;
      this.correlative = myPackage.correlative;
      this.key = myPackage.key;
      this.send = myPackage.send;
      this.departament = myPackage.departament;
      this.type = myPackage.type;
      this.content = myPackage.content;
      this.weight = myPackage.weight;
      this.cost = myPackage.cost;
      this.total = myPackage.subtotal;
      this.cancel = myPackage.payment;
      this.category = myPackage.category;
      this.baler = myPackage.baler;
      this.guide = myPackage.guide;
      this.office = myPackage.office;
      this.observation = myPackage.observation;
      this.multiplier = myPackage.multiplier;
      this.updatedMultiplier = myPackage.updatedMultiplier;
      // END PACKAGE
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
    clearData() {
      this.send = "";
      this.departament = null;
      this.type = null;
      this.content = "";
      this.category = null;
      this.weight = "";
      this.cost = "";
      this.total = 0;
      this.cancel = false;
      this.baler = null;
      this.observation = "";
      this.guide = "";
      this.correlative = "";
    },
    isObjEmpty(obj) {
      return Object.keys(obj).length === 0;
    },
  },
};
</script>
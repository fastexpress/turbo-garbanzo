<template>
  <b-modal
    ref="modal_update_content"
    title="Agregar contenido"
    title-tag="h4"
    size="xl"
    footer-class="justify-content-center"
    centered
  >
    <ValidationObserver ref="observer">
      <b-form>
        <!-- QUANTITY -->
        <ValidationProvider rules="required" name="cantidad">
          <b-form-group slot-scope="{ valid, errors }" label="Cantidad:">
            <b-form-input
              @input="calculateSubtotal"
              v-model="quantity"
              name="cantidad"
              type="number"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-input>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END QUANTITY -->
        <!-- CONTENT-->
        <ValidationProvider rules="required" name="contenido">
          <b-form-group slot-scope="{ valid, errors }" label="Contenido:">
            <b-form-input
              v-model="content"
              @blur="translate"
              name="contenido"
              type="text"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-input>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END CONTENT -->
        <!-- CONTENT EN-->
        <ValidationProvider rules="required" name="contenido">
          <b-form-group
            slot-scope="{ valid, errors }"
            label="Contenido(Ingles):"
          >
            <b-form-input
              v-model="content_en"
              name="contenido"
              type="text"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-input>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END CONTENT EN-->
        <!-- PRICE-->
        <ValidationProvider rules="required" name="precio">
          <b-form-group slot-scope="{ valid, errors }" label="Precio:">
            <b-form-input
              @input="calculateSubtotal"
              v-model="price"
              v-money="dollar"
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
        <!-- TOTAL PAYMENT -->
        <h3>Subtotal: {{ myDollar(subtotal) }}</h3>
        <!-- END TOTAL PAYMENT -->
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
      id: 0,
      quantity: 0,
      content: "",
      content_en: "",
      price: "",
      subtotal: 0,
    };
  },
  methods: {
    calculateSubtotal() {
      if (this.quantity === "" || this.price === "") this.subtotal = 0;
      else
        this.subtotal = this.parseDecimal(
          this.parseDecimal(this.quantity) *
            this.parseDecimal(this.converMaskToNumber(this.price))
        );
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
    openModal(content) {
      this.id = content.id;
      this.quantity = content.quantity;
      this.content = content.content;
      this.content_en = content.content_en;
      this.price = content.price;
      this.subtotal = content.subtotal;
      this.$refs["modal_update_content"].show();
    },
    handleSubmit() {
      this.$refs["observer"].validate().then((isValid) => {
        if (isValid) {
          this.$emit("update-content", {
            id: this.id,
            quantity: this.quantity,
            content: this.content,
            content_en: this.content_en,
            price: this.converMaskToNumber(this.price),
            subtotal: this.subtotal,
          });
          this.closeModal();
        }
      });
    },
    closeModal() {
      this.$refs["modal_update_content"].hide();
    },
    translate() {
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
          this.content_en = response.data.translatedText.toUpperCase();
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
  },
};
</script>
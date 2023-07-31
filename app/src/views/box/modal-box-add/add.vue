<template>
  <b-modal
    ref="modal_add_box"
    title="Agregar caja"
    title-tag="h4"
    size="xl"
    footer-class="justify-content-center"
    centered
  >
    <ValidationObserver ref="observer">
      <b-form>
        <!-- WEIGHT KG-->
        <ValidationProvider rules="required" name="peso">
          <b-form-group slot-scope="{ valid, errors }" label="Peso (Kg):">
            <b-form-input
              v-model="weightKg"
              v-money="kg"
              name="peso"
              type="text"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-input>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END WEIGHT KG -->
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
import { VMoney } from "v-money";
import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import es from "vee-validate/dist/locale/es";
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
      kg: {
        decimal: ".",
        thousands: ",",
        prefix: "",
        suffix: " kg",
        precision: 2,
        masked: true,
      },
      weightKg: 0,
    };
  },
  methods: {
    parseDecimalFixed(value) {
      return parseFloat(value).toFixed(2);
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
    openModal() {
      this.$refs["modal_add_box"].show();
    },
    uuidv4() {
      return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, (c) =>
        (
          c ^
          (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (c / 4)))
        ).toString(16)
      );
    },
    handleSubmit() {
      this.$refs["observer"].validate().then((isValid) => {
        if (isValid) {
          this.$emit("save-box", {
            id: this.uuidv4(),
            weightKg: this.converMaskToNumber(this.weightKg),
          });
          this.closeModal();
        }
      });
    },
    closeModal() {
      this.weightKg = "";
      this.$refs["modal_add_box"].hide();
    },
  },
};
</script>
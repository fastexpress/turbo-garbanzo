<template>
  <b-modal
    ref="modal_add_price"
    title="Agregar precio"
    title-tag="h4"
    size="xl"
    footer-class="justify-content-center"
    centered
  >
    <ValidationObserver ref="observer">
      <b-form>
        <!-- RANGE -->
        <ValidationProvider rules="required" name="precio">
          <b-form-group slot-scope="{ valid, errors }" label="Precio">
            <b-form-input
              v-model="range"
              type="range"
              :min="min"
              :max="max"
              :state="errors[0] ? false : valid ? true : null"
            />
            <div class="mt-2">
              Rango de libras:
              {{
                Number.parseInt(range) === 100
                  ? `${min} - 100 libras en adelante`
                  : `${min} - ${range}`
              }}
            </div>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END RANGE -->
        <!-- PRICES -->
        <ValidationProvider rules="required" name="precio">
          <b-form-group slot-scope="{ valid, errors }" label="Precio">
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
        <!-- END PRICES -->
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
// mapMutations
import { mapMutations } from "vuex";
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
  watch: {},
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
      min: 1,
      max: 100,
      range: 1,
      price: "",
    };
  },
  mounted() {},
  methods: {
    ...mapMutations(["setPrice"]),
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
    uuidv4() {
      return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, (c) =>
        (
          c ^
          (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (c / 4)))
        ).toString(16)
      );
    },
    handleSubmit() {
      this.$refs["observer"]
        .validate()
        .then((isValid) => {
          if (isValid) {
            this.$emit("save-price", {
              id: this.uuidv4(),
              min: Number.parseInt(this.min),
              max: Number.parseInt(this.range),
              price: this.converMaskToNumber(this.price),
            });
            this.clearData();
            this.closeModal();
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    closeModal() {
      this.$refs["modal_add_price"].hide();
    },
    openModal(min, max) {
      this.$refs["modal_add_price"].show();
      this.min = min;
      this.max = max;
      this.range = min;
    },
    clearData() {
      this.price = "";
    },
  },
};
</script>
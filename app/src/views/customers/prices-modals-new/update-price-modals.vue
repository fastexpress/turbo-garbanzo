<template>
  <b-modal
    ref="modal_update_price"
    title="Editar precio"
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
              :disabled="readOnly"
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
      id: 0,
      min: 1,
      max: 100,
      range: 1,
      price: "",
      readOnly: true,
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
    handleSubmit() {
      this.$refs["observer"]
        .validate()
        .then((isValid) => {
          if (isValid) {
            this.$emit("update-price", {
              id: this.id,
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
      this.$refs["modal_update_price"].hide();
    },
    openModal(price, last) {
      this.$refs["modal_update_price"].show();
      this.id = price.id;
      this.min = price.min;
      if (last === true) this.max = 100;
      else this.max = price.max;
      this.readOnly = !last;
      this.range = price.max;
      this.price = price.price;
    },
    clearData() {
      this.price = "";
    },
  },
};
</script>
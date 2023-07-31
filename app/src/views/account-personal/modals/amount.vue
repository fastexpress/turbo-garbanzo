<template>
  <b-modal
    ref="modal_amount_bank"
    title="Actualizar monto almacenado"
    title-tag="h4"
    size="xl"
    footer-class="justify-content-center"
    centered
  >
    <ValidationObserver ref="observer">
      <b-form>
        <!-- RECEIVE -->
        <ValidationProvider rules="required" name="monto">
          <b-form-group slot-scope="{ valid, errors }" label="Monto">
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
        <!-- END RECEIVE -->
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
import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import { mapGetters } from "vuex";
import es from "vee-validate/dist/locale/es";
import { VMoney } from "v-money";

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
      id: 0,
      amount: 0,
      quetzal: {
        decimal: ".",
        thousands: ",",
        prefix: "Q ",
        suffix: "",
        precision: 2,
        masked: true,
      },
    };
  },
  methods: {
    ...mapGetters(["api", "userID"]),
    openModal(account) {
      this.id = account.id;
      this.amount = account.amountBank;
      this.$refs["modal_amount_bank"].show();
    },
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
      this.$refs["observer"].validate().then((isValid) => {
        if (isValid) {
          let loader = this.$loading.show();
          axios
            .put(this.api() + `/updateAmountBank/${this.id}`, {
              amountBank: this.converMaskToNumber(this.amount),
              user: this.userID(),
            })
            .then((response) => {
              loader.hide();
              this.$swal({ text: `${response.data.message}`, icon: "success" });
              this.$emit("save");
              this.closeModal();
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
    closeModal() {
      this.id = 0;
      this.amount = 0;
      this.$refs["modal_amount_bank"].hide();
      this.$emit("get");
    },
  },
};
</script>

<template>
  <b-modal
    ref="modal_code_ups"
    title="Validar dirección"
    title-tag="h4"
    size="xl"
    footer-class="justify-content-center"
    centered
  >
    <ValidationObserver ref="observer">
      <b-form>
        <!-- RECEIVE -->
        <ValidationProvider rules="required" name="código de rastreo">
          <b-form-group
            slot-scope="{ valid, errors }"
            label="Código de rastreo(UPS):"
          >
            <b-form-input
              ref="code"
              v-model="codeUPS"
              name="código de rastreo"
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
      codeUPS: "1Z81F36Y04",
    };
  },
  methods: {
    ...mapGetters(["api", "userID"]),
    openModal(upsPackage) {
      this.id = upsPackage.id;
      if (upsPackage.codeUPS === null || upsPackage.codeUPS === "")
        this.codeUPS = "1Z81F36Y04";
      else this.codeUPS = upsPackage.codeUPS;
      this.$refs["modal_code_ups"].show();
      // this.$refs["code"].$el.focus();
    },
    handleSubmit() {
      this.$refs["observer"].validate().then((isValid) => {
        if (isValid) {
          let loader = this.$loading.show();
          axios
            .post(this.api() + "/package-ups-code", {
              id: this.id,
              codeUPS: this.codeUPS,
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
      this.codeUPS = "";
      this.$refs["modal_code_ups"].hide();
    },
  },
};
</script>
<template>
  <b-modal
    ref="modal_route_comment"
    title="Paquete en ruta"
    title-tag="h4"
    size="xl"
    footer-class="justify-content-center"
    centered
  >
    <ValidationObserver ref="observer">
      <b-form>
        <!-- RECEIVE -->
        <ValidationProvider rules="required" name="código de rastreo">
          <b-form-group slot-scope="{ valid, errors }" label="Observación:">
            <b-form-textarea
              v-model="observation"
              type="text"
              rows="3"
              max-rows="6"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-textarea>
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
      observation: "",
    };
  },
  methods: {
    ...mapGetters(["api", "userID"]),
    openModal(upsPackage) {
      this.id = upsPackage.id;
      this.observation = upsPackage.inRouteComment;
      this.$refs["modal_route_comment"].show();
    },
    handleSubmit() {
      this.$refs["observer"].validate().then((isValid) => {
        if (isValid) {
          let loader = this.$loading.show();
          axios
            .post(this.api() + "/package-ups-route-comment", {
              id: this.id,
              inRouteComment: this.observation,
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
      this.observation = "";
      this.$refs["modal_route_comment"].hide();
    },
  },
};
</script>
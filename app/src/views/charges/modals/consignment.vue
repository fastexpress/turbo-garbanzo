<template>
  <b-modal
    ref="modal_consigment"
    title="Cambiar a remesa"
    title-tag="h4"
    size="xl"
    footer-class="justify-content-center"
    centered
  >
    <ValidationObserver ref="observer">
      <b-form>
        <!-- RECEIVE -->
        <ValidationProvider rules="required" name="número de clave">
          <b-form-group slot-scope="{ valid, errors }" label="Número de clave:">
            <b-form-input
              v-model="keyNumber"
              name="número de clave"
              type="text"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-input>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END RECEIVE -->
        <!-- RECEIVE -->
        <ValidationProvider rules="required" name="envía">
          <b-form-group slot-scope="{ valid, errors }" label="Envía:">
            <b-form-input
              v-model="nameSend"
              name="envía"
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
Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });
export default {
  components: {
    ValidationObserver,
    ValidationProvider,
  },
  data() {
    return {
      id: 0,
      keyNumber: "",
      nameSend: "",
    };
  },
  methods: {
    ...mapGetters(["api", "userID"]),
    openModal(upsPackage) {
      this.id = upsPackage.id;
      this.$refs["modal_consigment"].show();
    },
    handleSubmit() {
      this.$refs["observer"].validate().then((isValid) => {
        if (isValid) {
          let loader = this.$loading.show();
          axios
            .post(this.api() + "/charge/changeToConsignment", {
              id: this.id,
              keyNumber: this.keyNumber,
              nameSend: this.nameSend,
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
      this.keyNumber = "";
      this.nameSend = "";
      this.$refs["modal_consigment"].hide();
    },
  },
};
</script>
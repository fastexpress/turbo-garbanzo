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
                  <h4>Nueva empacadora</h4>
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
                            label="Nombre de la empacadora:"
                          >
                            <b-form-input
                              v-model="name"
                              @input="getCode"
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
                        <!-- CODE -->
                        <ValidationProvider rules="required" name="código">
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Código de la empacadora:"
                          >
                            <b-form-input
                              v-model="code"
                              name="código"
                              type="text"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END CODE -->
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
  </div>
</template>
<script>
import Vue from "vue";
import axios from "axios";
import { mapGetters } from "vuex";
import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import es from "vee-validate/dist/locale/es";

Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });
export default {
  metaInfo: { title: "Nueva empacadora" },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Nueva empacadora", active: true },
      ],
      id: 0,
      name: "",
      code: "",
    };
  },
  components: {
    ValidationObserver,
    ValidationProvider,
  },
  mounted() {
    this.find();
  },
  methods: {
    ...mapGetters(["api"]),
    find() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + `/balers/${this.$route.params.id}`)
        .then((response) => {
          loader.hide();
          this.id = response.data.id;
          this.name = response.data.name;
          this.code = response.data.code;
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    getCode() {
      const names = this.name.split(" ");
      let code = "";
      names.forEach((character) => {
        if (character !== "") {
          code += character[0].toUpperCase();
        }
      });
      this.code = code;
    },
    handleSubmit() {
      let loader = this.$loading.show();
      axios
        .put(this.api() + `/balers/${this.id}`, {
          name: this.name,
          code: this.code,
        })
        .then((response) => {
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          loader.hide();
          this.$router.push({
            path: `/empacadora/lista`,
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
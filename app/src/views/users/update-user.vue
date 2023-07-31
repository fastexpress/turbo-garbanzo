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
                  <h4>Actualizar Usuario</h4>
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
                            label="Nombre del usuario:"
                          >
                            <b-form-input
                              v-model="name"
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
                        <!-- TELEPHONE -->
                        <ValidationProvider
                          rules="required|length:9"
                          name="télefono"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Télefono del usuario:"
                          >
                            <b-form-input
                              v-model="user"
                              name="télefono"
                              v-mask="'####-####'"
                              type="text"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END TELEPHONE -->
                        <!-- PASSWORD -->
                        <ValidationProvider
                          rules=""
                          name="contraseña"
                          vid="password"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Contraseña del usuario:"
                          >
                            <b-form-input
                              v-model="password"
                              name="contraseña"
                              type="password"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END PASSWORD -->
                        <!--CONFIRM PASSWORD -->
                        <ValidationProvider
                          :rules="
                            password !== '' ? 'required|confirmed:password' : ''
                          "
                          name="confirmar contraseña"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Confirmar contraseña del usuario:"
                          >
                            <b-form-input
                              v-model="confirmPassword"
                              name="confirmar contraseña"
                              type="password"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END CONFIRM PASSWORD -->
                        <!-- ROL -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="rol"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Rol"
                            :state="errors.length > 0 ? false : null"
                          >
                            <!-- getShipments -->
                            <multiselect
                              id="rol"
                              name="rol"
                              ref="rol"
                              v-model="rol"
                              :options="rols"
                              :allow-empty="false"
                              :show-labels="false"
                              :searchable="true"
                              label="name"
                              track-by="name"
                              placeholder="Seleccione un rol"
                              @search-change="getRols"
                              :internal-search="false"
                              :class="errors.length > 0 ? 'is-danger' : ''"
                              :loading="isLoadingRols"
                              @close="validatePassaport"
                            >
                            </multiselect>
                            <b-form-invalid-feedback
                              :state="errors.length > 0 ? false : null"
                            >
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END ROL -->
                        <!-- PASSPORT -->
                        <ValidationProvider
                          rules="required"
                          name="pasaporte"
                          v-if="showPassaport"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Pasaporte:"
                          >
                            <b-form-input
                              v-model="passport"
                              name="pasaporte"
                              type="text"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END PASSPORT -->
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
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });
export default {
  metaInfo: { title: "Actualizar usuario" },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Actualizar usuario", active: true },
      ],
      id: 0,
      name: "",
      user: "",
      password: "",
      confirmPassword: "",
      rol: null,
      rols: [],
      isLoadingRols: false,
      passport: "",
      showPassaport: false,
    };
  },
  components: {
    ValidationObserver,
    ValidationProvider,
    Multiselect,
  },
  mounted() {
    this.find();
  },
  methods: {
    ...mapGetters(["api"]),
    validatePassaport() {
      if (this.rol !== null) {
        if (this.rol.name === "Viajero") {
          this.showPassaport = true;
        } else {
          this.showPassaport = false;
        }
      } else {
        this.showPassaport = false;
      }
    },
    getRols(search) {
      let loader = this.$loading.show();
      this.isLoadingRols = true;
      axios
        .get(this.api() + `/getRols?filter=${search}`)
        .then((response) => {
          this.isLoadingRols = false;
          this.rols = response.data;
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          this.isLoadingRols = false;
          loader.hide();
        });
    },
    find() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + `/users/${this.$route.params.id}`)
        .then((response) => {
          loader.hide();
          const { user, rol } = response.data;

          this.id = user.id;
          this.name = user.name;
          this.user = user.user;
          this.passport = user.passport;
          this.rol = {
            id: rol.id,
            name: rol.name,
          };
          this.validatePassaport();
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    handleSubmit() {
      let loader = this.$loading.show();
      axios
        .put(this.api() + `/users/${this.id}`, {
          name: this.name,
          user: this.user,
          password: this.password,
          idRol: this.rol.id,
          passport: this.passport,
        })
        .then((response) => {
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          loader.hide();
          this.$router.push({
            path: `/usuario/lista`,
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
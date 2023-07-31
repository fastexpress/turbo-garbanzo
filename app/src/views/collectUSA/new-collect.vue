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
                  <h4>Nueva Recolección</h4>
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
                            label="Nombre del cliente:"
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
                        <!-- ADDRESS -->
                        <ValidationProvider rules="required" name="dirección">
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Dirección del cliente:"
                          >
                            <b-form-input
                              v-model="address"
                              name="dirección"
                              type="text"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END ADRESS -->
                        <!-- DEPARTAMENT -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="departamento"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Departamento de cliente"
                            :state="errors.length > 0 ? false : null"
                          >
                            <multiselect
                              id="departamento"
                              name="departamento"
                              ref="departamento"
                              v-model="departament"
                              :options="departaments"
                              :allow-empty="false"
                              :show-labels="false"
                              :searchable="true"
                              label="name"
                              track-by="name"
                              placeholder="Seleccione departamento"
                              :class="errors.length > 0 ? 'is-danger' : ''"
                              @select="getTowns"
                            >
                            </multiselect>
                            <b-form-invalid-feedback
                              :state="errors.length > 0 ? false : null"
                            >
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END DEPARTAMENT -->
                        <!-- TOWN -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="municipio"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Municipio de cliente"
                            :state="errors.length > 0 ? false : null"
                          >
                            <multiselect
                              id="municipio"
                              name="municipio"
                              ref="municipio"
                              v-model="town"
                              :options="towns"
                              :allow-empty="false"
                              :show-labels="false"
                              :searchable="true"
                              label="name"
                              track-by="name"
                              placeholder="Seleccione un municipio"
                              :class="errors.length > 0 ? 'is-danger' : ''"
                            >
                            </multiselect>
                            <b-form-invalid-feedback
                              :state="errors.length > 0 ? false : null"
                            >
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END TOWN -->
                        <!-- CHECK TELEPHONE -->
                        <b-form-group
                          :label="
                            checkedTelephone === true
                              ? 'Número GT'
                              : 'Número USA'
                          "
                        >
                          <label
                            class="switch s-icons s-outline s-outline-primary"
                          >
                            <input type="checkbox" v-model="checkedTelephone" />
                            <span class="slider round"></span>
                          </label>
                        </b-form-group>
                        <!-- END CHECK TELEPHONE -->
                        <!-- TELEFONO -->
                        <ValidationProvider
                          :rules="
                            checkedTelephone === true
                              ? 'required|length:9'
                              : 'required|length:12'
                          "
                          name="teléfono"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Teléfono del cliente:"
                          >
                            <b-form-input
                              v-model="telephone"
                              name="teléfono"
                              type="text"
                              v-mask="whatMasked(checkedTelephone)"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END TELEFONO -->
                        <!-- CHECK TELEPHONE ALTERNATIVE -->
                        <b-form-group
                          :label="
                            checkedAlternativeTelephone === true
                              ? 'Número GT'
                              : 'Número USA'
                          "
                        >
                          <label
                            class="switch s-icons s-outline s-outline-primary"
                          >
                            <input
                              type="checkbox"
                              v-model="checkedAlternativeTelephone"
                            />
                            <span class="slider round"></span>
                          </label>
                        </b-form-group>
                        <!-- END CHECK TELEPHONE ALTERNATIVE -->
                        <!-- TELEFONO -->
                        <ValidationProvider
                          :rules="
                            checkedAlternativeTelephone === true
                              ? 'length:9'
                              : 'length:12'
                          "
                          name="teléfono"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Teléfono del cliente(Alternativo):"
                          >
                            <b-form-input
                              v-model="alternativeTelephone"
                              name="teléfono"
                              type="text"
                              v-mask="whatMasked(checkedAlternativeTelephone)"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END TELEFONO ALTERNATIVE -->
                        <!-- SHIPMENT -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="fecha"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Fecha:"
                            :state="errors.length > 0 ? false : null"
                          >
                            <flatpickr
                              id="fecha"
                              name="fecha"
                              ref="fecha"
                              v-model="date"
                              :config="config"
                              class="form-control flatpickr active"
                            ></flatpickr>
                            <b-form-invalid-feedback
                              :state="errors.length > 0 ? false : null"
                            >
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END SHIPMENT -->
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
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import es from "vee-validate/dist/locale/es";
import "@/assets/sass/forms/switches.scss";

// DATE
import "flatpickr/dist/flatpickr.min.css";
import "@/assets/sass/forms/custom-flatpickr.css";
import flatpickr from "flatpickr";
import { Spanish } from "flatpickr/dist/l10n/es";
import Vueflatpickr from "vue-flatpickr-component";
flatpickr.localize(Spanish);
// END DATE

Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });
export default {
  metaInfo: { title: "Nueva recolección" },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Nueva recolección", active: true },
      ],
      config: {
        wrap: true, // set wrap to true only when using 'input-group'
        altFormat: "d-m-Y",
        altInput: true,
        dateFormat: "Y-m-d",
        locale: Spanish, // locale for this instance only
      },
      name: "",
      address: "",
      telephone: "",
      checkedTelephone: true,
      alternativeTelephone: "",
      checkedAlternativeTelephone: true,
      departament: null,
      departaments: [],
      town: null,
      towns: [],
      date: new Date(),
    };
  },
  components: {
    ValidationObserver,
    ValidationProvider,
    Multiselect,
    flatpickr: Vueflatpickr,
  },
  mounted() {
    this.getDepartment("");
  },
  methods: {
    ...mapGetters(["api", "userID"]),
    whatMasked(value) {
      return value === true ? "####-####" : "###-###-####";
    },
    dateGT(date) {
      const newDate = this.$moment(date);
      return newDate.format("DD-MM-YYYY");
    },
    getDepartment(search) {
      let loader = this.$loading.show();
      axios
        .get(this.api() + `/getDepartment?filter=${search}`)
        .then((response) => {
          this.departaments = response.data;
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          loader.hide();
        });
    },
    getTowns(departament) {
      this.town = null;
      let loader = this.$loading.show();
      axios
        .get(this.api() + `/getTowns?idDepartament=${departament.id}`)
        .then((response) => {
          this.towns = response.data;
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          loader.hide();
        });
    },
    handleSubmit() {
      let loader = this.$loading.show();
      axios
        .post(this.api() + "/collect", {
          name: this.name,
          address: this.address,
          telephone: this.telephone,
          alternativeTelephone: this.alternativeTelephone,
          town: this.town.id,
          date: this.$moment(this.date).format("YYYY-MM-DD"),
          userCreated: this.userID(),
          userUpdated: this.userID(),
        })
        .then((response) => {
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          loader.hide();
          this.$router.push({
            path: `/recoleccion/usa/lista`,
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
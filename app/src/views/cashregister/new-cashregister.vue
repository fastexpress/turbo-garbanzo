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
                  <h4>Nueva caja</h4>
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
                            label="Nombre de la caja:"
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
                        <!-- CHECK TYPE MONEY -->
                        <b-form-group
                          :label="check === true ? 'Quetzal' : 'Dólar'"
                        >
                          <label
                            class="switch s-icons s-outline s-outline-primary"
                          >
                            <input type="checkbox" v-model="check" />
                            <span class="slider round"></span>
                          </label>
                        </b-form-group>
                        <!-- END CHECK TYPE MONEY -->
                        <!-- AMOUNT -->
                        <ValidationProvider rules="required" name="monto">
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Monto"
                          >
                            <b-form-input
                              v-money="check === true ? quetzal : dollar"
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
                        <!-- END AMOUNT -->
                        <!-- USER -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="encargado"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Encargado"
                            :state="errors.length > 0 ? false : null"
                          >
                            <!-- getShipments -->
                            <multiselect
                              id="encargado"
                              name="encargado"
                              ref="encargado"
                              v-model="user"
                              :options="users"
                              :show-labels="false"
                              :searchable="true"
                              label="name"
                              track-by="name"
                              placeholder="Seleccione un encargado"
                              @search-change="getUsers"
                              :internal-search="false"
                              :class="errors.length > 0 ? 'is-danger' : ''"
                              :loading="loaderUser"
                            >
                            </multiselect>
                            <b-form-invalid-feedback
                              :state="errors.length > 0 ? false : null"
                            >
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END USER -->
                        <!-- OBSERVATION -->
                        <b-form-group label="Descripción:">
                          <b-form-textarea
                            v-model="description"
                            type="text"
                            rows="3"
                            max-rows="6"
                          ></b-form-textarea>
                        </b-form-group>
                        <!-- END OBSERVATION -->
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
import { VMoney } from "v-money";

Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });
export default {
  metaInfo: { title: "Nueva caja" },
  directives: { money: VMoney },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Nueva caja", active: true },
      ],
      quetzal: {
        decimal: ".",
        thousands: ",",
        prefix: "Q ",
        suffix: "",
        precision: 2,
        masked: true,
      },
      dollar: {
        decimal: ".",
        thousands: ",",
        prefix: "$ ",
        suffix: "",
        precision: 2,
        masked: true,
      },
      name: "",
      bank: "",
      amount: "",
      check: false,
      description: "",
      user: null,
      users: [],
      loaderUser: false,
    };
  },
  components: {
    ValidationObserver,
    ValidationProvider,
    Multiselect,
  },
  methods: {
    ...mapGetters(["api", "userID"]),
    getUsers(search) {
      this.loaderUser = true;
      axios
        .get(this.api() + `/getUser?filter=${search}`)
        .then((response) => {
          this.loaderUser = false;
          this.users = response.data.data;
        })
        .catch((error) => {
          console.log(error);
          this.loaderUser = false;
        });
    },
    converMaskToNumber(value) {
      var myNumber = "";
      for (var i = 0; i < value.length; i += 1) {
        if (
          value.charAt(i) === "$" ||
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
      let loader = this.$loading.show();
      axios
        .post(this.api() + "/cashregister", {
          name: this.name,
          sign: this.check === true ? "Q" : "$",
          amount: this.converMaskToNumber(this.amount),
          inCharge: this.user.id,
          user: this.userID(),
          description: this.description,
        })
        .then((response) => {
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          loader.hide();
          this.$router.push({
            path: `/caja/lista`,
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
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
                  <h4>Actualizar Precio</h4>
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
                        <!-- TYPE -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="entrega"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Entregar en"
                            :state="errors.length > 0 ? false : null"
                          >
                            <multiselect
                              id="entrega"
                              name="entrega"
                              ref="entrega"
                              v-model="type"
                              :options="types"
                              :allow-empty="false"
                              :show-labels="false"
                              :searchable="true"
                              label="name"
                              track-by="name"
                              placeholder="Seleccione tipo de entrega"
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
                        <!-- END TYPE -->
                        <!-- CATEGORIES -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="categoria"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Categorias"
                            :state="errors.length > 0 ? false : null"
                          >
                            <multiselect
                              id="categoria"
                              name="categoria"
                              ref="categoria"
                              :multiple="true"
                              v-model="category"
                              :options="categories"
                              :allow-empty="false"
                              :show-labels="false"
                              :searchable="true"
                              label="name"
                              track-by="name"
                              placeholder="Seleccione categorías"
                              @search-change="getCategory"
                              :internal-search="false"
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
                        <!-- END CATEGORIES -->
                        <!-- CATEGORIES -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="departamento"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Departamentos"
                            :state="errors.length > 0 ? false : null"
                          >
                            <multiselect
                              id="departamento"
                              name="departamento"
                              ref="departamento"
                              :multiple="true"
                              v-model="departament"
                              :options="departaments"
                              :allow-empty="false"
                              :show-labels="false"
                              :searchable="true"
                              label="name"
                              track-by="name"
                              placeholder="Seleccione departamentos"
                              @search-change="getDepartment"
                              :internal-search="false"
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
                        <!-- END CATEGORIES -->
                        <!-- PRICE -->
                        <ValidationProvider rules="required" name="precio">
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Precio base"
                          >
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
                        <!-- END PRICE -->
                        <!-- PRICE -->
                        <ValidationProvider
                          rules="required"
                          name="multiplicador"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Multiplicador"
                          >
                            <b-form-input
                              v-money="money"
                              v-model="multiplier"
                              name="multiplicador"
                              type="text"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END PRICE -->
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
import { VMoney } from "v-money";
import "vue-multiselect/dist/vue-multiselect.min.css";
import { eight } from "../../utils/eight";

import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import es from "vee-validate/dist/locale/es";

Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });

export default {
  metaInfo: { title: "Actualizar precio" },
  directives: { money: VMoney },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Actualizar precio", active: true },
      ],
      money: {
        decimal: ".",
        thousands: ",",
        prefix: "$ ",
        suffix: "",
        precision: 2,
        masked: true,
      },
      id: 0,
      multiplier: 0,
      price: 0,
      category: [],
      categories: [],
      departament: [],
      departaments: [],
      type: null,
      types: [
        {
          id: 1,
          name: "Oficina",
        },
        {
          id: 2,
          name: "Dirección",
        },
      ],
    };
  },
  components: {
    ValidationObserver,
    ValidationProvider,
    Multiselect,
  },
  mounted() {
    // this.getCategory("");
    // this.getDepartment("");
    this.find();
  },
  methods: {
    ...mapGetters(["api"]),
    myDollar(value) {
      return eight.dollar(parseFloat(value));
    },
    find() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + `/prices/${this.$route.params.id}`)
        .then((response) => {
          loader.hide();
          this.id = response.data.id;
          this.type = this.types.find(
            (x) => x.name === response.data.isDelivery
          );
          this.multiplier = this.myDollar(response.data.multiplicater);
          this.price = this.myDollar(response.data.basePrice);
          this.category = response.data.categories;
          this.departament = response.data.departaments;
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    converMaskToNumber(value) {
      var myNumber = "";
      for (var i = 0; i < value.length; i += 1) {
        if (
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
    getCategory(search) {
      let loader = this.$loading.show();
      axios
        .get(this.api() + `/getCategory?filter=${search}`)
        .then((response) => {
          this.categories = response.data;
          loader.hide();
        })
        .catch((error) => {
          console.log(error);
          loader.hide();
        });
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
    handleSubmit() {
      let loader = this.$loading.show();
      axios
        .put(this.api() + `/prices/${this.id}`, {
          isDelivery: this.type.name,
          multiplicater: this.converMaskToNumber(this.multiplier),
          priceBase: this.converMaskToNumber(this.price),
          categories: JSON.stringify(this.category),
          departaments: JSON.stringify(this.departament),
        })
        .then((response) => {
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          loader.hide();
          this.$router.push({
            path: `/precios/lista`,
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
<style>
.is-danger .multiselect__tags {
  border: 1px solid #f86c6b !important;
}
</style>